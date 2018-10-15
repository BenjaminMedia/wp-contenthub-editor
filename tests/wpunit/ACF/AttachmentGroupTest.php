<?php

namespace Bonnier\WP\ContentHub\Editor\Tests\wpunit\ACF;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\AttachmentGroup;
use PHPUnit\Framework\TestCase;

class AttachmentGroupTest extends TestCase
{
    public function testACFStructure()
    {
        $expected = [
            'key' => 'group_5b45e49cc3d58',
            'title' => 'Attachment',
            'fields' => [
                [
                    'key' => 'field_5b45e57fe6b14',
                    'label' => 'Caption',
                    'name' => 'attachment_caption',
                    'type' => 'markdown-editor',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'simple_mde_config' => 'simple',
                    'font_size' => 14,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'attachment',
                        'operator' => '==',
                        'value' => 'all',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ];

        $actual = AttachmentGroup::getFieldGroup();
        $this->assertSame($expected, $actual);
    }
}
