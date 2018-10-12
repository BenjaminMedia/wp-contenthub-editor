<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Illuminate\Support\Collection;

class TaxonomyList implements WidgetContract
{

    public function getLayout(): array
    {
        return [
            'key' => 'layout_5bc0556fd2014',
            'name' => AcfName::WIDGET_TAXONOMY_TEASER_LIST,
            'label' => 'Taxonomy Teaser List',
            'display' => 'block',
            'sub_fields' => array_merge([
                $this->getTitle(),
                $this->getDescription(),
                $this->getImage(),
                $this->getLabel(),
                $this->getDisplayHint(),
                $this->getTaxonomy(),
                $this->getTaxonomySelector(AcfName::FIELD_CATEGORY, 'Categories', AcfName::TAXONOMY_CATEGORY),
                $this->getTaxonomySelector(AcfName::FIELD_TAG, 'Tags', AcfName::TAXONOMY_TAG),
            ], $this->getCustomTaxonomySelectors()->toArray()),
            'min' => '',
            'max' => '',
        ];
    }

    private function getTitle()
    {
        return [
            'key' => 'field_5bc0557ad2015',
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

    private function getDescription()
    {
        return [
            'key' => 'field_5bc05582d2016',
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
            'simple_mde_config' => 'simple',
            'font_size' => 14,
        ];
    }

    private function getImage()
    {
        return [
            'key' => 'field_5bc05591d2017',
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

    private function getLabel()
    {
        return [
            'key' => 'field_5bc055a2d2018',
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

    private function getTaxonomy()
    {
        return [
            'key' => 'field_5bc055aad2019',
            'label' => 'Taxonomy',
            'name' => AcfName::FIELD_TAXONOMY,
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => array_merge([
                AcfName::FIELD_CATEGORY => 'Category',
                AcfName::FIELD_TAG => 'Tag',
            ], $this->getCustomTaxonomies()->toArray()),
            'default_value' => [
                0 => AcfName::FIELD_CATEGORY,
            ],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ];
    }

    private function getTaxonomySelector(string $name, string $label, string $taxonomy)
    {
        return [
            'key' => 'field_' . hash('md5', $name . $label . $taxonomy),
            'label' => $label,
            'name' => $name,
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => [
                [
                    [
                        'field' => 'field_5bc055aad2019',
                        'operator' => '==',
                        'value' => $name,
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'taxonomy' => $taxonomy,
            'field_type' => 'multi_select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
        ];
    }

    private function getDisplayHint()
    {
        return [
            'key' => 'field_5bc0566fff85d',
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
                AcfName::DISPLAY_HINT_DEFAULT => 'Default',
                AcfName::DISPLAY_HINT_PRESENTATION => 'Presentation',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => AcfName::DISPLAY_HINT_DEFAULT,
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }

    private function getCustomTaxonomies(): Collection
    {
        return WpTaxonomy::get_custom_taxonomies()->mapWithKeys(function ($taxonomy) {
            return [$taxonomy->machine_name => $taxonomy->name];
        });
    }

    private function getCustomTaxonomySelectors(): Collection
    {
        return $this->getCustomTaxonomies()->map(function ($taxonomy, $machineName) {
            return $this->getTaxonomySelector($machineName, ucfirst($taxonomy), $machineName);
        });
    }
}
