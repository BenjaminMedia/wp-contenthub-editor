<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Image implements WidgetContract
{
    const KEY = '58aaef9fb02bc';
    const LEAD_IMAGE_KEY = 'field_5908407c246cb';
    const FILE_KEY = 'field_58aaf042b02c1';
    const LOCKED_KEY = 'field_5922bd8e5cd9e';
    const LINK_KEY = 'field_5ba0c550e9e5f';
    const TARGET_KEY = 'field_5ba0c558e9e60';
    const RELATIONSHIP_KEY = 'field_5ba0c574e9e61';
    const DISPLAY_HINT_KEY = 'field_5bb4a00b2aa05';

    public function getLayout(): array
    {
        $image = new ACFLayout(self::KEY);
        $image->setName('image')
            ->setLabel('Image')
            ->addSubField($this->getLeadImage())
            ->addSubField($this->getFile())
            ->addSubField($this->getLockedContent())
            ->addSubField($this->getLink())
            ->addSubField($this->getTarget())
            ->addSubField($this->getRelationship())
            ->addSubField($this->getDisplayHint());

        return $image->toArray();
    }

    private function getLeadImage()
    {
        return [
            'key' => self::LEAD_IMAGE_KEY,
            'label' => 'Lead Image',
            'name' => 'lead_image',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
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

    private function getFile()
    {
        return [
            'key' => self::FILE_KEY,
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
                        'field' => 'field_5921f0c676974',
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

    private function getLink()
    {
        return [
            'key' => self::LINK_KEY,
            'label' => 'Link',
            'name' => 'link',
            'type' => 'url',
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
        ];
    }

    private function getTarget()
    {
        return [
            'key' => self::TARGET_KEY,
            'label' => 'Open in',
            'name' => 'target',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => self::LINK_KEY,
                        'operator' => '!=empty',
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => [
                '_self' => 'Same window',
                '_blank' => 'New window',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => '_self',
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }

    private function getRelationship()
    {
        return [
            'key' => self::RELATIONSHIP_KEY,
            'label' => 'Relationship (Follow / No Follow)',
            'name' => 'rel',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => self::LINK_KEY,
                        'operator' => '!=empty',
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => [
                'follow' => 'Follow',
                'nofollow' => 'No Follow',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => 'follow',
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
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
