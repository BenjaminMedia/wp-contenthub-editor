<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class AssociatedComposite implements WidgetContract
{
    const KEY = '58e393a7128b3';
    const COMPOSITE_KEY = 'field_58e393e0128b4';
    const LOCKED_KEY = 'field_5922be585cda6';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'name' => 'associated_composite',
            'label' => 'Sub Content',
            'display' => 'block',
            'sub_fields' => [
                $this->getComposite(),
                $this->getLockedContent(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getComposite()
    {
        return [
            'key' => self::COMPOSITE_KEY,
            'label' => 'Content',
            'name' => 'composite',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'post_type' => [
                0 => 'contenthub_composite',
            ],
            'taxonomy' => [
            ],
            'filters' => [
                0 => 'search',
                1 => 'taxonomy',
            ],
            'elements' => '',
            'min' => '',
            'max' => 1,
            'return_format' => 'object',
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
