<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\AttachmentGroup;
use function GuzzleHttp\Psr7\parse_query;

/**
 * Class WpAttachment
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models
 */
class WpAttachment
{
    const POST_META_CONTENTHUB_ID = 'contenthub_id';
    const POST_META_COPYRIGHT = 'attachment_copyright';

    public static function register()
    {
        // Add custom copyright field to image attachments
        add_filter('attachment_fields_to_edit', [__CLASS__, 'add_copyright_field_to_media_uploader'], null, 2);
        add_filter('attachment_fields_to_save', [__CLASS__, 'add_copyright_field_to_media_uploader_save'], null, 2);
        // load existing value from caption field and pass to acf
        add_filter('acf/load_value/key=' . AttachmentGroup::CAPTION_FIELD_KEY, [__CLASS__, 'add_caption_value_to_markdown_caption_field'], null, 3);
        add_action('admin_head', [__CLASS__, 'remove_caption_field']);
        add_filter('acf/update_value/key='.AttachmentGroup::CAPTION_FIELD_KEY, [__CLASS__, 'save_caption_to_wordpress'], null, 3);

        // Make attachments private
        add_filter('wp_update_attachment_metadata', [__CLASS__, 'wp_update_attachment_metadata'], 1000, 2);
    }

    public static function save_caption_to_wordpress($value, $postId, $field){
        wp_update_post([
            'ID' => $postId,
            'post_excerpt' => $value
        ]);
        return $value;
    }

    public static function add_caption_value_to_markdown_caption_field($value, $postId, $field){
        $caption = get_the_excerpt($postId) ?? $value;

        return $caption;
    }

    public static function remove_caption_field() {
        //unfortunately we have to hide it through css since it's not possible to hide the field through code as if WP 3.5+
        // for more info: https://wordpress.stackexchange.com/a/94476
        echo
        "<style>
                .media-sidebar .setting[data-setting=caption]
                .attachment-details .setting[data-setting=caption],
                .acf-media-modal .media-sidebar .attachment-details .setting[data-setting=caption] {
                    display: none;
                }
        </style>";
    }

    public static function wp_update_attachment_metadata($data, $postId)
    {
        $postMeta = get_post_meta($postId);

        // Check that attachment meta contains s3 info so we can set the visibility of the object
        if (isset($postMeta['amazonS3_info'][0]) && $s3Info = unserialize($postMeta['amazonS3_info'][0])) {
            static::set_s3_object_visibility($s3Info['bucket'], $s3Info['key'], 'private');
        }

        return $data;
    }

    public static function wp_get_attachment_url($url, $post_id)
    {
        if (is_admin()) {
            // Create signed url that expires after 3600 seconds (1 hour)
            return as3cf_get_secure_attachment_url($post_id, 3600);
        }

        return $url;
    }

    /**
     * Adding a "Copyright" field to the media uploader $form_fields array
     *
     * @param array $form_fields
     * @param object $post
     *
     * @return array
     */
    public static function add_copyright_field_to_media_uploader($form_fields, $post)
    {
        // Only add copyright field to image attachments
        if (!str_contains($post->post_mime_type, 'image')) {
            return $form_fields;
        }
        $form_fields['copyright_field'] = [
            'label' => __('Copyright'),
            'input' => 'text',
            'value' => get_post_meta($post->ID, static::POST_META_COPYRIGHT, true),

        ];
        return $form_fields;
    }


    /**
     * Save our new "Copyright" field
     *
     * @param object $post
     * @param object $attachment
     *
     * @return array
     */
    public static function add_copyright_field_to_media_uploader_save($post, $attachment)
    {
        if (isset($attachment['copyright_field']) && ! empty($attachment['copyright_field'])) {
            update_post_meta($post['ID'], static::POST_META_COPYRIGHT, $attachment['copyright_field']);
        } else {
            delete_post_meta($post['ID'], static::POST_META_COPYRIGHT);
        }

        return $post;
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public static function id_from_contenthub_id($id)
    {
        global $wpdb;
        return $wpdb->get_var(
            $wpdb->prepare("SELECT post_id FROM wp_postmeta WHERE meta_key=%s AND meta_value=%s", static::POST_META_CONTENTHUB_ID, $id)
        );
    }

    /**
     * @param $postId
     *
     * @return null|string
     */
    public static function contenthub_id($postId)
    {
        return get_post_meta($postId, static::POST_META_CONTENTHUB_ID, true) ?: null;
    }

    /**
     * @param $id
     *
     * Deleted the attachment with matching contenthub id
     *
     * @return null|string
     */
    public static function delete_by_contenthub_id($id)
    {
        if ($attachmentId = static::id_from_contenthub_id($id)) {
            return wp_delete_post($attachmentId, true);
        }
    }

    public static function upload_attachment($postId, $file)
    {
        if (is_null($file)) {
            return null;
        }

        // If attachment already exists then update meta data and return the id
        if ($existingId = static::id_from_contenthub_id($file->id)) {
            static::update_attachment($existingId, $file);
            return $existingId;
        }

        // Make sure that url has a scheme
        if (!isset(parse_url($file->url)['scheme'])) {
            $file->url = 'http:' . $file->url;
        }

        $rawFileName = basename($file->url);
        // Sanitize the new file name so WordPress will upload it
        $fileName = static::sanizite_file_name($rawFileName, $file->url);


        // Make sure to sanitize the file name so urls with spaces and other special chars will work
        $file->url = str_replace($rawFileName, urlencode($rawFileName), $file->url);

        // Getting file stream
        if (!$fileStream = @file_get_contents($file->url)) {
            if (!$fileStream = @file_get_contents(urldecode($file->url))) { // try the url decoded
                return null;
            }
        }

        // Uploading file
        $uploadedFile = wp_upload_bits($fileName, null, $fileStream);
        if ($uploadedFile['error']) {
            return null;
        }

        // Creating attachment
        $attachment = [
            'post_mime_type' => mime_content_type($uploadedFile['file']),
            'post_parent' => $postId,
            'post_title' => $file->title ?? '',
            'post_content' => $file->description ?? '',
            'post_excerpt' => $file->caption ?? '',
            'post_status' => 'inherit',
            'meta_input' => [
                static::POST_META_CONTENTHUB_ID => $file->id,
                static::POST_META_COPYRIGHT => $file->copyright ?? '',
                '_wp_attachment_image_alt' => static::getAlt($file),
            ]
        ];
        $attachmentId = wp_insert_attachment($attachment, $uploadedFile['file'], $postId);
        if (is_wp_error($attachmentId)) {
            return null;
        }

        // Attachment meta data
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        $attachmentData = wp_generate_attachment_metadata($attachmentId, $uploadedFile['file']);
        if (!wp_update_attachment_metadata($attachmentId, $attachmentData)) {
            return null;
        }

        if (function_exists('pll_set_post_language')) {
            pll_set_post_language($attachmentId, pll_get_post_language($postId));
        }

        return $attachmentId;
    }

    private static function update_attachment($attachmentId, $file)
    {
        update_post_meta($attachmentId, '_wp_attachment_image_alt', static::getAlt($file));
        update_post_meta($attachmentId, static::POST_META_COPYRIGHT, $file->copyright ?? '');
        global $wpdb;
        $wpdb->update('wp_posts', [
            'post_title' => $file->title ?? $file->caption ?? '',
            'post_content' => '',
            'post_excerpt' => $file->caption ?? '',
        ],
            [
                'ID' => $attachmentId
            ]);
    }

    private static function set_s3_object_visibility($bucket, $key, $acl)
    {
        global $as3cf;

        $s3Client = $as3cf->get_s3client();

        $s3Client->putObjectAcl(array(
            'ACL' => $acl,
            'Bucket' => $bucket,
            'Key' => $key
        ));
    }

    private static function sanizite_file_name($rawFileName, $url)
    {
        $sanitizedFileName = sanitize_file_name($rawFileName);
        $query = parse_query(parse_url($url, PHP_URL_QUERY));
        if (isset($query['fm']) && $fileNameWithoutExtension = pathinfo($sanitizedFileName, PATHINFO_FILENAME)) {
            // Append correct file extension from imgix format query param
            return sprintf('%s.%s', $fileNameWithoutExtension, $query['fm']);
        }
        // Fallback to default WP file sanitation
        return $sanitizedFileName;
    }

    private static function getAlt($file)
    {
        return collect(['altText', 'alt_text', 'title', 'caption'])->reduce(function ($out, $atr) use ($file) {
            if (isset($file->{$atr}) && ! empty($file->{$atr})) {
                $out = $file->{$atr};
            }
            return $out;
        }, '');
    }
}
