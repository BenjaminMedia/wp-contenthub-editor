<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class ParagraphList implements WidgetContract
{
    const KEY = 'layout_5bb4bd1afd048';
    const TITLE_KEY = 'field_5bb4bd2ffd049';
    const DESCRIPTION_KEY = 'field_5bb4bd38fd04a';
    const IMAGE_KEY = 'field_5bb4bd65fd04b';
    const DISPLAY_HINT_KEY = 'field_5bb4bd75fd04c';
    const ITEMS_KEY = 'field_5bb4be68fd04d';
    const CUSTOM_BULLET_KEY = 'field_5bb4c1bb8cdbc';
    const ITEM_TITLE_KEY = 'field_5bb4be86fd04e';
    const ITEM_DESCRIPTION_KEY = 'field_5bb4be91fd04f';
    const ITEM_IMAGE_KEY = 'field_5bb4bea5fd050';

    public function getLayout(): array
    {
        $paragraphList = new ACFLayout(self::KEY);
        $paragraphList->setName('paragraph_list')
            ->setLabel('Paragraph List')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getImage())
            ->addSubField($this->getDisplayHint())
            ->addSubField($this->getItems());

        return $paragraphList->toArray();
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
            'simple_mde_config' => 'simple',
            'font_size' => 14,
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
            'required' => 0,
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
                'ordered' => 'Number List',
                'unordered' => 'Bullet List',
                'image' => 'Image List',
                'custom' => 'Custom List',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => 'ordered',
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }

    private function getItems()
    {
        return [
            'key' => self::ITEMS_KEY,
            'label' => 'Items',
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'row',
            'button_label' => 'Add item',
            'sub_fields' => [
                $this->getCustomBullet(),
                $this->getItemTitle(),
                $this->getItemDescription(),
                $this->getItemImage(),
            ],
        ];
    }

    private function getCustomBullet()
    {
        return [
            'key' => self::CUSTOM_BULLET_KEY,
            'label' => 'Custom Bullet',
            'name' => 'custom_bullet',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => [
                [
                    [
                        'field' => 'field_5bb4bd75fd04c',
                        'operator' => '==',
                        'value' => 'custom',
                    ],
                ],
            ],
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

    private function getItemTitle()
    {
        return [
            'key' => self::ITEM_TITLE_KEY,
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
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

    private function getItemDescription()
    {
        return [
            'key' => self::ITEM_DESCRIPTION_KEY,
            'label' => 'Description',
            'name' => 'description',
            'type' => 'markdown-editor',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'simple_mde_config' => 'simple',
            'font_size' => 14,
        ];
    }

    private function getItemImage()
    {
        return [
            'key' => self::ITEM_IMAGE_KEY,
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
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
