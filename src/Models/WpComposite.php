<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

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
    const POST_META_CONTENTHUB_ID = 'contenthub_id';

    /**
     * Register the composite as a custom wp post type
     */
    public static function register() {
        add_action('init', function() {
            register_post_type(static::POST_TYPE,
                [
                    'labels' => [
                        'name' => __(static::POST_TYPE_NAME),
                        'singular_name' => __(static::POST_TYPE_NAME_SINGULAR)
                    ],
                    'public' => true,
                    'has_archive' => true,
                    'supports' => [
                        'title'
                    ]
                ]
            );
        });
        static::register_acf_fields();
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public static function id_from_contenthub_id($id) {
        global $wpdb;
        return $wpdb->get_var(
            $wpdb->prepare("SELECT post_id FROM wp_postmeta WHERE meta_key=%s AND meta_value=%s", static::POST_META_CONTENTHUB_ID, $id)
        );
    }

    private static function register_acf_fields() {
        require_once (__DIR__ .'/ACF/Composite/CompositeFields.php');
    }
}
