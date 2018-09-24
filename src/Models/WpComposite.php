<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\AttachmentGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\MagazineFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\MetaFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TaxonomyFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TeaserFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TranslationStateFieldGroup;
use Bonnier\WP\ContentHub\Editor\Plugin;
use Bonnier\WP\ContentHub\Editor\Repositories\Scaphold\CompositeRepository;
use Bonnier\WP\ContentHub\Editor\Settings\SettingsPage;
use WP_Post;

/**
 * Class WpComposite
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models
 */
class WpComposite
{
    const POST_TYPE = 'contenthub_composite';
    const POST_TYPE_NAME = 'Content';
    const POST_TYPE_NAME_SINGULAR = 'Composite';
    const POST_SLUG = '%category%';
    const POST_PERMALINK_STRUCTURE = '/%category%/%postname%';
    const CATEGORY_BASE = '';
    const POST_META_CONTENTHUB_ID = 'contenthub_id';
    const POST_META_CUSTOM_PERMALINK = 'custom_permalink';
    const POST_META_TITLE = '_yoast_wpseo_title';
    const POST_META_DESCRIPTION = '_yoast_wpseo_metadesc';
    const POST_CANONICAL_URL = '_yoast_wpseo_canonical';
    const POST_FACEBOOK_TITLE = '_yoast_wpseo_opengraph-title';
    const POST_FACEBOOK_DESCRIPTION = '_yoast_wpseo_opengraph-description';
    const POST_FACEBOOK_IMAGE = '_yoast_wpseo_opengraph-image';
    const SLUG_CHANGE_HOOK = 'contenthub_composite_slug_change';

    /**
     * Register the composite as a custom wp post type
     */
    public static function register()
    {
        static::register_permalink();

        add_action('init', function () {
            register_post_type(static::POST_TYPE,
                [
                    'labels' => [
                        'name' => __(static::POST_TYPE_NAME),
                        'singular_name' => __(static::POST_TYPE_NAME_SINGULAR)
                    ],
                    'public' => true,
                    'rest_base' => 'composites',
                    'show_in_rest' => true, // enable rest api
                    'rewrite' => [
                        'willow_custom_permalink' => static::POST_PERMALINK_STRUCTURE,
                    ],
                    'has_archive' => false,
                    'supports' => [
                        'title',
                        'author',
                        'revisions'
                    ],
                    'taxonomies' => [
                        'category'
                    ],
                ]
            );
            static::register_acf_fields();
        });

        add_action('save_post', [__CLASS__, 'on_save'], 10, 2);
        add_action('save_post', [__CLASS__, 'on_save_slug_change'], 5, 2);
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public static function id_from_contenthub_id($id)
    {
        global $wpdb;
        return $wpdb->get_var(
            $wpdb->prepare("SELECT post_id FROM wp_postmeta WHERE meta_key=%s AND meta_value=%s", static::POST_META_CONTENTHUB_ID, $id)
        );
    }

    private static function register_acf_fields()
    {
        AttachmentGroup::register();
        CompositeFieldGroup::register();
        MagazineFieldGroup::register();
        MetaFieldGroup::register();
        TeaserFieldGroup::register();
        TranslationStateFieldGroup::register();
        TaxonomyFieldGroup::register(WpTaxonomy::get_custom_taxonomies());
    }

    private static function register_permalink()
    {
        add_filter('pre_option_permalink_structure', function ($currentSetting) {
            return static::POST_PERMALINK_STRUCTURE;
        });

        add_filter('pre_option_category_base', function ($currentSetting) {
            return static::CATEGORY_BASE;
        });
    }

    public static function on_save($postId, WP_Post $post)
    {
        if (static::post_type_match_and_not_auto_draft($post) &&
            !get_post_meta($postId, static::POST_META_CONTENTHUB_ID, true) &&
            $site = Plugin::instance()->settings->get_site(pll_current_language('locale'))) {
            $contentHubId = base64_encode(sprintf('COMPOSITES-%s-%s', $site->brand->brand_code, $postId));
            update_post_meta($postId, WpComposite::POST_META_CONTENTHUB_ID, $contentHubId);
        }
    }

    /**
     * Triggers the slug change hook on post save
     *
     * @param          $postId
     * @param \WP_Post $post
     */
    public static function on_save_slug_change($postId, WP_Post $post)
    {
        if (static::post_type_match_and_not_auto_draft($post)) {
            $oldLink = get_permalink();
            if ($oldLink && acf_validate_save_post()) {  // Validate acf input and get old link
                acf_save_post($postId);
                $newLink = get_permalink($postId);
                if ($newLink && $newLink !== $oldLink) { // Check if old link differ from new
                    do_action(static::SLUG_CHANGE_HOOK, $postId, $oldLink, $newLink); // Trigger the hook
                }
            }
        }
    }

    public static function map_all($callback)
    {
        $args = [
            'post_type' => static::POST_TYPE,
            'posts_per_page' => 100,
            'paged' => 0
        ];

        $posts = query_posts($args);

        while ($posts) {
            collect($posts)->each(function (WP_Post $post) use ($callback) {
                $callback($post);
            });

            $args['paged']++;
            $posts = query_posts($args);
        }
    }

    private static function flush_rewrite_rules_if_needed()
    {
        if (get_option(Plugin::FLUSH_REWRITE_RULES_FLAG)) {
            flush_rewrite_rules();
            delete_option(Plugin::FLUSH_REWRITE_RULES_FLAG);
        }
    }

    private static function post_type_match_and_not_auto_draft($post)
    {
        return is_object($post) && $post->post_type === static::POST_TYPE && $post->post_status !== 'auto-draft';
    }
}
