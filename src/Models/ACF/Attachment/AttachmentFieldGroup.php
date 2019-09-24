<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Attachment;

class AttachmentFieldGroup
{
    const CAPTION_FIELD_KEY =  'field_5c51b211deb49';


    public static function register()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_5c51a64a46db2',
                'title' => 'Attachments',
                'fields' => [
                    [
                        'key' => static::CAPTION_FIELD_KEY,
                        'label' => 'Caption',
                        'name' => 'caption',
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
            ]);
        }
    }
}
