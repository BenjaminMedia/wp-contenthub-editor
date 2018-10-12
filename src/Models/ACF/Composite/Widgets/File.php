<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class File implements WidgetContract
{
    const KEY = '590aef9de4a5e';
    const CAPTION_KEY = 'field_590aefe3e4a5f';
    const FILE_KEY = 'field_590af026e4a61';
    const IMAGES_KEY = 'field_5921e5a83f4ea';
    const LOCKED_KEY = 'field_590af0eee4a62';
    const IMAGE_KEY = 'field_5921e94c3f4eb';
    const LABEL_KEY = 'field_59e49490911cf';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'name' => 'file',
            'label' => 'File',
            'display' => 'block',
            'sub_fields' => [
                $this->getCaption(),
                $this->getFile(),
                $this->getImages(),
                $this->getLockedContent(),
                $this->getLabel(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getCaption()
    {
        return [
            'key' => self::CAPTION_KEY,
            'label' => 'Caption',
            'name' => 'caption',
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
        ];
    }

    private function getFile()
    {
        return [
            'key' => self::FILE_KEY,
            'label' => 'File',
            'name' => 'file',
            'type' => 'file',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'return_format' => 'array',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => '',
        ];
    }

    private function getImages()
    {
        return [
            'key' => self::IMAGES_KEY,
            'label' => 'Images',
            'name' => 'images',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'Add Image',
            'sub_fields' => [
                $this->getImage(),
            ],
        ];
    }

    private function getLockedContent()
    {
        return [
            'key' => self::LOCKED_KEY,
            'label' => 'Locked Content',
            'name' => 'locked_content',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => CompositeContentFieldGroup::LOCKED_CONTENT_KEY,
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
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ];
    }

    private function getLabel()
    {
        return [
            'key' => self::LABEL_KEY,
            'label' => 'Download Button Text (Optional]',
            'name' => 'download_button_text',
            'type' => 'text',
            'instructions' => 'This will override the default button text.',
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
        ];
    }

    private function getImage()
    {
        return [
            'key' => self::IMAGE_KEY,
            'label' => 'File',
            'name' => 'file',
            'type' => 'image',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
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
        ];
    }
}
