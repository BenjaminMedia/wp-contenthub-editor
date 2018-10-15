<?php

namespace Bonnier\WP\ContentHub\Editor\Tests\wpunit\ACF;

use Bonnier\WP\ContentHub\Editor\Models\WpPage;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function testPageACFStructure()
    {
        $expected = [
            'key' => 'group_5bb31817b40e4',
            'title' => 'Page',
            'fields' => [
                [
                    'key' => 'field_5bb318f2ffcef',
                    'label' => 'Page Widgets',
                    'name' => 'page_widgets',
                    'type' => 'flexible_content',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => ''
                    ],
                    'button_label' => 'Add Widget',
                    'min' => '',
                    'max' => '',
                    'layouts' => [
                        [
                            'key' => '5bbb23424c064',
                            'name' => 'featured_content',
                            'label' => 'Featured Content',
                            'display' => 'block',
                            'sub_fields' => [
                                [
                                    'key' => 'field_5bbb4363c6cc8',
                                    'label' => 'Settings',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'placement' => 'top',
                                    'endpoint' => 0,
                                ],
                                [
                                    'key' => 'field_5bbb41245b291',
                                    'label' => 'Image',
                                    'name' => 'image',
                                    'type' => 'image',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'return_format' => 'id',
                                    'library' => 'all',
                                    'min_size' => '',
                                    'max_size' => '',
                                    'mime_types' => '',
                                    'preview_size' => 'thumbnail',
                                    'min_width' => '',
                                    'min_height' => '',
                                    'max_width' => '',
                                    'max_height' => '',
                                ],
                                [
                                    'key' => 'field_5bbb41625b292',
                                    'label' => 'Video',
                                    'name' => 'video',
                                    'type' => 'file',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'return_format' => 'id',
                                    'library' => 'all',
                                    'min_size' => '',
                                    'max_size' => '',
                                    'mime_types' => 'mp4',
                                ],
                                [
                                    'key' => 'field_5bbb417a5b293',
                                    'label' => 'Display Format',
                                    'name' => 'display_hint',
                                    'type' => 'radio',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'choices' => [
                                        'default' => 'Default',
                                        'lead' => 'Lead'
                                    ],
                                    'allow_null' => 0,
                                    'other_choice' => 0,
                                    'save_other_choice' => 0,
                                    'default_value' => 'default',
                                    'layout' => 'vertical',
                                    'return_format' => 'value',
                                ],
                                [
                                    'key' => 'field_31a5b25916ceafc76e8f01c3153264a7',
                                    'label' => 'Sort by',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'placement' => 'left',
                                    'endpoint' => 0,
                                ],
                                [
                                    'key' => 'field_8fcff84caaae86c595e90ab55001679d',
                                    'label' => 'Sort by',
                                    'name' => 'sort_by',
                                    'type' => 'radio',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'choices' => [
                                        'popular' => 'Popular (Cxense)',
                                        'recently_viewed' => 'Recently Viewed by User (Cxense)',
                                        'custom' => 'Taxonomy (WordPress)',
                                        'manual' => 'Manual (WordPress)'
                                    ],
                                    'allow_null' => 0,
                                    'other_choice' => 0,
                                    'save_other_choice' => 0,
                                    'default_value' => 'popular',
                                    'layout' => 'vertical',
                                    'return_format' => 'value',
                                ],
                                [
                                    'key' => 'field_5eb59ec096a24f8b88a7c94ef5670062',
                                    'label' => 'Teasers',
                                    'name' => 'teaser_list',
                                    'type' => 'custom_relationship',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_8fcff84caaae86c595e90ab55001679d',
                                                'operator' => '==',
                                                'value' => 'manual'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'post_type' => [
                                        'contenthub_composite'
                                    ],
                                    'taxonomy' => '',
                                    'post_tag' => '',
                                    'filters' => [
                                        'search',
                                        'taxonomy',
                                        'post_tag'
                                    ],
                                    'elements' => '',
                                    'min' => 1,
                                    'max' => 1,
                                    'return_format' => 'object',
                                ],
                                [
                                    'key' => 'field_d9b98ce6c59f249b640d2ef0f6fe95c8',
                                    'label' => 'Category',
                                    'name' => 'category',
                                    'type' => 'taxonomy',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_8fcff84caaae86c595e90ab55001679d',
                                                'operator' => '==',
                                                'value' => 'custom'
                                            ]
                                        ],
                                        [
                                            [
                                                'field' => 'field_8fcff84caaae86c595e90ab55001679d',
                                                'operator' => '==',
                                                'value' => 'popular'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'taxonomy' => 'category',
                                    'field_type' => 'select',
                                    'allow_null' => 1,
                                    'add_term' => 0,
                                    'save_terms' => 0,
                                    'load_terms' => 0,
                                    'return_format' => 'object',
                                    'multiple' => 0,
                                ],
                                [
                                    'key' => 'field_f231d3da5c894444062d94239acecbe3',
                                    'label' => 'Tag',
                                    'name' => 'tag',
                                    'type' => 'taxonomy',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_8fcff84caaae86c595e90ab55001679d',
                                                'operator' => '==',
                                                'value' => 'custom'
                                            ]
                                        ],
                                        [
                                            [
                                                'field' => 'field_8fcff84caaae86c595e90ab55001679d',
                                                'operator' => '==',
                                                'value' => 'popular'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'taxonomy' => 'post_tag',
                                    'field_type' => 'select',
                                    'allow_null' => 1,
                                    'add_term' => 0,
                                    'save_terms' => 0,
                                    'load_terms' => 0,
                                    'return_format' => 'object',
                                    'multiple' => 0,
                                ],
                            ],
                            'min' => '',
                            'max' => ''
                        ],
                        [
                            'key' => '5bb3190811fdf',
                            'name' => 'teaser_list',
                            'label' => 'Teaser List',
                            'display' => 'block',
                            'sub_fields' => [
                                [
                                    'key' => 'field_5bb31940ffcf0',
                                    'label' => 'Settings',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'placement' => 'top',
                                    'endpoint' => 0,
                                ],
                                [
                                    'key' => 'field_5bb31a3bffcf2',
                                    'label' => 'Title',
                                    'name' => 'title',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ],
                                [
                                    'key' => 'field_5bb759ce606a7',
                                    'label' => 'Label',
                                    'name' => 'label',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ],
                                [
                                    'key' => 'field_5bb31a6c1d390',
                                    'label' => 'Description',
                                    'name' => 'description',
                                    'type' => 'textarea',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'maxlength' => '',
                                    'rows' => '',
                                    'new_lines' => '',
                                ],
                                [
                                    'key' => 'field_5bb31a7d1d391',
                                    'label' => 'Image',
                                    'name' => 'image',
                                    'type' => 'image',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'return_format' => 'id',
                                    'library' => 'all',
                                    'min_size' => '',
                                    'max_size' => '',
                                    'mime_types' => '',
                                    'preview_size' => 'thumbnail',
                                    'min_width' => '',
                                    'min_height' => '',
                                    'max_width' => '',
                                    'max_height' => '',
                                ],
                                [
                                    'key' => 'field_5bb31a9c1d392',
                                    'label' => 'Link',
                                    'name' => 'link',
                                    'type' => 'url',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                ],
                                [
                                    'key' => 'field_5bb759f880c92',
                                    'label' => 'Link Label',
                                    'name' => 'link_label',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_5bb31a9c1d392',
                                                'operator' => '!=empty'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ],
                                [
                                    'key' => 'field_5bb319a1ffcf1',
                                    'label' => 'Display Format',
                                    'name' => 'display_hint',
                                    'type' => 'radio',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'choices' => [
                                        'default' => 'Standard',
                                        'presentation' => 'Presentation',
                                        'ordered_list' => 'Ordered List',
                                        'magazine_issue' => 'Magazine Issue',
                                        'slider' => 'Slider',
                                        'featured_with_related' => 'Featured with Related'
                                    ],
                                    'allow_null' => 0,
                                    'other_choice' => 0,
                                    'save_other_choice' => 0,
                                    'default_value' => 'default',
                                    'layout' => 'vertical',
                                    'return_format' => 'value',
                                ],
                                [
                                    'key' => 'field_ed5cbc903a2ba91e1311b1ab9bbb0c34',
                                    'label' => 'Sort by',
                                    'name' => '',
                                    'type' => 'tab',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'placement' => 'left',
                                    'endpoint' => 0,
                                ],
                                [
                                    'key' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                    'label' => 'Sort by',
                                    'name' => 'sort_by',
                                    'type' => 'radio',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'choices' => [
                                        'popular' => 'Popular (Cxense)',
                                        'recently_viewed' => 'Recently Viewed by User (Cxense)',
                                        'custom' => 'Taxonomy (WordPress)',
                                        'manual' => 'Manual (WordPress)'
                                    ],
                                    'allow_null' => 0,
                                    'other_choice' => 0,
                                    'save_other_choice' => 0,
                                    'default_value' => 'popular',
                                    'layout' => 'vertical',
                                    'return_format' => 'value',
                                ],
                                [
                                    'key' => 'field_e417816d5e67607a24f613c4a68c34f3',
                                    'label' => 'Amount of Teasers to display',
                                    'name' => 'teaser_amount',
                                    'type' => 'number',
                                    'instructions' => 'How many teasers should it contain?<br><b>Note:</b> Cxense max Teasers is configured to 10.',
                                    'required' => 1,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                                'operator' => '!=',
                                                'value' => 'manual'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => 4,
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'min' => 1,
                                    'max' => 12,
                                    'step' => '',
                                ],
                                [
                                    'key' => 'field_db5408919ca8fbf9866709ae53b070ca',
                                    'label' => 'Teasers',
                                    'name' => 'teaser_list',
                                    'type' => 'custom_relationship',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                                'operator' => '==',
                                                'value' => 'manual'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'post_type' => [
                                        'contenthub_composite'
                                    ],
                                    'taxonomy' => '',
                                    'post_tag' => '',
                                    'filters' => [
                                        'search',
                                        'taxonomy',
                                        'post_tag'
                                    ],
                                    'elements' => '',
                                    'min' => 1,
                                    'max' => 12,
                                    'return_format' => 'object',
                                ],
                                [
                                    'key' => 'field_33f454d47923615615ce8e2f89a5be09',
                                    'label' => 'Category',
                                    'name' => 'category',
                                    'type' => 'taxonomy',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                                'operator' => '==',
                                                'value' => 'custom'
                                            ]
                                        ],
                                        [
                                            [
                                                'field' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                                'operator' => '==',
                                                'value' => 'popular'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'taxonomy' => 'category',
                                    'field_type' => 'select',
                                    'allow_null' => 1,
                                    'add_term' => 0,
                                    'save_terms' => 0,
                                    'load_terms' => 0,
                                    'return_format' => 'object',
                                    'multiple' => 0,
                                ],
                                [
                                    'key' => 'field_280b4cf86887ce33fdc170b5ea64e36c',
                                    'label' => 'Tag',
                                    'name' => 'tag',
                                    'type' => 'taxonomy',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                                'operator' => '==',
                                                'value' => 'custom'
                                            ]
                                        ],
                                        [
                                            [
                                                'field' => 'field_df0cf2315b577511295667dfcc62aa3e',
                                                'operator' => '==',
                                                'value' => 'popular'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'taxonomy' => 'post_tag',
                                    'field_type' => 'select',
                                    'allow_null' => 1,
                                    'add_term' => 0,
                                    'save_terms' => 0,
                                    'load_terms' => 0,
                                    'return_format' => 'object',
                                    'multiple' => 0,
                                ],
                            ],
                            'min' => '',
                            'max' => ''
                        ],
                        [
                            'key' => 'layout_5bbc54bacaf10',
                            'name' => 'seo_text',
                            'label' => 'SEO Text',
                            'display' => 'block',
                            'sub_fields' => [
                                [
                                    'key' => 'field_5bbc5050c8158',
                                    'label' => 'Title',
                                    'name' => 'title',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ],
                                [
                                    'key' => 'field_5bbc5058c8159',
                                    'label' => 'Description',
                                    'name' => 'description',
                                    'type' => 'markdown-editor',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'simple_mde_config' => 'standard',
                                    'font_size' => 14,
                                ],
                                [
                                    'key' => 'field_5bbc505fc815a',
                                    'label' => 'Image',
                                    'name' => 'image',
                                    'type' => 'image',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'return_format' => 'id',
                                    'library' => 'all',
                                    'min_size' => '',
                                    'max_size' => '',
                                    'mime_types' => '',
                                    'preview_size' => 'thumbnail',
                                    'min_width' => '',
                                    'min_height' => '',
                                    'max_width' => '',
                                    'max_height' => '',
                                ]
                            ],
                            'min' => '',
                            'max' => ''
                        ],
                        [
                            'key' => 'layout_5bc0556fd2014',
                            'name' => 'taxonomy_teaser_list',
                            'label' => 'Taxonomy Teaser List',
                            'display' => 'block',
                            'sub_fields' => [
                                [
                                    'key' => 'field_5bc0557ad2015',
                                    'label' => 'Title',
                                    'name' => 'title',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ],
                                [
                                    'key' => 'field_5bc05582d2016',
                                    'label' => 'Description',
                                    'name' => 'description',
                                    'type' => 'markdown-editor',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'simple_mde_config' => 'simple',
                                    'font_size' => 14,
                                ],
                                [
                                    'key' => 'field_5bc05591d2017',
                                    'label' => 'Image',
                                    'name' => 'image',
                                    'type' => 'image',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'return_format' => 'id',
                                    'library' => 'all',
                                    'min_size' => '',
                                    'max_size' => '',
                                    'mime_types' => '',
                                    'preview_size' => 'thumbnail',
                                    'min_width' => '',
                                    'min_height' => '',
                                    'max_width' => '',
                                    'max_height' => '',
                                ],
                                [
                                    'key' => 'field_5bc055a2d2018',
                                    'label' => 'Label',
                                    'name' => 'label',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ],
                                [
                                    'key' => 'field_5bc0566fff85d',
                                    'label' => 'Display Format',
                                    'name' => 'display_hint',
                                    'type' => 'radio',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'choices' => [
                                        'default' => 'Default',
                                        'presentation' => 'Presentation'
                                    ],
                                    'allow_null' => 0,
                                    'other_choice' => 0,
                                    'save_other_choice' => 0,
                                    'default_value' => 'default',
                                    'layout' => 'vertical',
                                    'return_format' => 'value',
                                ],
                                [
                                    'key' => 'field_5bc055aad2019',
                                    'label' => 'Taxonomy',
                                    'name' => 'taxonomy',
                                    'type' => 'select',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'choices' => [
                                        'category' => 'Category',
                                        'tag' => 'Tag',
                                    ],
                                    'default_value' => [
                                        'category'
                                    ],
                                    'allow_null' => 0,
                                    'multiple' => 0,
                                    'ui' => 0,
                                    'ajax' => 0,
                                    'return_format' => 'value',
                                    'placeholder' => '',
                                ],
                                [
                                    'key' => 'field_05f78728bc41599f40be5a37c32ce999',
                                    'label' => 'Categories',
                                    'name' => 'category',
                                    'type' => 'taxonomy',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_5bc055aad2019',
                                                'operator' => '==',
                                                'value' => 'category'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'taxonomy' => 'category',
                                    'field_type' => 'multi_select',
                                    'allow_null' => 0,
                                    'add_term' => 0,
                                    'save_terms' => 0,
                                    'load_terms' => 0,
                                    'return_format' => 'id',
                                    'multiple' => 0,
                                ],
                                [
                                    'key' => 'field_4fc1fed6dbb2a0fce8e70c0ca56fccdd',
                                    'label' => 'Tags',
                                    'name' => 'tag',
                                    'type' => 'taxonomy',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => [
                                        [
                                            [
                                                'field' => 'field_5bc055aad2019',
                                                'operator' => '==',
                                                'value' => 'tag'
                                            ]
                                        ]
                                    ],
                                    'wrapper' => [
                                        'width' => '',
                                        'class' => '',
                                        'id' => ''
                                    ],
                                    'taxonomy' => 'post_tag',
                                    'field_type' => 'multi_select',
                                    'allow_null' => 0,
                                    'add_term' => 0,
                                    'save_terms' => 0,
                                    'load_terms' => 0,
                                    'return_format' => 'id',
                                    'multiple' => 0,
                                ],
                            ],
                            'min' => '',
                            'max' => ''
                        ],
                        [
                            'key' => 'layout_5bbc54bacaf10',
                            'name' => 'newsletter',
                            'label' => 'Newsletter',
                            'display' => 'block',
                            'sub_fields' => [],
                            'min' => '',
                            'max' => ''
                        ],
                        [
                            'key' => 'layout_5bbc54cbcaf11',
                            'name' => 'banner_placement',
                            'label' => 'Banner Placement',
                            'display' => 'block',
                            'sub_fields' => [],
                            'min' => '',
                            'max' => ''
                        ]
                    ],
                    'button_label' => 'Add Widget',
                    'min' => '',
                    'max' => ''
                ]
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page'
                    ]
                ]
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => [
                'discussion',
                'comments',
                'revisions',
                'featured_image',
                'send-trackbacks'
            ],
            'active' => 1,
            'description' => '',
        ];
        $actual = WpPage::getPageGroup();
        $this->assertSame($expected, $actual);
    }
}
