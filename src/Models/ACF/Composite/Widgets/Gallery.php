<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use PHPUnit\Framework\Constraint\Composite;

class Gallery implements WidgetContract
{
    const KEY = '5a4f4dea1745f';
    const TITLE_KEY = 'field_5a952a1a811d2';
    const DESCRIPTION_KEY = 'field_5bbb153867a6f';
    const IMAGES_KEY = 'field_5a4f4dfd17460';
    const IMAGE_KEY = 'field_5a4f4e0f17461';
    const IMAGE_TITLE_KEY = 'field_5bbb151067a6e';
    const IMAGE_DESCRIPTION_KEY = 'field_5af2a0fcb1027';
    const LOCKED_KEY = 'field_5a4f4e5f17462';
    const DISPLAY_HINT_KEY = 'field_5af2a198b1028';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'name' => 'gallery',
            'label' => 'Gallery',
            'display' => 'block',
            'sub_fields' => [
                $this->getTitle(),
                $this->getDescription(),
                $this->getImages(),
                $this->getLockedContent(),
                $this->getDisplayHint(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getTitle()
    {
        return [
            'key' => self::TITLE_KEY,
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
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
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ];
    }

    private function getDescription()
    {
        return [
            'key' => self::DESCRIPTION_KEY,
            'label' => 'Description',
            'name' => 'description',
            'type' => 'markdown-editor',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'simple_mde_config' => 'standard',
            'font_size' => 14,
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
            'layout' => 'block',
            'button_label' => 'Add Image to Gallery',
            'sub_fields' => [
                $this->getImage(),
                $this->getImageTitle(),
                $this->getImageDescription(),
            ],
        ];
    }

    private function getImage()
    {
        return [
            'key' => self::IMAGE_KEY,
            'label' => 'Image',
            'name' => 'image',
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

    private function getImageTitle()
    {
        return [
            'key' => self::IMAGE_TITLE_KEY,
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
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
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ];
    }

    private function getImageDescription()
    {
        return [
            'key' => self::IMAGE_DESCRIPTION_KEY,
            'label' => 'Description',
            'name' => 'description',
            'type' => 'markdown-editor',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'simple_mde_config' => 'standard',
            'font_size' => 14,
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

    private function getDisplayHint()
    {
        return [
            'key' => self::DISPLAY_HINT_KEY,
            'label' => 'Display Format',
            'name' => 'display_hint',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => [
                'default' => 'Default',
                'inline' => 'Inline',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => 'default',
            'layout' => 'vertical',
            'return_format' => 'value',
        ];
    }
}
