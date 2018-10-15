<?php

namespace Bonnier\WP\ContentHub\Editor\Tests\wpunit\ACF;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeFieldGroup;
use PHPUnit\Framework\TestCase;

class CompositeFieldGroupTest extends TestCase
{
    public function testACFStructure()
    {
        $expected = [
            'key' => 'group_58abfd3931f2f',
            'title' => 'Composite Fields',
            'fields' => [
                [
                    'key' => 'field_58e388862daa8',
                    'label' => 'Kind',
                    'name' => 'kind',
                    'type' => 'radio',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'choices' => [
                        'Article' => 'Article',
                        'Gallery' => 'Gallery',
                        'Story' => 'Story',
                        'Review' => 'Review',
                        'Recipe' => 'Recipe',
                    ],
                    'allow_null' => 0,
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'default_value' => 'Article',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ],
                [
                    'key' => 'field_58abfebd21b82',
                    'label' => 'Description',
                    'name' => 'description',
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
                    'rows' => '',
                    'new_lines' => '',
                ],
                [
                    'key' => 'field_5af9888b4b7a1',
                    'label' => 'Author',
                    'name' => 'author',
                    'type' => 'user',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'role' => '',
                    'allow_null' => 0,
                    'multiple' => 0,
                ],
                [
                    'key' => 'field_5a8d44d026528',
                    'label' => 'Author Description',
                    'name' => 'author_description',
                    'type' => 'text',
                    'instructions' => 'Extra information about the authors ie. who took the photos or did the styling',
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
                [
                    'key' => 'field_58e39a7118284',
                    'label' => 'Category',
                    'name' => 'category',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'taxonomy' => 'category',
                    'field_type' => 'select',
                    'allow_null' => 0,
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 0,
                    'return_format' => 'object',
                    'multiple' => 0,
                ],
                [
                    'key' => 'field_58f606b6e1fb0',
                    'label' => 'Tags',
                    'name' => 'tags',
                    'type' => 'taxonomy',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'taxonomy' => 'post_tag',
                    'field_type' => 'multi_select',
                    'allow_null' => 0,
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 0,
                    'return_format' => 'object',
                    'multiple' => 0,
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
            'menu_order' => 0,
            'position' => 'acf_after_title',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => [
                'slug',
                'categories',
                'author'
            ],
            'active' => 1,
            'description' => '',
        ];
        $actual = CompositeFieldGroup::getFieldGroup();

        $this->assertSame($expected, $actual);
    }
}
