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

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE  . ' ' . static::CMD_NAMESPACE, __CLASS__);
    }

    private static function isUpdateNeeded($term, $taxonomy, $post_id) {
        return !has_term($term->term_id, $taxonomy->machine_name, $post_id) ||
               !isset(get_fields($post_id)[$taxonomy->machine_name]) ||
               get_fields($post_id)[$taxonomy->machine_name]->term_id != $term->term_id;
    }

    public function sync() {
        // Get taxonomies (editorial_type / difficulty)
        WpTaxonomy::get_custom_taxonomies()->each(function ($taxonomy) {

            // Get danish terms in the current taxonomy (anmeldelse ...)
            collect(get_terms(['taxonomy' => $taxonomy->machine_name, 'lang' => 'da', 'hide_empty' => false]))
                ->each(function ($term) use ($taxonomy) {

                $translatedTerms = pll_get_term_translations($term->term_id);

                // Get composites with the current term
                collect(get_posts(
                    [
                        'post_type' => 'contenthub_composite',
                        'numberposts' => -1, // Get all posts
                        'tax_query' => [
                            [
                                'taxonomy' => $taxonomy->machine_name,
                                'field' => 'term_id',
                                'terms' => $term->term_id
                            ]
                        ]
                    ]
                ))->each(function ($post) use ($taxonomy, $translatedTerms) {

                    // Get translated composites
                    collect(pll_get_post_translations($post->ID))->forget('da')
                        ->each(function ($post_id, $lang) use ($taxonomy, $translatedTerms) {

                        $term = get_term($translatedTerms[$lang]);

                        if (self::isUpdateNeeded($term, $taxonomy, $post_id)) {
                            WP_CLI::line('Adding term - ' . $lang);
                            WP_CLI::line('term: ' . $term->term_id . ' - ' . $term->name);
                            WP_CLI::line('post: ' . $post_id . ' - ' . get_the_title($post_id));
                            WP_CLI::line('');

                            wp_set_post_terms($post_id, $term->name, $taxonomy->machine_name, true);
                            update_field($taxonomy->machine_name, $term->term_id, $post_id);

                            if (self::isUpdateNeeded($term, $taxonomy, $post_id)) {
                                WP_CLI::error('ERROR adding the term to the post');
                            }
                        }
                    });
                });
            });
        });
    }
}
