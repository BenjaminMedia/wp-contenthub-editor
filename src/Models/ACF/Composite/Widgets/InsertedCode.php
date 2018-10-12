<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class InsertedCode implements WidgetContract
{
    const KEY = '58aae89d0f005';
    const CODE_KEY = 'field_58aae8b00f006';
    const LOCKED_KEY = 'field_5922bdbd5cda1';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'name' => 'inserted_code',
            'label' => 'Inserted Code',
            'display' => 'block',
            'sub_fields' => [
                $this->getCode(),
                $this->getLockedContent(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getCode()
    {
        return [
            'key' => self::CODE_KEY,
            'label' => 'Code',
            'name' => 'code',
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
