<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page;

use Bonnier\WP\ContentHub\Editor\ACF\CustomRelationship;
use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Helpers\SortBy;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;

class BaseWidget
{
    protected $widgetName;
    protected $sortByField;
    protected $config;

    public function __construct(string $widgetName, array $config = [])
    {
        $this->widgetName = $widgetName;
        $this->sortByField = 'field_' . hash('md5', $this->widgetName . AcfName::FIELD_SORT_BY);
        $this->config = $config;
    }

    /**
     * @return array
     */
    protected function getSortByFields()
    {
        return array_merge([
            $this->getSortByTab(),
            $this->getSortByOptions(),
            $this->getTeaserAmount(),
            $this->getTeaserList(),
            $this->getCategoryField(),
            $this->getTagField(),
        ], $this->getCustomTaxonomyFields());
    }

    private function getSortByTab()
    {
        return [
            'key' => 'field_' . hash('md5', $this->widgetName . 'sort by tab'),
            'label' => 'Sort by',
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
            'placement' => 'left',
            'endpoint' => 0,
        ];
    }

    private function getSortByOptions()
    {
        return [
            'key' => $this->sortByField,
            'label' => 'Sort by',
            'name' => AcfName::FIELD_SORT_BY,
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
                SortBy::POPULAR => 'Popular (Cxense)',
                SortBy::RECENTLY_VIEWED => 'Recently Viewed by User (Cxense)',
                SortBy::CUSTOM => 'Taxonomy (WordPress)',
                SortBy::MANUAL => 'Manual (WordPress)',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => SortBy::POPULAR,
            'layout' => 'vertical',
            'return_format' => 'value',
        ];
    }

    private function getTeaserAmount()
    {
        return [
            'key' => 'field_' . hash('md5', $this->widgetName . AcfName::FIELD_TEASER_AMOUNT),
            'label' => 'Amount of Teasers to display',
            'name' => AcfName::FIELD_TEASER_AMOUNT,
            'type' => 'number',
            'instructions' =>
                'How many teasers should it contain?<br><b>Note:</b> Cxense max Teasers is configured to 10.',
            'required' => 1,
            'conditional_logic' => [
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '!=',
                        'value' => SortBy::MANUAL,
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'default_value' => $this->config['teaserCountDefault'] ?? 4,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => $this->config['minTeasers'] ?? 1,
            'max' => $this->config['maxTeasers'] ?? 12,
            'step' => '',
        ];
    }

    private function getTeaserList()
    {
        return [
            'key' => 'field_' . hash('md5', $this->widgetName . AcfName::FIELD_TEASER_LIST),
            'label' => 'Teasers',
            'name' => AcfName::FIELD_TEASER_LIST,
            'type' => CustomRelationship::NAME,
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => [
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::MANUAL,
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'post_type' => [
                0 => WpComposite::POST_TYPE,
            ],
            'taxonomy' => '',
            'post_tag' => '',
            'filters' => [
                0 => 'search',
                1 => 'taxonomy',
                2 => 'post_tag',
            ],
            'elements' => '',
            'min' => $this->config['minTeasers'] ?? 1,
            'max' => $this->config['maxTeasers'] ?? 12,
            'return_format' => 'object',
        ];
    }

    private function getCategoryField()
    {
        return [
            'key' => 'field_' . hash('md5', $this->widgetName . AcfName::FIELD_CATEGORY),
            'label' => 'Category',
            'name' => AcfName::FIELD_CATEGORY,
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::CUSTOM,
                    ],
                ],
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::POPULAR,
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ],
            'taxonomy' => 'category',
            'field_type' => 'select',
            'allow_null' => 1,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'object',
            'multiple' => 0,
        ];
    }

    private function getTagField()
    {
        return [
            'key' => 'field_' . hash('md5', $this->widgetName . AcfName::FIELD_TAG),
            'label' => 'Tag',
            'name' => AcfName::FIELD_TAG,
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::CUSTOM,
                    ],
                ],
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::POPULAR,
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '50',
                'class' => '',
                'id' => '',
            ],
            'taxonomy' => 'post_tag',
            'field_type' => 'select',
            'allow_null' => 1,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'object',
            'multiple' => 0,
        ];
    }

    private function getCustomTaxonomyFields()
    {
        return WpTaxonomy::get_custom_taxonomies()->map(function ($customTaxonomy) {
            return [
                'key' => 'field_'.md5($this->widgetName . $customTaxonomy->machine_name),
                'label' => $customTaxonomy->name,
                'name' => $customTaxonomy->machine_name,
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'field' => $this->sortByField,
                            'operator' => '==',
                            'value' => SortBy::CUSTOM,
                        ],
                    ]
                ],
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'taxonomy' => $customTaxonomy->machine_name,
                'field_type' => 'select',
                'allow_null' => 1,
                'add_term' => 0,
                'save_terms' => 1,
                'load_terms' => 0,
                'return_format' => 'object',
                'multiple' => 0,
            ];
        })->toArray();
    }
}
