<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Partials\Hotspots;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class HotspotImage implements WidgetContract
{
    const KEY = 'layout_5bb21d074132f';
    const TITLE_KEY = 'field_5bb21d1841330';
    const DESCRIPTION_KEY = 'field_5bb21d2a2c2c4';
    const IMAGE_KEY = 'field_5bb21d3b2c2c5';
    const DISPLAY_HINT_KEY = 'field_5bb36df662c91';

    public function getLayout(): array
    {
        $hotspotImage = new ACFLayout(self::KEY);
        $hotspotImage->setName('hotspot_image')
            ->setLabel('Hotspot Image')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getImage())
            ->addSubField($this->getDisplayHint())
            ->addSubField(with(new Hotspots)->getLayout());

        return $hotspotImage->toArray();
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
            'return_format' => 'array',
            'preview_size' => 'large',
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
                'ordered' => 'ordered',
                'unordered' => 'unordered',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => '',
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }
}
