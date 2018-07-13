<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class MagazineFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class MagazineFieldGroup
{
    public static function register()
    {
        static::create_acf_field_group();
    }

    private static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_58f607ebb79e3',
                'title' => 'Magazine',
                'fields' => [
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
                'menu_order' => 3,
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
