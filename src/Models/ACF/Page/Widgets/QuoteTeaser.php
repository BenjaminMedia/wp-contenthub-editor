<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;

class QuoteTeaser extends BaseWidget
{
    private const LINK_TYPE_KEY = 'field_5e68a31bbe22d';

    public function __construct(array $config = [])
    {
        parent::__construct(AcfName::WIDGET_QUOTE_TEASER, $config);
    }

    public function getLayout(): array
    {
        return [
            'key' => 'layout_5e68a042a0db7',
            'name' => AcfName::WIDGET_QUOTE_TEASER,
            'label' => 'Quote Teaser',
            'display' => 'block',
            'sub_fields' => array_merge([
                $this->getQuoteField(),
                $this->getAuthorField(),
                $this->getLinkTypeField(),
                $this->getLinkField(),
                $this->getLinkLabelField(),
                $this->getCompositeContentField()
            ]),
            'min' => '',
            'max' => '',
        ];
    }

    private function getQuoteField()
    {
        return [
            'key' => 'field_5e68a04ca0db8',
            'label' => 'Quote',
            'name' => AcfName::FIELD_QUOTE,
            'type' => 'textarea',
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

    private function getAuthorField()
    {
        return [
            'key' => 'field_5e68a068a0db9',
            'label' => 'Author',
            'name' => AcfName::FIELD_AUTHOR,
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

    private function getLinkTypeField()
    {
        return [
            'key' => static::LINK_TYPE_KEY,
            'label' => 'Link type',
            'name' => AcfName::FIELD_LINK_TYPE,
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
                'external' => 'External URL',
                'composite' => 'Composite Content',
            ],
            'default_value' => [
                0 => 'external',
            ],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ];
    }

    private function getLinkLabelField()
    {
        return [
            'key' => 'field_5e68a41bba6be',
            'label' => 'Link Label',
            'name' => AcfName::FIELD_LINK_LABEL,
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => static::LINK_TYPE_KEY,
                        'operator' => '==',
                        'value' => 'external',
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

    private function getLinkField()
    {
        return [
            'key' => 'field_5e68a075a0dba',
            'label' => 'Link',
            'name' => AcfName::FIELD_LINK,
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => static::LINK_TYPE_KEY,
                        'operator' => '==',
                        'value' => 'external',
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
        ];
    }

    private function getCompositeContentField()
    {
        return [
            'key' => 'field_5e68a391be22e',
            'label' => 'Composite Content',
            'name' => AcfName::FIELD_COMPOSITE_CONTENT,
            'type' => 'custom_relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => static::LINK_TYPE_KEY,
                        'operator' => '==',
                        'value' => 'composite',
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'post_type' => [
                0 => 'contenthub_composite',
            ],
            'taxonomy' => '',
            'post_tag' => '',
            'filters' => [
                0 => 'search',
                1 => 'taxonomy',
                2 => 'post_tag',
            ],
            'elements' => '',
            'min' => 1,
            'max' => 1,
            'return_format' => 'object',
        ];
    }
}
