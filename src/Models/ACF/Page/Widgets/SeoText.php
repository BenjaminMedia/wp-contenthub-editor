<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;

class SeoText extends BaseWidget
{
    public function __construct(array $config = [])
    {
        parent::__construct(AcfName::WIDGET_SEO_TEXT, $config);
    }

    public function getLayout(): array
    {
        return [
            'key' => '5bbc5200c758b',
            'name' => AcfName::WIDGET_SEO_TEXT,
            'label' => 'SEO Text',
            'display' => 'block',
            'sub_fields' => array_merge([
                $this->getTitleField(),
                $this->getDescriptionField(),
                $this->getImageField(),
                $this->getImagePosition(),
            ]),
            'min' => '',
            'max' => '',
        ];
    }

    private function getTitleField()
    {
        return [
            'key' => 'field_5bbc5050c8158',
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

    private function getDescriptionField()
    {
        return [
            'key' => 'field_5bbc5058c8159',
            'label' => 'Description',
            'name' => AcfName::FIELD_DESCRIPTION,
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

    private function getImageField()
    {
        return [
            'key' => 'field_5bbc505fc815a',
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

    public function getImagePosition()
    {
        return [
            'key' => 'field_5be56c5ca42ef',
            'label' => 'Image Position',
            'name' => 'image_position',
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
                'before' => 'Before Text',
                'after' => 'After Text',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => 'left',
            'layout' => 'horizontal',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }
}
