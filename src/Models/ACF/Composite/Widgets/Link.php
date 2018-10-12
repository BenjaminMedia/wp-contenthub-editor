<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Link implements WidgetContract
{
    const KEY = '590b1798c8768';
    const URL_KEY = 'field_590b17c4c876a';
    const BUTTON_LABEL_KEY = 'field_590b179fc8769';
    const TARGET_KEY = 'field_590b17d4c876b';
    const LOCKED_KEY = 'field_5922be3e5cda5';

    public function getLayout(): array
    {
        $link = new ACFLayout(self::KEY);
        $link->setName('link')
            ->setLabel('Link')
            ->addSubField($this->getUrl())
            ->addSubField($this->getButtonLabel())
            ->addSubField($this->getTarget())
            ->addSubField($this->getLockedContent());

        return $link->toArray();
    }

    private function getUrl()
    {
        return [
            'key' => self::URL_KEY,
            'label' => 'URL',
            'name' => 'url',
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

    private function getButtonLabel()
    {
        return [
            'key' => self::BUTTON_LABEL_KEY,
            'label' => 'Button text',
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

    private function getTarget()
    {
        return [
            'key' => self::TARGET_KEY,
            'label' => 'Target',
            'name' => 'target',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => [
                'Default' => 'Default For the Site',
                'Self' => 'Open in same window/tab',
                'Blank' => 'Open in a new tab',
                'Download' => 'Force download a file',
            ],
            'default_value' => [
                0 => 'Default',
            ],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
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
