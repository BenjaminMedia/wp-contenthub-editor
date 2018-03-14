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
        add_action('save_post', [$this ,'videoTeaserImage']);
    }

    public function videoTeaserImage($postId)
    {
        if (wp_is_post_revision($postId) || !have_rows('composite_content', $postId)) {
            return;
        }

        while (have_rows('composite_content', $postId)) {
            //this function will increment have_rows after every loop
            $widget = the_row();

            //field_5a8d7ae021e44 is the hardcoded ACF name for video teaser_image
            if (!$widget['field_5a8d7ae021e44'] || $widget['acf_fc_layout'] !== 'video') {
                continue;
            }

            $embed = get_sub_field('embed_url');
            $teaserImagefile = VideoHelper::getLeadImageFile($embed, 'https://bonnier-publications-danmark.23video.com');

            if (!$teaserImagefile->url || empty($embed)) {
                continue;
            }

            try {
                $attachmentId = WpAttachment::upload_attachment($postId, $teaserImagefile);
                $videoTeaserImageUrl = get_post_meta($postId, 'video_teaser_image_origin_url');

                //Update when it's not saved yet or if video teaser image is changed.
                if ($attachmentId && ((empty($videoTeaserImageUrl)) || $videoTeaserImageUrl[0] !== $teaserImagefile->url)) {
                    update_field('teaser_image', $attachmentId);
                    update_post_meta($postId, 'video_teaser_image_origin_url', $teaserImagefile->url);
                    break;
                }
            } catch (\Exception $e) {
                echo 'Error uploading video thumbnail! '.$e->getMessage();
            }
        }
    }

}
