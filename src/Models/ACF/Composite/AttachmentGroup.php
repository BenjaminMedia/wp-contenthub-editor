<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

/**
 * Class AttachmentGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class AttachmentGroup
{
    const CAPTION_FIELD_NAME = 'attachment_caption';
    const CAPTION_FIELD_KEY = 'field_5b45e57fe6b14';

    public static function register()
    {
        static::create_acf_field_group();
    }

    public static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')) :
            acf_add_local_field_group([
                'key' => 'group_5b45e49cc3d58',
                'title' => 'Attachment',
                'fields' => [
                    [
                        'key' => self::CAPTION_FIELD_KEY,
                        'label' => 'Caption',
                        'name' => self::CAPTION_FIELD_NAME,
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

        endif;
    }
}
