<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use Illuminate\Support\Collection;
use Bonnier\WP\ContentHub\Editor\ContenthubEditor;

/**
 * Class WpTaxonomy
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models
 */
class WpTaxonomy
{
    const CUSTOM_TAXONOMIES_OPTION = 'content_hub_custom_taxonomies';

    public static function register($plugin)
    {
        add_action('init', function () {
            static::get_custom_taxonomies()->each(function ($customTaxonomy) {
                register_taxonomy($customTaxonomy->machine_name, WpComposite::POST_TYPE, [
                    'label'             => $customTaxonomy->name,
                    'show_ui'           => false,
                    'show_admin_column' => false,
                ]);
            });
        });
        static::admin_enqueue_scripts($plugin);
    }

    public static function add($externalTaxonomy)
    {
        $customTaxonomies = static::get_custom_taxonomies();
        $customTaxonomies[$externalTaxonomy->content_hub_id] = $externalTaxonomy;
        static::set_custom_taxonomies($customTaxonomies);
    }

    public static function get_taxonomy($contentHubId)
    {
        return static::get_custom_taxonomies()->get($contentHubId)->machine_name ?? null;
    }

    public static function get_custom_taxonomies()
    {
        return collect(get_option(static::CUSTOM_TAXONOMIES_OPTION, []));
    }

    public static function set_custom_taxonomies(Collection $taxonomies)
    {
        update_option(static::CUSTOM_TAXONOMIES_OPTION, $taxonomies->toArray(), true);
    }

    public static function admin_enqueue_scripts($plugin)
    {
        wp_enqueue_script(
            'acf-taxonomy-fields',
            $plugin->pluginUrl . 'js/acf/fields/taxonomy-fields.js',
            [],
            filemtime($plugin->pluginDir . 'js/acf/fields/taxonomy-fields.js')
        );
    }
}
