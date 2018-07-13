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
        add_action('save_post', [$this, 'videoTeaserImage']);
    }

    /**
     * @param $postId
     */
    public function videoTeaserImage($postId)
    {
        if (wp_is_post_revision($postId) || !have_rows('composite_content', $postId)) {
            return;
        }

        $videoWithTeaser = collect(get_field('composite_content'))->first(function ($content) {
            return (isset($content['video_teaser_image']) && $content['video_teaser_image']) && $content['acf_fc_layout'] === 'video';
        });

        if ($videoWithTeaser) {
            $embed = $videoWithTeaser['embed_url'];
            $teaserImagefile = VideoHelper::getLeadImageFile($embed, 'https://bonnier-publications-danmark.23video.com');

            if (empty($embed) ||
                !$teaserImagefile->url ||
                !$attachmentId = WpAttachment::upload_attachment($postId, $teaserImagefile)
            ) {
                return;
            }

            $videoTeaserImageUrl = get_post_meta($postId, 'video_teaser_image_origin_url', true);
            $currentTeaserImage = get_field('teaser_image');

            //Update when it's not saved yet or if video teaser image is changed or the teaser image is not set
            if (empty($videoTeaserImageUrl) ||
                $videoTeaserImageUrl !== $teaserImagefile->url ||
                !$currentTeaserImage
            ) {
                update_field('teaser_image', $attachmentId);
                update_post_meta($postId, 'video_teaser_image_origin_url', $teaserImagefile->url);
            }
        }
    }
}
