<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page;

use Bonnier\WP\ContentHub\Editor\ACF\CustomRelationship;
use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Helpers\SortBy;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Illuminate\Support\Collection;

abstract class BaseWidget implements WidgetContract
{
    protected $widgetName;
    protected $sortByField;
    protected $config;

    /**
     * BaseWidget constructor.
     * Configuretion options are:
     * 'minTeasers' - Minimum number of teasers to select
     * 'maxTeasers' - Maximum number of teasers to select
     * 'teaserCountDefault' - Default number of teasers selected.
     *
     * @param string $widgetName
     * @param array $config
     */
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
        /** @var Collection $fields */
        $fields = collect([
            $this->getSortByTab(),
            $this->getSortByOptions(),
            $this->getTeaserAmount(),
            $this->getSkipTeasersAmount(),
            $this->getTeaserList(),
            $this->getCategoryField(),
            $this->getTagField(),
        ])->rejectNullValues();

        return $fields->merge($this->getCustomTaxonomyFields())->toArray();
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
                SortBy::SHUFFLE => 'Shuffle',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => SortBy::POPULAR,
            'layout' => 'vertical',
            'return_format' => 'value',
        ];
    }

    private function getSkipTeasersAmount()
    {
        return [
            'key' => 'field_' . hash('md5', $this->widgetName . AcfName::FIELD_SKIP_TEASERS_AMOUNT),
            'label' => 'Amount of Teasers skip',
            'name' => AcfName::FIELD_SKIP_TEASERS_AMOUNT,
            'type' => 'number',
            'instructions' =>
                'How many teasers should it it skip?',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::CUSTOM,
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'default_value' => 0,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => 0,
            'max' => PHP_INT_MAX,
            'step' => '',
        ];
    }

    private function getTeaserAmount()
    {
        if ($this->getMaxTeasers() > 1) {
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
                'default_value' => $this->getDefaultTeaserAmount(),
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => $this->getMinTeasers(),
                'max' => $this->getMaxTeasers(),
                'step' => '',
            ];
        }

        return null;
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
            'taxonomy' => array(
                0 => 'category',
                1 => 'post_tag',
            ),
            'post_tag' => '',
            'filters' => [
                0 => 'search',
                1 => 'taxonomy',
                2 => 'post_tag',
            ],
            'elements' => '',
            'min' => $this->getMinTeasers(),
            'max' => $this->getMaxTeasers(),
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
        });
    }

    private function getMinTeasers()
    {
        return array_get($this->config, 'minTeasers', 1);
    }

    private function getMaxTeasers()
    {
        return array_get($this->config, 'minTeasers', 12);
    }

    private function getDefaultTeaserAmount()
    {
        return array_get($this->config, 'teaserCountDefault', 4);
    }
}
