<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\WP\Cache\Models\Post as BonnierCachePost;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Helpers\HtmlToMarkdown;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\ContentHub\Editor\Repositories\Scaphold\CompositeRepository;
use Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum\ContentRepository;
use Bonnier\WP\Cxense\Models\Post as CxensePost;
use Bonnier\WP\Redirect\Model\Post;
use Illuminate\Support\Collection;
use WP_CLI;
use WP_Post;

/**
 * Class AdvancedCustomFields
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class WaContent extends BaseCmd
{
    const CMD_NAMESPACE = 'wa content';

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE . ' ' . static::CMD_NAMESPACE, __CLASS__);

    }

    /**
     * Imports composites from Scaphold
     *
     * ## OPTIONS
     *
     *
     * [--id=<id>]
     * : The id of a single composite to import.
     *
     * [--source=<source_code>]
     * : The the source code to fetch content from
     *
     * ## EXAMPLES
     * wp contenthub editor wa content import <domain>
     *
     * @param $args
     * @param $assocArgs
     */
    public function import($args, $assocArgs)
    {
        $this->disableHooks(); // Disable various hooks and filters during import

        if($id = $assocArgs['id'] ?? null) {
            $repository = new ContentRepository();
            $this->import_composite($repository->find_by_id($id));
        }
    }

    private function import_composite($waContent)
    {
        WP_CLI::line('Beginning import of: ' . $waContent->widget_content->title  . ' id: ' . $waContent->id);

        $postId = $this->create_post($waContent);
        $compositeContents = $this->format_composite_contents($waContent);

        $this->handle_translation($postId, $waContent);
        $this->set_meta($postId, $waContent);
        $this->save_teasers($postId, $waContent);
        $this->delete_orphaned_files($postId, $compositeContents);
        $this->save_composite_contents($postId, $compositeContents);
        $this->save_categories($postId, $waContent);
        //$this->save_tags($postId, $compositeContents);
        //$this->handle_locked_content($postId, $waContent);

        WP_CLI::success('imported: ' . $waContent->widget_content->title  . ' id: ' . $waContent->id);
    }

    private function create_post($waContent)
    {
        $existingId = WpComposite::id_from_white_album_id($waContent->id);

        // Tell Polylang the language of the post to allow multiple posts with the same slug in different languages
        $_POST['term_lang_choice'] = $waContent->translation->locale;

        $metaTitle = $waContent->widget_content->meta_title !== $waContent->widget_content->title ?
            $waContent->widget_content->meta_title : null;
        $metaDescription = $waContent->widget_content->meta_description !== $waContent->widget_content->description ?
            $waContent->widget_content->meta_description : null;

        return wp_insert_post([
            'ID' => $existingId,
            'post_title' => $waContent->widget_content->title,
            'post_name' => $waContent->slug,
            'post_status' => $waContent->widget_content->live ? 'publish' : 'draft',
            'post_type' => WpComposite::POST_TYPE,
            'post_date' => $waContent->widget_content->publish_at,
            'post_modified' => $waContent->widget_content->publish_at,
            'meta_input' => [
                WpComposite::POST_META_WHITE_ALBUM_ID => $waContent->id,
                WpComposite::POST_META_TITLE => $metaTitle,
                WpComposite::POST_META_DESCRIPTION => $metaDescription,
                WpComposite::POST_CANONICAL_URL => $waContent->widget_content->canonical_link,
            ],
        ]);

    }

    private function handle_translation($postId, $waContent)
    {
        pll_set_post_language($postId, $waContent->translation->locale); // Set post language
    }

    private function set_meta($postId, $waContent)
    {
        update_field('kind', 'Article', $postId); // Todo: set proper kind
        update_field('description', trim($waContent->widget_content->description), $postId);

        if ($waContent->magazine_year && $waContent->number) {
            update_field('magazine_year', $waContent->magazine_year, $postId);
            update_field('magazine_issue', $waContent->magazine_number, $postId);
        }

        // Todo: set advertorial
        //update_field('commercial', isset($composite->advertorial_type), $postId);
        //update_field('commercial_type', $composite->advertorial_type ?? null, $postId);
    }

    private function format_composite_contents($waContent)
    {
        return collect($waContent->body->widget_groups)->pluck('widgets')->flatten(1)->map(function ($waWidget) {
            return collect([
                'type' => collect([ // Map the type
                    'Widgets::Text'         => 'text_item',
                    'Widgets::Image'        => 'image',
                    'Widgets::InsertedCode' => 'inserted_code',
                    'Widgets::InfoBox' => 'info_box',
                ])->get($waWidget->type, null),
            ])->merge($waWidget->properties) // merge properties
                ->merge($waWidget->image ?? null); // merge image
        })->prepend($waContent->widget_content->lead_image ? // prepend lead image
            collect([
                'type'       => 'image',
                'lead_image' => true
            ])->merge($waContent->widget_content->lead_image)
            : null
        )->toObject();
    }

    private function save_composite_contents($postId, $compositeContents)
    {
        $content = $compositeContents->map(function ($compositeContent) use ($postId) {
            if ($compositeContent->type === 'text_item') {
                return [
                    'body' => HtmlToMarkdown::parseHtml($compositeContent->text),
                    'locked_content' => false,
                    'acf_fc_layout' => $compositeContent->type
                ];
            }
            if ($compositeContent->type === 'image') {
                return [
                    'lead_image' => $compositeContent->lead_image ?? false,
                    'file' => WpAttachment::upload_attachment($postId, $compositeContent),
                    'locked_content' => false,
                    'acf_fc_layout' => $compositeContent->type
                ];
            }
            if ($compositeContent->type === 'file') { // Todo implement file if necessary
                /*return [
                    'file' => WpAttachment::upload_attachment($postId, $compositeContent->content),
                    'images' => collect($compositeContent->content->images->edges)->map(function ($image) use ($postId) {
                        return [
                            'file' => WpAttachment::upload_attachment($postId, $image->node),
                        ];
                    }),
                    'locked_content' => $compositeContent->locked,
                    'acf_fc_layout' => $compositeContent->type
                ];*/
            }
            if ($compositeContent->type === 'inserted_code') {
                return [
                    'code' => $compositeContent->code,
                    'locked_content' => false,
                    'acf_fc_layout' => $compositeContent->type
                ];
            }
        })->rejectNullValues();

        update_field('composite_content', $content->toArray(), $postId);
    }

    private function save_tags($postId, $compositeContents)
    {
        $compositeContents->map(function ($compositeContent) {
            if ($compositeContent->type === 'tag' && $existingTermId = WpTerm::id_from_contenthub_id($compositeContent->content->id)) {
                if(isset($compositeContent->content->vocabulary->id) && $existingTaxonomy = WpTaxonomy::get_taxonomy($compositeContent->content->vocabulary->id)) {
                    return [$existingTaxonomy => $existingTermId];
                }
                return ['tags' => $existingTermId];
            }
            return null;
        })->rejectNullValues()->toAssocCombine()->each(function (Collection $tagIds, $taxonomy) use($postId){
            update_field($taxonomy, $tagIds->toArray(), $postId);
        });
    }

    private function save_teasers($postId, $waContent)
    {
        update_field('teaser_title', $waContent->widget_content->teaser_title, $postId);
        update_field('teaser_description', $waContent->widget_content->teaser_description, $postId);

        // Todo implement teaser image
        //update_field('teaser_image', WpAttachment::upload_attachment($postId, $teaser->image), $postId);


        /* Todo: implement facebook teaser if needed
        update_post_meta($postId, WpComposite::POST_FACEBOOK_TITLE, $teaser->title);
        update_post_meta($postId, WpComposite::POST_FACEBOOK_DESCRIPTION, $teaser->description);
        if ($teaser->image) {
            $imageId = WpAttachment::upload_attachment($postId, $teaser->image);
            update_post_meta($postId, WpComposite::POST_FACEBOOK_IMAGE, wp_get_attachment_image_url($imageId));
        }
        */

    }


    private function handle_locked_content($postId, $composite)
    {
        $accessRules = collect($composite->accessRules->edges)->pluck('node');
        if (!$accessRules->isEmpty()) {
            update_field('locked_content',
                $accessRules->first(function ($rule) {
                    return $rule->domain === 'All' && $rule->kind === 'Deny';
                }) ? true : false,
                $postId
            );
            update_field('required_user_role',
                $accessRules->first(function ($rule) {
                    return in_array($rule->domain, ['Subscriber', 'RegUser']) && $rule->kind === 'Allow';
                })->domain,
                $postId
            );
        }
    }

    private function save_categories($postId, $composite)
    {
        $contentHubId = base64_encode(sprintf('categories-wa-%s', $composite->widget_content->category_id));
        if ($existingTermId = WpTerm::id_from_contenthub_id($contentHubId)) {
            update_field('category', $existingTermId, $postId);
        }
    }

    private function remove_if_orphaned(WP_Post $post)
    {
        $compositeId = get_post_meta($post->ID, WpComposite::POST_META_CONTENTHUB_ID, true);
        if($compositeId && !CompositeRepository::find_by_id($compositeId)) {
            // Delete attachments on composite
            collect(get_field('composite_content', $post->ID) ?? [])->each(function($content){
                if($content['acf_fc_layout'] === 'file') {
                    wp_delete_attachment($content['file']['ID'], true);
                    collect($content['images'])->each(function($image){
                        wp_delete_attachment($image['file']['ID'], true);
                    });
                }
                if($content['acf_fc_layout'] === 'image') {
                    wp_delete_attachment($content['file']['ID'], true);
                }
            });
            // Delete composite
            wp_delete_post($post->ID, true);
            WP_CLI::line(sprintf('Removed post: %s, with id:%s and composite id:%s', $post->post_title, $post->ID, $compositeId));
        }
    }

    private function get_post_name($composite)
    {
        if(preg_match('/[^\/]*$/', $composite->metaInformation->originalUrl, $matches) && !empty($matches)) {
            return $matches[0]; // return the part of the slug after the last /
        }
        global $locale; // No original url is available so we generate post name from the title instead
        $locale = $composite->locale; // We modify the global $locale so sanitize_title_with_dashes() works correctly
        return sanitize_title($composite->title);
    }

    /**
     * @param                                $postId
     * @param \Illuminate\Support\Collection $compositeContents
     *
     * Deletes attachments that would have otherwise become orphaned after import
     */
    private function delete_orphaned_files($postId, Collection $compositeContents)
    {
        $currentFileIds = collect(get_field('composite_content', $postId))->map(function ($content) use ($postId) {
            if ($content['acf_fc_layout'] === 'image') {
                return WpAttachment::contenthub_id($content['file'] ?? null);
            }
            if ($content['acf_fc_layout'] === 'file') {
                return [
                    'file'   => WpAttachment::contenthub_id($content['file'] ?? null),
                    'images' => collect($content['images'])->map(function ($image) {
                        return WpAttachment::contenthub_id($image['file'] ?? null);
                    })
                ];
            }
        })->flatten()
            ->push(WpAttachment::contenthub_id(get_field('teaser_image', $postId)))
            ->rejectNullValues();

        $newFileIds = $compositeContents->map(function ($compositeContent) {
            if ($compositeContent->type === 'image') {
                return $compositeContent->id;
            }
            /*if ($compositeContent->type === 'file') {
                return [
                    'file'   => $compositeContent->id,
                    'images' => collect($compositeContent->content->images->edges)->map(function ($image) {
                        return $image->node->id;
                    })
                ];
            }*/
        })->flatten()->rejectNullValues();

        $currentFileIds->diff($newFileIds)->each(function ($orphanedFileId) { // Compare current file ids to new file ids
            // We delete any of the current files that would be come orphaned
            WpAttachment::delete_by_contenthub_id($orphanedFileId);
        });
    }

    private function disableHooks()
    {
        // Disable generation of image sizes on import to speed up the precess
        add_filter('intermediate_image_sizes_advanced', function ($sizes) {
            return [];
        });

        // Disable on save hook to prevent call to content hub, Cxense and Bonnier Cache Manager
        remove_action( 'save_post', [WpComposite::class, 'on_save'], 10, 2 );
        remove_action( 'save_post', [BonnierCachePost::class, 'update_post'], 10, 1 );
        remove_action( 'transition_post_status', [CxensePost::class, 'post_status_changed'], 10, 3);
        remove_action( 'save_post', [Post::class, 'save'], 5, 2);
    }

}
