<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Helpers\PermalinkHelper;
use Bonnier\WP\ContentHub\Editor\Http\Api\CompositeRestController;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\MetaFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TaxonomyFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TeaserFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TranslationStateFieldGroup;
use Bonnier\WP\ContentHub\Editor\ContenthubEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\TeaserList;
use Bonnier\WP\ContentHub\Editor\Models\Partials\EstimatedReadingTime;
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
    const POST_META_WHITE_ALBUM_ID = 'white_album_id';
    const POST_META_WHITE_ALBUM_SOURCE = 'white_album_source';
    const POST_META_CUSTOM_PERMALINK = 'custom_permalink';
    const POST_TEASER_TITLE = 'teaser_title';
    const POST_TEASER_DESCRIPTION = 'teaser_description';
    const POST_TEASER_IMAGE = 'teaser_image';
    const POST_META_TITLE = 'seo_teaser_title';
    const POST_META_DESCRIPTION = 'seo_teaser_description';
    const POST_CANONICAL_URL = 'canonical_url';
    const POST_FACEBOOK_TITLE = 'fb_teaser_title';
    const POST_FACEBOOK_DESCRIPTION = 'fb_teaser_description';
    const POST_FACEBOOK_IMAGE = 'fb_teaser_image';
    const POST_TWITTER_TITLE = 'tw_teaser_title';
    const POST_TWITTER_DESCRIPTION = 'tw_teaser_description';
    const POST_TWITTER_IMAGE = 'tw_teaser_image';
    const SLUG_CHANGE_HOOK = 'contenthub_composite_slug_change';

    /**
     * Register the composite as a custom wp post type
     */
    public static function register()
    {
        if (! class_exists('willow_permalinks')) {
            add_action('init', array(PermalinkHelper::class, 'instance'), 0);
        }
        add_filter('pre_option_permalink_structure', function ($currentSetting) {
            return static::POST_PERMALINK_STRUCTURE;
        });

        add_filter('pre_option_category_base', function ($currentSetting) {
            return static::CATEGORY_BASE;
        });

        add_action('init', function () {
            register_post_type(static::POST_TYPE,
                [
                    'labels' => [
                        'name' => __(static::POST_TYPE_NAME),
                        'singular_name' => __(static::POST_TYPE_NAME_SINGULAR)
                    ],
                    'public' => true,
                    'rest_base' => 'composites',
                    'rest_controller_class' => CompositeRestController::class,
                    'show_in_rest' => true, // enable rest api
                    'rewrite' => [
                        'willow_custom_permalink' => static::POST_PERMALINK_STRUCTURE,
                    ],
                    'has_archive' => false,
                    'supports' => [
                        'title',
                        'author'
                    ],
                    'taxonomies' => [
                        'category',
                        'post_tag'
                    ],
                ]
            );
            static::register_acf_fields();
        });

        add_action('save_post', [__CLASS__, 'on_save'], 10, 2);
        add_action('save_post', [__CLASS__, 'on_save_slug_change'], 5, 2);
        add_action('added_term_relationship', [__CLASS__, 'addedTermRelationship'], 10, 3);
        add_action('acf/save_post', [EstimatedReadingTime::class, 'addEstimatedReadingTime'], 20);
        add_filter('pll_copy_post_metas', [__CLASS__, 'checkIfTermIsTranslated'], 10, 3);
        add_filter('admin_enqueue_scripts', [__CLASS__, 'loadCharacterCounterScript']);
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

    /**
     * @param $id
     *
     * @return null|string
     */
    public static function id_from_white_album_id($id)
    {
        global $wpdb;
        return $wpdb->get_var(
            $wpdb->prepare("SELECT post_id FROM wp_postmeta WHERE meta_key=%s AND meta_value=%s", static::POST_META_WHITE_ALBUM_ID, $id)
        );
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public static function white_album_id_form_post_id($id)
    {
        return get_post_meta($id, static::POST_META_WHITE_ALBUM_ID, true);
    }

    private static function register_acf_fields()
    {
        CompositeFieldGroup::register();
        TeaserFieldGroup::register();
        MetaFieldGroup::register();
        TranslationStateFieldGroup::register();
        TaxonomyFieldGroup::register(WpTaxonomy::get_custom_taxonomies());
    }

    public static function on_save($postId, WP_Post $post)
    {
        if (static::post_type_match_and_not_auto_draft($post) &&
            !get_post_meta($postId, static::POST_META_CONTENTHUB_ID, true) &&
            $site = ContenthubEditor::instance()->settings->get_site(LanguageProvider::getCurrentLanguage('locale'))) {
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
        remove_action('save_post', [__CLASS__, 'on_save_slug_change'], 5, 2);
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
        add_action('save_post', [__CLASS__, 'on_save_slug_change'], 5, 2);
    }

    public static function addedTermRelationship(int $postID, int $termID, string $taxonomy)
    {
        if (($taxonomy === 'category' && $post = get_post($postID)) &&
            $post instanceof WP_Post &&
            $post->post_type === self::POST_TYPE
        ) {
            // Needed by the redirect plugin, so categories updated through wp_set_post_categories() are synced to acf
            update_field('category', $termID, $post->ID);
        }
    }

    public static function map_all($callback)
    {
        $args = [
            'post_type' => static::POST_TYPE,
            'posts_per_page' => 100,
            'paged' => 1,
            'order' => 'ASC',
            'orderby' => 'ID'
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

    public static function checkIfTermIsTranslated($metas, $sync, $fromPostId)
    {
        if (isset($_GET['new_lang'])) {
            $fromTerms = array(get_field('category', $fromPostId));
            $fromTags = get_field('tags', $fromPostId);
            if (is_array($fromTags)) {
                foreach ($fromTags as $tag) {
                    array_push($fromTerms, $tag);
                }
            }

            $newLanguage = $_GET['new_lang'];
            foreach ($fromTerms as $term) {
                if (is_null($term) || is_null($term->term_id)) {
                    continue;
                }
                if (!LanguageProvider::isTermTranslated($term->term_id, $newLanguage)) {
                    if ($term->taxonomy === 'category') {
                        unset($metas[array_search('category', $metas)]);
                    }

                    if ($term->taxonomy === 'post_tag') {
                        unset($metas[array_search('tags', $metas)]);
                    }
                };
            }
        }

        return $metas;
    }

    public static function loadCharacterCounterScript($admin_page) {
        if ($admin_page === 'post.php' && get_post()->post_type === static::POST_TYPE) {
            wp_register_script(
                'text-field-character-counter',
                ContenthubEditor::instance()->pluginUrl . 'js/text-field-character-counter.js',
                ['acf-input'],
                filemtime(ContenthubEditor::instance()->pluginDir . 'js/text-field-character-counter.js')
            );
            wp_enqueue_script(
                'text-field-character-counter', '', [], filemtime(ContenthubEditor::instance()->pluginDir . 'js/text-field-character-counter.js')
            );
        }
    }

    private static function post_type_match_and_not_auto_draft($post)
    {
        return is_object($post) && $post->post_type === static::POST_TYPE && $post->post_status !== 'auto-draft';
    }
}
