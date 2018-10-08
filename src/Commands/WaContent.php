<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\Cache\Models\Post as BonnierCachePost;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Helpers\HtmlToMarkdown;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
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

    private $repository = null;

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE . ' ' . static::CMD_NAMESPACE, __CLASS__);
    }

    /**
     * Imports composites from Scaphold
     *
     * ## OPTIONS
     *
     * [--id=<id>]
     * : The id of a single composite to import.
     *
     * [--type=<type>]
     * : The type of the single composite used together with id, can be article|gallery.
     *
     * [--locale=<locale>]
     * : The locale to fetch from used in conjunction with --id
     *
     * [--source=<source_code>]
     * : The the source code to fetch content from
     *
     * ## EXAMPLES
     * wp contenthub editor wa content import
     *
     * @param $args
     * @param $assocArgs
     */
    public function import($args, $assocArgs)
    {
        $this->disableHooks(); // Disable various hooks and filters during import
        $this->fixNonceErrors();

        $this->repository = new ContentRepository($assocArgs['locale'] ?? null);
        if ($contentId = $assocArgs['id'] ?? null) {
            $resource = collect([
                'article' => ContentRepository::ARTICLE_RESOURCE,
                'gallery' => ContentRepository::GALLERY_RESOURCE,
            ])->get($assocArgs['type'] ?? 'article');
            $this->importComposite($this->repository->find_by_id($contentId, $resource));
        } else {
            $this->repository->map_all(function ($waContent) {
                $this->importComposite($waContent);
            });
        }
    }

    private function importComposite($waContent)
    {
        if (!$waContent) {
            return;
        }

        WP_CLI::line(sprintf(
            'Beginning import of: %s id: %s',
            $waContent->widget_content->title,
            $waContent->widget_content->id
        ));

        $postId = $this->createPost($waContent);
        $compositeContents = $this->formatCompositeContents($waContent);

        $this->handleTranslation($postId, $waContent);
        $this->setMeta($postId, $waContent);
        $this->deleteOrphanedFiles($postId, $compositeContents);
        $this->saveCompositeContents($postId, $compositeContents);
        $this->saveTeasers($postId, $waContent);
        $this->saveCategories($postId, $waContent);
        $this->saveTags($postId, $waContent);

        WP_CLI::success('imported: ' . $waContent->widget_content->title  . ' id: ' . $postId);
    }

    private function createPost($waContent)
    {
        $existingId = WpComposite::id_from_white_album_id($waContent->widget_content->id);

        if ($existingId && getenv('WP_ENV') === 'production') {
            usleep(500000); // Delay for half a second when updating on prod to avoid ddos'ing WA
        }

        // Tell Polylang the language of the post to allow multiple posts with the same slug in different languages
        $_POST['term_lang_choice'] = $waContent->translation->locale ?? 'da';

        return wp_insert_post([
            'ID' => $existingId,
            'post_title' => $waContent->widget_content->title,
            'post_name' => $waContent->slug,
            'post_status' => $waContent->widget_content->live ? 'publish' : 'draft',
            'post_type' => WpComposite::POST_TYPE,
            'post_date' => $waContent->widget_content->publish_at,
            'post_modified' => $waContent->widget_content->publish_at,
            'post_author' => $this->getAuthor($waContent),
            'meta_input' => [
                WpComposite::POST_META_WHITE_ALBUM_ID => $waContent->widget_content->id,
            ],
        ]);
    }

    private function handleTranslation($postId, $waContent)
    {
        LanguageProvider::setPostLanguage($postId, $waContent->translation->locale ?? 'da');

        //if this is not the master translation, just return
        if (!isset($waContent->translation)) {
            return;
        }

        $translationPostIds = collect($waContent->translation->translation_ids)->map(
            function ($translationId, $locale) use ($waContent) {
                return $waContent->translation->is_master ? // Only import the translations when import master locale
                    $this->importTranslation($translationId, $locale) :
                    WpComposite::id_from_white_album_id($translationId);
            }
        )->merge([
            $waContent->translation->locale ?? 'da'  => $postId, // always push current locale
        ])->rejectNullValues();
        LanguageProvider::savePostTranslations($translationPostIds->toArray());
        if (!$translationPostIds->isEmpty()) {
            WP_CLI::success(
                sprintf(
                    'attached the following translations %s to: %s',
                    $translationPostIds,
                    $waContent->widget_content->title
                )
            );
        }
    }

    private function findMatchingTranslation($whiteAlbumId, $locale)
    {
        $repository = new ContentRepository($locale);
        return $repository->find_by_id($whiteAlbumId, ContentRepository::ARTICLE_RESOURCE) ?:
            $repository->find_by_id($whiteAlbumId, ContentRepository::GALLERY_RESOURCE);
    }

    private function importTranslation($translationId, $locale)
    {
        if ($translation = $this->findMatchingTranslation($translationId, $locale)) {
            WP_CLI::line(sprintf('found translation: %s in locale: %s', $translation->widget_content->title, $locale));
            $this->importComposite($translation);
            return WpComposite::id_from_white_album_id($translationId);
        }
        WP_CLI::warning(sprintf('no translations for %s found.', $translationId));
        return null;
    }

    private function setMeta($postId, $waContent)
    {
        update_field('kind', 'Article', $postId); // Todo: set proper kind
        update_field('description', trim($waContent->widget_content->description), $postId);

        update_field('magazine_year', $waContent->magazine_year ?? null, $postId);
        update_field('magazine_issue', $waContent->magazine_number ?? null, $postId);

        update_field('canonical_url', $waContent->widget_content->canonical_link);

        if ($waContent->widget_content->advertorial_label) {
            update_field('commercial', true, $postId);
            $type = join('', array_map(function ($part) {
                return ucfirst($part);
            }, explode(' ', $waContent->widget_content->advertorial_label)));
            update_field('commercial_type', $type ?? null, $postId);
        }
    }

    private function formatCompositeContents($waContent)
    {
        if (isset($waContent->body->widget_groups)) {
            return collect($waContent->body->widget_groups)
                ->pluck('widgets')
                ->flatten(1)
                ->map(function ($waWidget) {
                    return collect([
                        'type' => collect([ // Map the type
                            'Widgets::Text'         => 'text_item',
                            'Widgets::Image'        => 'image',
                            'Widgets::InsertedCode' => 'inserted_code',
                            'Widgets::InfoBox' => 'info_box',
                            'Widgets::Video' => 'video',
                        ])
                            ->get($waWidget->type, null),
                    ])
                        ->merge($waWidget->properties) // merge properties
                        ->merge($waWidget->image ?? null); // merge image
                })
                ->prepend(
                    $waContent->widget_content->lead_image ? // prepend lead image
                        collect([
                            'type'       => 'image',
                            'lead_image' => true
                        ])
                            ->merge($waContent->widget_content->lead_image)
                        : null
                )->itemsToObject()->map(function ($content) {
                    return $this->fixFaultyImageFormats($content);
                });
        }
        if (isset($waContent->gallery_images)) {
            // Galleries are converted to a combination of text items and image widgets
            return collect($waContent->gallery_images)
                ->pluck('image')
                ->map(function ($waImage) {
                    $description = $waImage->description;
                    $waImage->type = 'image';
                    unset($waImage->description);
                    return [
                        $this->fixFaultyImageFormats($waImage),
                        [
                            'type' => 'text_item',
                            'text' => $description
                        ]
                    ];
                })
                ->flatten(1)
                ->itemsToObject();
        }
        return null;
    }

    private function saveCompositeContents($postId, Collection $compositeContents)
    {
        $content = $compositeContents
            ->map(function ($compositeContent) use ($postId) {
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
                if ($compositeContent->type === 'file') {
                    // Todo implement file if necessary
                    /*return [
                        'file' => WpAttachment::upload_attachment($postId, $compositeContent->content),
                        'images' => collect($compositeContent->content->images->edges)
                            ->map(function ($image) use ($postId) {
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
                if ($compositeContent->type === 'gallery') {
                    return [
                        'images' => $compositeContent->images->map(function ($waImage) use ($postId) {
                            $description = HtmlToMarkdown::parseHtml(
                                sprintf('<h3>%s</h3> %s', $waImage->title, $waImage->description)
                            );
                            // Unset description from image as it will be imported to gallery
                            $waImage->description = null;
                            return [
                                'image' => WpAttachment::upload_attachment($postId, $waImage),
                                // Prepend title to description as we do not support titles per image
                                'description' => $description
                            ];
                        }),
                        'display_hint' => $compositeContent->display_hint,
                        'locked_content' => false,
                        'acf_fc_layout' => $compositeContent->type
                    ];
                }
                if ($compositeContent->type === 'video') {
                    return [
                        'embed_url' => $this->getVideoEmbed($compositeContent->video_site, $compositeContent->video_id),
                        'locked_content' => false,
                        'acf_fc_layout' => $compositeContent->type
                    ];
                }
                return null;
            })->rejectNullValues();

        update_field('composite_content', $content->toArray(), $postId);
    }

    private function saveTags($postId, $waContent)
    {
        $tagIds = collect($waContent->widget_content->tags)->map(function ($waTag) {
            return WpTerm::id_from_whitealbum_id($waTag->id) ?: null;
        })->rejectNullValues();
        update_field('tags', $tagIds->toArray(), $postId);
    }

    private function saveTeasers($postId, $waContent)
    {
        $teaserTitle = $waContent->widget_content->teaser_title ?: $waContent->widget_content->title;
        $teaserDescription = $waContent->widget_content->teaser_description ?: $waContent->widget_content->description;
        $teaserImage = $waContent->widget_content->teaser_image ?: $waContent->widget_content->lead_image;

        // General teaser
        update_field(WpComposite::POST_TEASER_TITLE, $teaserTitle, $postId);
        update_field(WpComposite::POST_TEASER_DESCRIPTION, $teaserDescription, $postId);
        update_field(WpComposite::POST_TEASER_IMAGE, WpAttachment::upload_attachment($postId, $teaserImage), $postId);

        // Facebook teaser
        if ($waContent->widget_content->teaser_facebook_only) {
            update_field(WpComposite::POST_FACEBOOK_TITLE, $teaserTitle, $postId);
            update_field(WpComposite::POST_FACEBOOK_DESCRIPTION, $teaserDescription, $postId);
            update_field(WpComposite::POST_FACEBOOK_IMAGE, WpAttachment::upload_attachment(
                $postId,
                $teaserImage
            ), $postId);
        }

        update_field(WpComposite::POST_META_TITLE, $waContent->widget_content->meta_title, $postId);
        update_field(WpComposite::POST_META_DESCRIPTION, $waContent->widget_content->meta_description, $postId);
    }

    private function saveCategories($postId, $composite)
    {
        if ($existingTermId = WpTerm::id_from_whitealbum_id($composite->widget_content->category_id)) {
            update_field('category', $existingTermId, $postId);
        }
    }

    private function remove_if_orphaned(WP_Post $post)
    {
        $compositeId = get_post_meta($post->ID, WpComposite::POST_META_CONTENTHUB_ID, true);
        if ($compositeId && !CompositeRepository::findById($compositeId)) {
            // Delete attachments on composite
            collect(get_field('composite_content', $post->ID) ?? [])->each(function ($content) {
                if ($content['acf_fc_layout'] === 'file') {
                    wp_delete_attachment($content['file']['ID'], true);
                    collect($content['images'])->each(function ($image) {
                        wp_delete_attachment($image['file']['ID'], true);
                    });
                }
                if ($content['acf_fc_layout'] === 'image') {
                    wp_delete_attachment($content['file']['ID'], true);
                }
            });
            // Delete composite
            wp_delete_post($post->ID, true);
            WP_CLI::line(sprintf(
                'Removed post: %s, with id:%s and composite id:%s',
                $post->post_title,
                $post->ID,
                $compositeId
            ));
        }
    }

    /**
     * @param                                $postId
     * @param \Illuminate\Support\Collection $compositeContents
     *
     * Deletes attachments that would have otherwise become orphaned after import
     */
    private function deleteOrphanedFiles($postId, Collection $compositeContents)
    {
        $currentFileIds = collect(get_field('composite_content', $postId))
            ->map(function ($content) use ($postId) {
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
                if ($content['acf_fc_layout'] === 'gallery') {
                    return collect($content['images'])->map(function ($galleryItem) {
                        return WpAttachment::contenthub_id($galleryItem['image'] ?? null);
                    });
                }
                return null;
            })->flatten()
            ->push(WpAttachment::contenthub_id(get_field('teaser_image', $postId)))
            ->rejectNullValues();



        $newFileIds = $compositeContents
            ->map(function ($compositeContent) {
                if ($compositeContent->type === 'image') {
                    return $compositeContent->id;
                }
                if ($compositeContent->type === 'gallery') {
                    return $compositeContent->images->map(function ($galleryItem) {
                        return $galleryItem->id;
                    });
                }
                return null;
            })
            ->flatten()
            ->rejectNullValues();

        // Compare current file ids to new file ids
        $currentFileIds->diff($newFileIds)->each(function ($orphanedFileId) {
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
        remove_action('save_post', [WpComposite::class, 'on_save'], 10);
        remove_action('save_post', [BonnierCachePost::class, 'update_post'], 10);
        remove_action('transition_post_status', [CxensePost::class, 'post_status_changed'], 10);
        remove_action('save_post', [Post::class, 'save'], 5);
    }

    private function getAuthor($waContent)
    {
        global $wpdb;

        $contentHubId = base64_encode(sprintf('wa-author-%s', md5(strtolower(trim($waContent->author)))));

        $existingId = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT user_id FROM wp_usermeta WHERE meta_key=%s AND meta_value=%s",
                'contenthub_id',
                $contentHubId
            )
        );

        $userId = wp_insert_user([
            'ID' => $existingId ?: null,
            'user_login' => sanitize_user($waContent->author),
            'display_name' => $waContent->author,
            'user_pass' => md5(rand(1, 32)),
        ]);

        if (is_wp_error($userId)) {
            return null;
        }

        update_user_meta($userId, 'contenthub_id', $contentHubId);

        return $userId;
    }

    private function fixFaultyImageFormats($content)
    {
        if ($content->type === 'image'
            && ($extension = pathinfo($content->url, PATHINFO_EXTENSION))
            && in_array($extension, ['psd'])
        ) {
            // If we find the image extension to be in the blacklist then we tell imgix to return as png format
            $content->url .= '?fm=png';
        }
        return $content;
    }

    private function getVideoEmbed($provider, $videoId)
    {
        $vendor = collect([
            'youtube' => 'https://www.youtube.com/embed/',
            'vimeo' => 'https://player.vimeo.com/video/',
            'vimeo' => '//bonnier-publications-danmark.23video.com/v.ihtml/player.html?source=share&photo%5fid=',
        ])->get($provider);
        return $vendor ? $vendor . $videoId : null;
    }

    private function fixNonceErrors()
    {
        wp_set_current_user(1); // Make sure we act as admin to allow upload of all file types
        add_action('check_admin_referer', function ($action, &$result) {
            $result = 1; // Force valid nonce
        }, 10, 2);
    }
}
