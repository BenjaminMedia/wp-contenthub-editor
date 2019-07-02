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
        add_action('save_post', [$this, 'setStoryParent']);
        add_filter('acf/validate_value/key=field_58e393e0128b4', [$this, 'validateArticleNotPartOfMultipleStories'], 10, 4);
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
            return (isset($content['video_teaser_image']) && $content['video_teaser_image']) &&
                $content['acf_fc_layout'] === 'video';
        });

        if ($videoWithTeaser) {
            $embed = $videoWithTeaser['embed_url'];
            $teaserImagefile = VideoHelper::getLeadImageFile(
                $embed,
                'https://bonnier-publications-danmark.23video.com'
            );

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

    public function setStoryParent($postId)
    {
        //do all this magic code because we don\'t want the wp_update_post to run infinitely.
        if (!$this->validStoryComposite($postId)) {
            return;
        }

        global $wpdb;

        // First remove all relations, so that if we remove a composite from a story,
        // so it will no longer have a relation to the story  composite.
        $wpdb->delete(
            sprintf('%spostmeta', $wpdb->prefix),
            [
                'meta_key' => 'story_parent',
                'meta_value' => $postId
            ]
        );

        // Then run through all associated_composites and add a postmeta field, defining the story parent
        collect(get_field('composite_content', $postId))->each(function ($content) use ($postId, $wpdb) {
            if ($content['acf_fc_layout'] === 'associated_composites') {
                collect(array_get($content, 'composites', []))->each(function (\WP_Post $composite) use ($postId) {
                    add_post_meta($composite->ID, 'story_parent', $postId);
                });
            }
        });
    }

    private function validStoryComposite($postId)
    {
        return current_user_can('edit_post', $postId) &&
            !wp_is_post_revision($postId) &&
            !wp_is_post_autosave($postId) &&
            have_rows('composite_content', $postId) &&
            get_field('kind', $postId) === 'Story';
    }

    public function validateArticleNotPartOfMultipleStories($valid, $articlesInStory)
    {
        if (!$articlesInStory) {
            return false;
        }
        $currentPostId = $_POST['post_ID'];

        collect($articlesInStory)->each(function ($article) use (&$valid, &$currentPostId) {
            $parentStoryIds = get_post_meta($article, 'story_parent');

            if (count($parentStoryIds) > 0 && !in_array($currentPostId, $parentStoryIds)) {
                $violatedPostId = collect($parentStoryIds)->first(function ($parentId) use (&$valid, &$currentPostId) {
                    return (int)$parentId !== (int)$currentPostId;
                });

                $sameArticleKind = get_post_meta($currentPostId, 'kind')[0] === 'Story'
                    && get_post_meta($violatedPostId, 'kind')[0] === 'Story';

                if ($violatedPostId && $sameArticleKind == true) {
                    $valid = sprintf(
                        '%s: %s <a class="post-edit-link" href="%s" target="_blank">%s</a>',
                        get_post($article)->post_title,
                        'Is used in another story',
                        get_edit_post_link($violatedPostId),
                        'Click here to edit the story where it\'s used'
                    );
                    return $valid;
                }
            }
        });

        return $valid;
    }
}
