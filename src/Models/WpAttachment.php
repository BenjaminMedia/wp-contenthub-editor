<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use AS3CF_Error;
use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Helpers\HtmlToMarkdown;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Attachment\AttachmentFieldGroup;
use DeliciousBrains\WP_Offload_S3\Providers\AWS_Provider;
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
    const POST_META_COPYRIGHT_URL = 'attachment_copyright_url';

    public static function register()
    {
        // Add custom copyright field to image attachments
        add_filter('attachment_fields_to_edit', [__CLASS__, 'add_copyright_field_to_media_uploader'], null, 2);
        add_filter('attachment_fields_to_save', [__CLASS__, 'add_copyright_field_to_media_uploader_save'], null, 2);

        // Make attachments private
        add_filter('wp_update_attachment_metadata', [__CLASS__, 'wp_update_attachment_metadata'], 1000, 2);
        add_action('init', function () {
            static::register_acf_fields();
        });
        add_action('wpos3_post_upload_attachment', [__CLASS__, 'action_wpos3_post_upload_attachment'], 1000, 2);
        //add_filter('as3cf_update_attached_file', [__CLASS__, 'action_wpos3_post_upload_attachment'], 10, 3);
    }

    /*public static function action_wpos3_post_upload_attachment($file, $attachment_id, $s3object) {
        var_dump([$file, $attachment_id, $s3object]);
        var_dump('as3cf_update_attached_file');


        $url = get_post($attachment_id)->guid;
        $image = basename($url);
        if (is_file(wp_upload_dir()['path'] . '/' . $image)) {
            var_dump('supposed to delete media!');
            wp_delete_file(wp_upload_dir()['path'] . '/' . $image);
        }

        return $file;
    }*/

    public static function action_wpos3_post_upload_attachment($postId, $s3Object){
        $url = get_post($postId)->guid;
        $image = wp_upload_dir()['path'] . '/' . basename($url);
        $files_to_remove = apply_filters( 'as3cf_upload_attachment_local_files_to_remove', [$image], $postId, $image );

        $filesize_total = 0;

        foreach ( $files_to_remove as $index => $path ) {
            if ( ! empty( $attachment_id ) && is_int( $attachment_id ) ) {
                $bytes = filesize( $path );

                $filesize_total += ( false !== $bytes ) ? $bytes : 0;
            }

            // Individual files might still be kept local, but we're still going to count them towards total above.
            if ( false !== ( $pre = apply_filters( 'as3cf_preserve_file_from_local_removal', false, $path ) ) ) {
                continue;
            }

            if ( ! @unlink( $path ) ) {
                $message = 'Error removing local file ';

                if ( ! file_exists( $path ) ) {
                    $message = "Error removing local file. Couldn't find the file at ";
                } else if ( ! is_writable( $path ) ) {
                    $message = 'Error removing local file. Ownership or permissions are mis-configured for ';
                }

                AS3CF_Error::log( $message . $path );
            }
            else {
                AS3CF_Error::log('REMOVED FILE'. $path);
            }
        }

        // If we were able to sum up file sizes for an attachment, record it.
        if ( $filesize_total > 0 ) {
            update_post_meta( $attachment_id, 'wpos3_filesize_total', $filesize_total );
        }
    }

    public static function wp_update_attachment_metadata($data, $postId)
    {
        $postMeta = get_post_meta($postId);
        // Check that attachment meta contains s3 info so we can set the visibility of the object
        if (isset($postMeta['amazonS3_info'][0]) && $s3Info = unserialize($postMeta['amazonS3_info'][0])) {
            static::setS3ObjectVisibility($s3Info['bucket'], $s3Info['key'], 'private');
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
        $form_fields['copyright_url_field'] = [
            'label' => __('Copyright URL'),
            'input' => 'text',
            'value' => get_post_meta($post->ID, static::POST_META_COPYRIGHT_URL, true),

        ];
        return $form_fields;
    }


    /**
     * Save our new "Copyright" field
     *
     * @param object $post
     * @param object $attachment
     *
     * @return object
     */
    public static function add_copyright_field_to_media_uploader_save($post, $attachment)
    {
        if (isset($attachment['copyright_field']) && ! empty($attachment['copyright_field'])) {
            update_post_meta($post['ID'], static::POST_META_COPYRIGHT, $attachment['copyright_field']);
            update_post_meta($post['ID'], static::POST_META_COPYRIGHT_URL, $attachment['copyright_url_field']);
        } else {
            delete_post_meta($post['ID'], static::POST_META_COPYRIGHT);
            delete_post_meta($post['ID'], static::POST_META_COPYRIGHT_URL);
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
        return null;
    }

    public static function upload_attachment($postId, $file)
    {
        if (is_null($file)) {
            return null;
        }

        // If attachment already exists then update meta data and return the id
        if ($existingId = static::id_from_contenthub_id($file->id)) {
            static::updateAttachment($existingId, $file);
            return $existingId;
        }

        // Make sure that url has a scheme
        if (!isset(parse_url($file->url)['scheme'])) {
            $file->url = 'http:' . $file->url;
        }

        $rawFileName = basename($file->url);
        // Sanitize the new file name so WordPress will upload it
        $fileName = static::sanitizeFileName($rawFileName, $file->url);


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
            'post_content' => '',
            'post_excerpt' => static::getCaption($file),
            'post_status' => 'inherit',
            'meta_input' => [
                static::POST_META_CONTENTHUB_ID => $file->id,
                static::POST_META_COPYRIGHT => $file->copyright ?? null,
                static::POST_META_COPYRIGHT_URL => $file->copyright_url ?? null,
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

        if ($language = LanguageProvider::getPostLanguage($postId)) {
            LanguageProvider::setPostLanguage($attachmentId, $language);
        }

        return $attachmentId;
    }

    private static function register_acf_fields()
    {
        AttachmentFieldGroup::register();
    }

    private static function updateAttachment($attachmentId, $file)
    {
        update_post_meta($attachmentId, '_wp_attachment_image_alt', static::getAlt($file));
        update_post_meta($attachmentId, static::POST_META_COPYRIGHT, $file->copyright ?? '');
        update_post_meta($attachmentId, static::POST_META_COPYRIGHT_URL, $file->copyright_url ?? '');
        global $wpdb;
        $wpdb->update(
            'wp_posts',
            [
                'post_title' => $file->title ?? '',
                'post_content' => '',
                'post_excerpt' => static::getCaption($file),
            ],
            [
                'ID' => $attachmentId
            ]
        );
    }

    private static function setS3ObjectVisibility($bucket, $key, $acl)
    {
        /** @var \Amazon_S3_And_CloudFront $as3cf */
        global $as3cf;

        /** @var AWS_Provider $s3Client */
        $s3Client = $as3cf->get_s3client();

        $s3Client->update_object_acl(array(
            'ACL' => $acl,
            'Bucket' => $bucket,
            'Key' => $key
        ));
    }

    private static function sanitizeFileName($rawFileName, $url)
    {
        $sanitizedFileName = sanitize_file_name($rawFileName);
        $query = parse_query(parse_url($url, PHP_URL_QUERY));
        if (isset($query['fm']) &&
            $fileWithoutExt = pathinfo($sanitizedFileName, PATHINFO_FILENAME)
        ) {
            // Append correct file extension from imgix format query param
            return sprintf('%s.%s', $fileWithoutExt, $query['fm']);
        }
        // Fallback to default WP file sanitation
        return $sanitizedFileName;
    }

    private static function getAlt($file)
    {
        return static::getFirstMacthingAttribute($file, ['altText', 'alt_text', 'title', 'caption'], '');
    }

    /**
     * @param $file
     *
     * @return mixed|null|string
     */
    private static function getCaption($file)
    {
        $caption = static::getFirstMacthingAttribute($file, ['description', 'caption'], '');
        if (! empty($caption)) {
            $caption = HtmlToMarkdown::parseHtml($caption);
        }
        return $caption;
    }

    private static function getFirstMacthingAttribute($file, array $attributes, $defaultValue = null)
    {
        return collect($attributes)->reduce(function ($out, $atr) use ($file) {
            if ($data = data_get($file, $atr) ?: null) {
                $out = $data;
            }
            return $out;
        }, $defaultValue);
    }
}
