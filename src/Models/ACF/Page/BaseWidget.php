<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Helpers\SortBy;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\CustomRelationship;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Number;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Tab;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Taxonomy;
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
     * @return Collection
     */
    protected function getSortByFields()
    {
        /** @var Collection $fields */
        $fields = collect([
            $this->getSortByTab(),
            $this->getSortByOptions(),
            $this->getTeaserAmount(),
            $this->getTeaserList(),
            $this->getCategoryField(),
            $this->getTagField(),
        ])->reject(function ($field) {
            return is_null($field);
        });

        return $fields->merge($this->getCustomTaxonomyFields());
    }

    private function getSortByTab()
    {
        $sortBy = new Tab('field_' . hash('md5', $this->widgetName . 'sort by tab'));
        $sortBy->setPlacement('left')
            ->setLabel('Sort by');

        return $sortBy;
    }

    private function getSortByOptions()
    {
        $options = new Radio($this->sortByField);
        $options->setChoices([
                SortBy::POPULAR => 'Popular (Cxense)',
                SortBy::RECENTLY_VIEWED => 'Recently Viewed by User (Cxense)',
                SortBy::CUSTOM => 'Taxonomy (WordPress)',
                SortBy::MANUAL => 'Manual (WordPress)',
            ])
            ->setDefaultValue(SortBy::POPULAR)
            ->setLabel('Sort by')
            ->setName(AcfName::FIELD_SORT_BY);

        return $options;
    }

    private function getTeaserAmount()
    {
        if ($this->getMaxTeasers() > 1) {
            $amount = new Number('field_' . hash('md5', $this->widgetName . AcfName::FIELD_TEASER_AMOUNT));
            $amount->setDefaultValue($this->getDefaultTeaserAmount())
                ->setMin($this->getMinTeasers())
                ->setMax($this->getMaxTeasers())
                ->setLabel('Amount of Teasers to display')
                ->setName(AcfName::FIELD_TEASER_AMOUNT)
                ->setInstructions(
                    'How many teasers should it contain?<br><b>Note:</b> Cxense max Teasers is configured to 10.'
                )
                ->setRequired(1)
                ->setConditionalLogic([
                    [
                        [
                            'field' => $this->sortByField,
                            'operator' => '!=',
                            'value' => SortBy::MANUAL,
                        ],
                    ],
                ]);

            return $amount;
        }

        return null;
    }

    private function getTeaserList()
    {
        $teaserList = new CustomRelationship('field_' . hash('md5', $this->widgetName . AcfName::FIELD_TEASER_LIST));
        $teaserList->setPostType([WpComposite::POST_TYPE])
            ->setFilters(['search', 'taxonomy', 'post_tag'])
            ->setMin($this->getMinTeasers())
            ->setMax($this->getMaxTeasers())
            ->setLabel('Teasers')
            ->setName(AcfName::FIELD_TEASER_LIST)
            ->setRequired(1)
            ->setConditionalLogic([
                [
                    [
                        'field' => $this->sortByField,
                        'operator' => '==',
                        'value' => SortBy::MANUAL,
                    ],
                ],
            ]);
        return $teaserList;
    }

    private function getCategoryField()
    {
        $category = new Taxonomy('field_' . hash('md5', $this->widgetName . AcfName::FIELD_CATEGORY));
        $category->setTaxonomy(AcfName::TAXONOMY_CATEGORY)
            ->setFieldType('select')
            ->setAllowNull(1)
            ->setLabel('Category')
            ->setName(AcfName::FIELD_CATEGORY)
            ->setConditionalLogic([
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
            ]);

        return $category;
    }

    private function getTagField()
    {
        $tag = new Taxonomy('field_' . hash('md5', $this->widgetName . AcfName::FIELD_TAG));
        $tag->setTaxonomy(AcfName::TAXONOMY_TAG)
            ->setFieldType('select')
            ->setAllowNull(1)
            ->setLabel('Tag')
            ->setName(AcfName::FIELD_TAG)
            ->setConditionalLogic([
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
            ]);

        return $tag;
    }

    private function getCustomTaxonomyFields()
    {
        return WpTaxonomy::get_custom_taxonomies()->map(function ($customTaxonomy) {
            $taxonomy = new Taxonomy('field_'.md5($this->widgetName . $customTaxonomy->machine_name));
            $taxonomy->setTaxonomy($customTaxonomy->machine_name)
                ->setFieldType('select')
                ->setAllowNull(1)
                ->setLabel($customTaxonomy->name)
                ->setName($customTaxonomy->machine_name)
                ->setConditionalLogic([
                    [
                        [
                            'field' => $this->sortByField,
                            'operator' => '==',
                            'value' => SortBy::CUSTOM,
                        ],
                    ]
                ]);
            return $taxonomy;
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
