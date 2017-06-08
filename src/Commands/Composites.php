<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Helpers\SlugHelper;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Repositories\Scaphold\CompositeRepository;
use WP_CLI;

/**
 * Class AdvancedCustomFields
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class Composites extends BaseCmd
{
    const CMD_NAMESPACE = 'composites';

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE . ' ' . static::CMD_NAMESPACE, __CLASS__);
    }

    /**
     * Imports composites from Scahpold
     *
     * ## EXAMPLES
     *
     * wp contenthub editor composites import
     *
     */
    public function import()
    {
        $this->map_sites(function ($site) {
            $this->map_composites_by_brand_id($site->brand->content_hub_id, function ($composite) {
                $this->import_composite($composite);
            });
        });
    }

    private function map_composites_by_brand_id($id, callable $callable)
    {
        $compositeQuery = CompositeRepository::find_by_brand_id($id);

        while ($compositeQuery) {
            $categories = collect($compositeQuery->edges);
            $categories->pluck('node')->each(function ($compositeInfo) use ($callable) {
                $callable(CompositeRepository::find_by_id($compositeInfo->id));
            });
            if (isset($compositeQuery->pageInfo->hasNextPage) && $compositeQuery->pageInfo->hasNextPage)
                $compositeQuery = CompositeRepository::find_by_brand_id($id, $categories->last()->cursor);
            else
                $compositeQuery = null;
        }
    }

    private function import_composite($composite)
    {
        WP_CLI::line('Beginning import of: ' . $composite->title  . ' id: ' . $composite->id);

        $postId = $this->create_post($composite);
        $compositeContents = $this->format_composite_contents($composite);

        $this->handle_translation($postId, $composite);
        $this->set_meta($postId, $composite);
        $this->save_composite_contents($postId, $compositeContents);
        $this->save_tags($compositeContents);
        $this->save_teasers($composite);
        $this->set_slug($postId, $composite);
        $this->handle_locked_content($postId, $composite);
        $this->save_categories($postId, $composite);

        WP_CLI::success('imported: ' . $composite->title  . ' id: ' . $composite->id);
    }

    private function create_post($composite)
    {
        $existingId = WpComposite::id_from_contenthub_id($composite->id);

        return wp_insert_post([
            'ID' => $existingId,
            'post_title' => $composite->title,
            'post_name' => SlugHelper::create_slug($composite->title),
            'post_status' => collect([
                'Published' => 'publish',
                'Draft' => 'draft',
                'Ready' => 'pending'
            ])->get($composite->status, 'draft'),
            'post_type' => WpComposite::POST_TYPE,
            'post_date' => $composite->publishedAt ?? $composite->createdAt,
            'post_modified' => $composite->modifiedAt,
            'meta_input' => [
                WpComposite::POST_META_CONTENTHUB_ID => $composite->id,
                WpComposite::POST_META_TITLE => $composite->metaInformation->pageTitle,
                WpComposite::POST_META_DESCRIPTION => $composite->metaInformation->description,
                WpComposite::POST_CANONICAL_URL => $composite->metaInformation->canonicalUrl,
            ],
        ]);

    }

    private function handle_translation($postId, $composite)
    {
        // Todo: link translations together by calling pll_save_post_translations()
        pll_set_post_language($postId, $composite->locale);
    }

    private function set_meta($postId, $composite)
    {
        update_field('kind', $composite->kind, $postId);
        update_field('description', $composite->description, $postId);

        if ($composite->magazine) {
            $magazineYearIssue = explode('-', $composite->magazine);
            update_field('magazine_year', $magazineYearIssue[0], $postId);
            update_field('magazine_issue', $magazineYearIssue[1], $postId);
        }

        update_field('commercial', !is_null($composite->advertorial_type), $postId);
        update_field('commercial_type', $composite->advertorial_type, $postId);
    }

    private function format_composite_contents($composite)
    {
        return collect($composite->content->edges)->pluck('node')->map(function ($compositeContent) {
            return collect($compositeContent)->rejectNullValues();
        })->map(function ($compositeContent) {
            $contentType = $compositeContent->except(['id', 'position', 'locked'])->keys()->first();
            return (object)$compositeContent->only(['id', 'position', 'locked'])->merge([
                'type' => snake_case($contentType),
                'content' => $compositeContent->get($contentType)
            ])->toArray();
        });
    }

    private function save_composite_contents($postId, $compositeContents)
    {
        $content = $compositeContents->map(function ($compositeContent) use ($postId) {
            if ($compositeContent->type === 'text_item') {
                return [
                    'body' => $compositeContent->content->body,
                    'locked_content' => $compositeContent->locked,
                    'acf_fc_layout' => $compositeContent->type
                ];
            }
            if ($compositeContent->type === 'image') {
                return [
                    'lead_image' => $compositeContent->content->trait === 'Primary' ? true : false,
                    'file' => WpAttachment::upload_attachment($postId, $compositeContent->content),
                    'locked_content' => $compositeContent->locked,
                    'acf_fc_layout' => $compositeContent->type
                ];
            }
            if ($compositeContent->type === 'file') {
                return [
                    'file' => WpAttachment::upload_attachment($postId, $compositeContent->content),
                    'images' => collect($compositeContent->content->images->edges)->map(function ($image) use ($postId) {
                        return [
                            'file' => WpAttachment::upload_attachment($postId, $image->node),
                        ];
                    }),
                    'locked_content' => $compositeContent->locked,
                    'acf_fc_layout' => $compositeContent->type
                ];
            }
        })->rejectNullValues();

        update_field('composite_content', $content->toArray(), $postId);
    }

    private function save_tags($compositeContents)
    {
        $tags = collect($compositeContents->map(function ($compositeContent) {
            if ($compositeContent->type === 'tag' && $existingTermId = WpTerm::id_from_contenthub_id($compositeContent->content->id)) {
                return $existingTermId;
            }
            return null;
        }))->rejectNullValues()->toArray();
        update_field('tags', $tags, $postId);
    }

    private function save_teasers($composite)
    {
        collect($composite->teasers->edges)->pluck('node')->each(function ($teaser) use ($postId) {
            if ($teaser->kind === 'Internal') {
                update_field('teaser_title', $teaser->title, $postId);
                update_field('teaser_description', $teaser->title, $postId);
                update_field('teaser_image', WpAttachment::upload_attachment($postId, $teaser->image), $postId);
            }
            if ($teaser->kind === 'Facebook') {

                update_post_meta($postId, WpComposite::POST_FACEBOOK_TITLE, $teaser->title);
                update_post_meta($postId, WpComposite::POST_FACEBOOK_DESCRIPTION, $teaser->description);
                if ($teaser->image) {
                    $imageId = WpAttachment::upload_attachment($postId, $teaser->image);
                    update_post_meta($postId, WpComposite::POST_FACEBOOK_IMAGE, wp_get_attachment_image_url($imageId));
                }
            }
            // Todo: implement Twitter social teaser
        });
    }

    private function set_slug($postId, $composite)
    {
        if ($originalSlug = parse_url($composite->metaInformation->originalUrl)['path'] ?? null) {
            // Ensure that post has the same url as it previously had
            $currentSlug = parse_url(get_permalink($postId))['path'];
            if (rtrim($originalSlug, '/') !== rtrim($currentSlug, '/')) {
                update_post_meta($postId, WpComposite::POST_META_CUSTOM_PERMALINK, ltrim($originalSlug, '/'));
            }
        }
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
        collect($composite->categories->edges)->pluck('node')->each(function ($category) use ($postId) {
            if ($existingTermId = WpTerm::id_from_contenthub_id($category->id)) {
                update_field('category', $existingTermId, $postId);
            }
        });
    }

}
