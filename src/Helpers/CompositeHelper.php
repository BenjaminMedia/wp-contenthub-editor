<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\VideoHelper;

class CompositeHelper
{
    /**
     * Composite constructor.
     */
    public function __construct()
    {
        add_action('save_post', [$this ,'videoLeadImage']);
    }

    public function videoLeadImage($postId)
    {
        if (wp_is_post_revision($postId) || !have_rows('composite_content', $postId)) {
            return;
        }

        while (have_rows('composite_content', $postId)) {
            //this function will increment have_rows after every loop
            $widget = the_row();

            $leadImage = boolval(get_sub_field('lead_image'));

            if (!$leadImage || $widget['acf_fc_layout'] !== 'video') {
                continue;
            }

            $embed = get_sub_field('embed_url');
            $leadImagefile = VideoHelper::getLeadImageFile($embed, 'https://bonnier-publications-danmark.23video.com');

            if (!$leadImagefile->url || empty($embed)) {
                continue;
            }

            try {
                $attachmentId = WpAttachment::upload_attachment($postId, $leadImagefile);
                $postLeadImage = get_post_meta($postId, 'video_lead_image_origin_url');

                //Update when it's not saved yet or if lead image is changed.
                if ($attachmentId && ((empty($postLeadImage)) || $postLeadImage[0] !== $leadImagefile->url)) {
                    update_post_meta($postId, 'video_lead_image', $attachmentId);
                    update_post_meta($postId, 'video_lead_image_origin_url', $leadImagefile->url);
                }
            } catch (\Exception $e) {
                echo 'Error uploading video thumbnail! '.$e->getMessage();
            }
        }
    }

}
