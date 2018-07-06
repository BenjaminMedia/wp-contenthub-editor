<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class TranslationStateFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class TranslationStateFieldGroup
{
    const TRANSLATION_STATES = [
        'ready' => 'Ready For Translation',
        'progress' => 'In Progress',
        'translated' => 'Translated',
    ];

    public static function register() {
        static::register_field_group();
    }

    private static function register_field_group() {
        if( function_exists('acf_add_local_field_group') ) {
            acf_add_local_field_group([
                'key' => 'group_5940debee7ae2',
                'title' => 'Translation State',
                'fields' => [
                    [
                        'key' => 'field_5940df2d4eff9',
                        'label' => '',
                        'name' => 'translation_state',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'choices' => static::TRANSLATION_STATES,
                        'default_value' => [
                        ],
                        'allow_null' => 1,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ],
                    [
                        'key' => 'field_59885bce3d421',
                        'label' => 'Translation deadline',
                        'name' => 'translation_deadline',
                        'type' => 'date_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'display_format' => 'F j, Y',
                        'return_format' => 'Y-m-d',
                        'first_day' => 1,
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => WpComposite::POST_TYPE,
                        ],
                    ],
                ],
                'menu_order' => 0,
                'position' => 'side',
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
