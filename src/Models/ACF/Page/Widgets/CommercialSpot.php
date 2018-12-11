<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;

class CommercialSpot extends BaseWidget
{

    public function __construct(array $config = [])
    {
        parent::__construct(AcfName::WIDGET_COMMERCIAL_SPOT, $config);
    }

    public function getLayout(): array
    {
        return [
            'key' => 'layout_5c0fa2f4ea1a5',
            'name' => AcfName::WIDGET_COMMERCIAL_SPOT,
            'label' => 'Commercial Spot',
            'display' => 'block',
            'sub_fields' => [
                $this->getTitleField(),
                $this->getDescriptionField(),
                $this->getImageField(),
                $this->getLinkField(),
                $this->getLinkLabelField(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getTitleField()
    {
        return [
            'key' => 'field_5c0fa33fea1a8',
            'label' => 'Title',
            'type' => AcfName::FIELD_TITLE,
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
            'key' => 'field_5c0fa350ea1a9',
            'label' => 'Description',
            'type' => AcfName::FIELD_DESCRIPTION,
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

    private function getImageField()
    {
        return [
            'key' => 'field_5c0fa313ea1a7',
            'label' => 'Image',
            'name' => 'image',
            'type' => AcfName::FIELD_IMAGE,
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'return_format' => 'array',
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
            'key' => 'field_5c0fa2fcea1a6',
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
            'key' => 'field_5c0fa375ea1aa',
            'label' => 'Link Label',
            'name' => AcfName::FIELD_LINK_LABEL,
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => 'field_5c0fa2fcea1a6',
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
}
