<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Illuminate\Support\Arr;

/**
 * Class PolylangConfig
 *
 * @package \Bonnier\WP\ContentHub\Editor\Helpers
 */
class PolylangConfig
{
    public static function register()
    {
        add_action('option_polylang', [__CLASS__, 'polylang_options']);
        add_filter('wp_insert_post_data', [__CLASS__, 'copyPostAuthor']);
    }

    public static function polylang_options($defaultOptions)
    {
        return array_merge(
            $defaultOptions,
            [
                'post_types' => [
                    WpComposite::POST_TYPE, // Tell polylang to enable translation for our custom content type
                ],
                // Tell polylang to enable translation for each of our custom taxonomies
                'taxonomies' => collect(WpTaxonomy::get_custom_taxonomies())->pluck('machine_name')->toArray()
            ]
        );
    }

    public static function copyPostAuthor($post)
    {
        if (($fromId = Arr::get($_GET, 'from_post')) &&
            ($fromPost = get_post($fromId)) &&
            $fromPost instanceof \WP_Post
        ) {
            $post['post_author'] = $fromPost->post_author;
        }

        return $post;
    }
}
