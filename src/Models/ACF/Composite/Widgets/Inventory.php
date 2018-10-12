<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Inventory implements WidgetContract
{
    const KEY = '58aeadaacbe5c';
    const TITLE_KEY = 'field_58e3971e4d277';
    const INVENTORY_KEY = 'field_58aeadcdcbe5d';
    const NAME_KEY = 'field_58aeae3fcbe61';
    const VALUE_KEY = 'field_58aeae4ccbe62';
    const LOCKED_KEY = 'field_5922be6d5cda7';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'name' => 'inventory',
            'label' => 'Inventory',
            'display' => 'block',
            'sub_fields' => [
                $this->getTitle(),
                $this->getInventory(),
                $this->getLockedContent(),
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
            'readonly' => 0,
            'disabled' => 0,
        ];
    }

    private function getInventory()
    {
        return [
            'key' => self::INVENTORY_KEY,
            'label' => 'Inventory Items',
            'name' => 'inventory_items',
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
            'layout' => 'table',
            'button_label' => 'Add Row',
            'sub_fields' => [
                $this->getName(),
                $this->getValue(),
            ],
        ];
    }

    private function getName()
    {
        return [
            'key' => self::NAME_KEY,
            'label' => 'Name',
            'name' => 'name',
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
            'readonly' => 0,
            'disabled' => 0,
        ];
    }

    private function getValue()
    {
        return [
            'key' => self::VALUE_KEY,
            'label' => 'Value',
            'name' => 'value',
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
            'readonly' => 0,
            'disabled' => 0,
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
}
