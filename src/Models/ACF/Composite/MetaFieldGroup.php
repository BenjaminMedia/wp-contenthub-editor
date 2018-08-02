<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
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
        static::register_translations();
    }

    private static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_58fde819ea0f1',
                'title' => 'Meta',
                'fields' => [
                    [
                        'key' => 'field_5af188bbb5b45',
                        'label' => 'Canonical URL',
                        'name' => 'canonical_url',
                        'type' => 'url',
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
                    ],
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
                        'key' => 'field_5a8d72d39ee48',
                        'label' => 'Commercial logo',
                        'name' => 'commercial_logo',
                        'type' => 'image',
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
                        'rows' => 3,
                        'new_lines' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ],
                    [
                        'key' => 'field_58f5febf3cb9c',
                        'label' => 'Magazine Year',
                        'name' => 'magazine_year',
                        'type' => 'select',
                        'instructions' => 'The magazine year ie. 2017 if the article was published in 2017',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'choices' => static::get_magazine_years(),
                        'default_value' => [],
                        'allow_null' => 1,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ],
                    [
                        'key' => 'field_58e3878b2dc76',
                        'label' => 'Magazine Issue',
                        'name' => 'magazine_issue',
                        'type' => 'select',
                        'instructions' => 'The magazine issue ie. 01 for the first issue of a given year',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'choices' => static::get_magazine_issues(),
                        'default_value' => [],
                        'allow_null' => 1,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
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

    private static function register_translations()
    {
        collect(static::COMMERCIAL_TYPES)->each(function ($commercialType) {
            LanguageProvider::registerStringTranslation($commercialType, $commercialType, 'content-hub-editor');
        });
    }

    private static function get_magazine_issues()
    {
        $issues = array_map(function ($issue) {
            if ($issue <= 9) {
                return '0' . $issue;
            }
            return $issue;
        }, range(1, 18));
        return array_combine($issues, $issues);
    }

    private static function get_magazine_years()
    {
        $years = array_reverse(range(1980, date("Y") + 1));
        return array_combine($years, $years);
    }
}
