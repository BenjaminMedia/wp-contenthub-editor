<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class MetaFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class TeaserFieldGroup
{
    public static function register() {
        static::create_acf_field_group();
    }

    private static function create_acf_field_group() {
        if( function_exists('acf_add_local_field_group') ) {
            acf_add_local_field_group([
                'key' => 'group_58e38d7eca92e',
                'title' => 'Teaser',
                'fields' => [
                    [
                        'key' => 'field_58e38d86194e2',
                        'label' => 'Title',
                        'name' => 'teaser_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => 'wpautop',
                        'readonly' => 0,
                        'disabled' => 0,
                    ],
                    [
                        'key' => 'field_58e38da2194e3',
                        'label' => 'Image',
                        'name' => 'teaser_image',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'return_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ],
                    [
                        'key' => 'field_58e38dd0194e4',
                        'label' => 'Description',
                        'name' => 'teaser_description',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'contenthub_composite',
                        ],
                    ],
                ],
                'menu_order' => 2,
                'position' => 'acf_after_title',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ]);
        }
    }
}
