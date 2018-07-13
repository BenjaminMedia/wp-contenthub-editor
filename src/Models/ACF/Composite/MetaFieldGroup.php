<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class MetaFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class MetaFieldGroup
{
    const COMMERCIAL_TYPES = [
        'Advertorial' => 'Advertorial',
        'AffiliatePartner' => 'AffiliatePartner',
        'CommercialContent' => 'CommercialContent',
        'Offer' => 'Offer',
    ];

    public static function register()
    {
        static::create_acf_field_group();
        static::register_pll_translations();
    }

    private static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_58fde819ea0f1',
                'title' => 'Meta',
                'fields' => [
                    [
                        'key' => 'field_58fde84d034e4',
                        'label' => 'Commercial',
                        'name' => 'commercial',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ],
                    [
                        'key' => 'field_58fde876034e5',
                        'label' => 'Commercial Type',
                        'name' => 'commercial_type',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_58fde84d034e4',
                                    'operator' => '==',
                                    'value' => '1',
                                ],
                            ],
                        ],
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'choices' => static::COMMERCIAL_TYPES,
                        'default_value' => [
                        ],
                        'allow_null' => 1,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'placeholder' => '',
                        'disabled' => 0,
                        'readonly' => 0,
                        'return_format' => 'value',
                    ],
                    [

                        'key' => 'field_58fde876034e6',
                        'label' => 'Internal Comment',
                        'name' => 'internal_comment',
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
                        'maxlength' => '',
                        'rows' => '3',
                        'new_lines' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ]
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
                'menu_order' => 4,
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

    private static function register_pll_translations()
    {
        if (function_exists('pll_register_string')) {
            collect(static::COMMERCIAL_TYPES)->each(function ($commercialType) {
                pll_register_string($commercialType, $commercialType, 'content-hub-editor');
            });
        }
    }
}
