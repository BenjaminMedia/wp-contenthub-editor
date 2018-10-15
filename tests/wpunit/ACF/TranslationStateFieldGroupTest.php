<?php

namespace Bonnier\WP\ContentHub\Editor\Tests\wpunit\ACF;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\TranslationStateFieldGroup;
use PHPUnit\Framework\TestCase;

class TranslationStateFieldGroupTest extends TestCase
{
    public function testACFStructure()
    {
        $expected = [
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
                    'choices' => [
                        'ready' => 'Ready For Translation',
                        'progress' => 'In Progress',
                        'translated' => 'Translated',
                    ],
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
                        'value' => 'contenthub_composite',
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
        ];

        $actual = TranslationStateFieldGroup::getFieldGroup();
        $this->assertSame($expected, $actual);
    }
}
