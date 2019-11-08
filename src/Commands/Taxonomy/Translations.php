<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy;

use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\CategoryRepository;
use WP_CLI;

/**
 * Class Tags
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class Translations extends BaseTaxonomyImporter
{
    const CMD_NAMESPACE = 'translations';

    protected $taxonomiesToSync = [
        'post_tag' => 'tags'
    ];

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE . ' ' . static::CMD_NAMESPACE, __CLASS__);
    }

    private function getTaxonomiesToSync()
    {
        $taxonomies = $this->taxonomiesToSync;

        WpTaxonomy::get_custom_taxonomies()->each(function ($taxonomy) use (&$taxonomies) {
            $taxonomies[$taxonomy->machine_name] = $taxonomy->machine_name;
        });
        return collect($taxonomies);
    }

    public function sync()
    {
        $sourceLang = 'da';
        $taxonomiesToSync = $this->getTaxonomiesToSync();

        // Get all danish articles
        collect(get_posts(
            [
                'post_type' => 'contenthub_composite',
                'numberposts' => -1, // Get all posts
                'lang' => $sourceLang,
            ]
        ))->each(function ($sourcePost) use ($sourceLang, $taxonomiesToSync) {

            // Get translated articles (pass on source_article_id)
            collect(pll_get_post_translations($sourcePost->ID))->forget($sourceLang)->each(function ($translatedPostId, $translatedLang) use ($sourcePost, $taxonomiesToSync) {

                WP_CLI::line('sourcePost->ID: ' . $sourcePost->ID);
                WP_CLI::line('translatedPost->ID: ' . $translatedPostId);

                // Sync tags and custom taxonomies
                $taxonomiesToSync->each(function ($taxonomyACF, $taxonomyWP) use ($sourcePost, $translatedPostId, $translatedLang) {
                    self::syncTagsFromPostToPost($taxonomyWP, $taxonomyACF, $sourcePost->ID, $translatedPostId, $translatedLang);
                });

                WP_CLI::line('');
            });
        });
    }

    private function syncTagsFromPostToPost($taxonomyWP, $taxonomyACF, $sourcePostId, $translatedPostId, $translatedLang)
    {
        $sourcePostHasTagIdsTranslated = collect(get_the_terms($sourcePostId, $taxonomyWP))->map(function ($sourceTag) use ($translatedPostId, $translatedLang) {
            if (!$sourceTag) {
                return null;
            }
            $translatedTerms = pll_get_term_translations($sourceTag->term_id);
            if (!isset($translatedTerms[$translatedLang])) {
                WP_CLI::error('No term-translation found');
            }
            return $translatedTerms[$translatedLang];
        });

        // Update ACF tags
        update_field($taxonomyACF, $sourcePostHasTagIdsTranslated->all(), $translatedPostId);
    }
}
