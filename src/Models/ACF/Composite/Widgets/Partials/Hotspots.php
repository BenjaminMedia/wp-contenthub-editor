<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Partials;

use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Hotspots implements WidgetContract
{
    public function getLayout(): array
    {
        return [
            'key' => 'field_5bb21d902c2c6',
            'label' => 'Hotspots',
            'name' => 'hotspots',
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
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Add Hotspot',
            'sub_fields' => [
                $this->getTitle(),
                $this->getDescription(),
                $this->getCoordinates(),
            ],
        ];
    }

    private function getTitle()
    {
        return [
            'key' => 'field_5bb21db12c2c7',
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
            'key' => 'field_5bb21db72c2c8',
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

    private function getCoordinates()
    {
        return [
            'key' => 'field_5bb21dc52c2c9',
            'label' => 'Coordinates',
            'name' => 'coordinates',
            'type' => 'image-hotspot-coordinates',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
        ];
    }
}
