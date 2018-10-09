<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class TeaserList extends BaseWidget
{
    public function __construct(array $config = [])
    {
        parent::__construct(AcfName::WIDGET_TEASER_LIST, $config);
    }

    public function getLayout(): array
    {
        return [
            'key' => '5bb3190811fdf',
            'name' => AcfName::WIDGET_TEASER_LIST,
            'label' => 'Teaser List',
            'display' => 'block',
            'sub_fields' => array_merge([
                $this->getSettingsTab(),
                $this->getTitleField(),
                $this->getLabelField(),
                $this->getDescriptionField(),
                $this->getImageField(),
                $this->getLinkField(),
                $this->getLinkLabelField(),
                $this->getDisplayHintField(),
            ], $this->getSortByFields()),
            'min' => '',
            'max' => '',
        ];
    }

    private function getSettingsTab()
    {
        return [
            'key' => 'field_5bb31940ffcf0',
            'label' => 'Settings',
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'placement' => 'top',
            'endpoint' => 0,
        ];
    }

    private function getTitleField()
    {
        return [
            'key' => 'field_5bb31a3bffcf2',
            'label' => 'Title',
            'name' => AcfName::FIELD_TITLE,
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

    private function getLabelField()
    {
        return [
            'key' => 'field_5bb759ce606a7',
            'label' => 'Label',
            'name' => AcfName::FIELD_LABEL,
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

    private function getDescriptionField()
    {
        return [
            'key' => 'field_5bb31a6c1d390',
            'label' => 'Description',
            'name' => AcfName::FIELD_DESCRIPTION,
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

    private function getImageField()
    {
        return [
            'key' => 'field_5bb31a7d1d391',
            'label' => 'Image',
            'name' => AcfName::FIELD_IMAGE,
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

    private function getLinkField()
    {
        return [
            'key' => 'field_5bb31a9c1d392',
            'label' => 'Link',
            'name' => AcfName::FIELD_LINK,
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

    private function getLinkLabelField()
    {
        return [
            'key' => 'field_5bb759f880c92',
            'label' => 'Link Label',
            'name' => AcfName::FIELD_LINK_LABEL,
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => 'field_5bb31a9c1d392',
                        'operator' => '!=empty',
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

    private function getDisplayHintField()
    {
        return [
            'key' => 'field_5bb319a1ffcf1',
            'label' => 'Display Format',
            'name' => AcfName::FIELD_DISPLAY_HINT,
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
                AcfName::DISPLAY_HINT_DEFAULT => 'Standard',
                AcfName::DISPLAY_HINT_PRESENTATION => 'Presentation',
                AcfName::DISPLAY_HINT_ORDERED_LIST => 'Ordered List',
                AcfName::DISPLAY_HINT_MAGAZINE_ISSUE => 'Magazine Issue',
                AcfName::DISPLAY_HINT_SLIDER => 'Slider',
                AcfName::DISPLAY_HINT_FEATURED_WITH_RELATED => 'Featured with Related',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => 'default',
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }
}
