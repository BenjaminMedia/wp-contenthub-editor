<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

/**
 * Class Composite
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models
 */
class Composite
{
    const POST_TYPE = 'contenthub_composite';
    const POST_TYPE_NAME = 'Content';
    const POST_TYPE_NAME_SINGULAR = 'Composite';

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

    private static function register_acf_fields() {
        require_once (__DIR__ .'/ACF/Composite/CompositeFields.php');
    }
}
