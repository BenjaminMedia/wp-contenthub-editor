<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class LeadParagraph implements WidgetContract
{
    const KEY = 'layout_5bbb614643179';
    const TITLE_KEY = 'field_5bbb61464317a';
    const DESCRIPTION_KEY = 'field_5bbb61464317b';
    const DISPLAY_HINT_KEY = 'field_5bbb61464317d';

    public function getLayout(): array
    {
        $leadParagraph = new ACFLayout(self::KEY);
        $leadParagraph->setName('lead_paragraph')
            ->setLabel('Lead Paragraph')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getDisplayHint());

        return $leadParagraph->toArray();
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
                'inline' => 'Chapter',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => 'ordered',
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }
}
