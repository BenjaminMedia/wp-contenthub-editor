<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Select;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Taxonomy;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Illuminate\Support\Collection;

class TaxonomyList implements WidgetContract
{
    const KEY = 'layout_5bc0556fd2014';
    const TITLE_KEY = 'field_5bc0557ad2015';
    const DESCRIPTION_KEY = 'field_5bc05582d2016';
    const IMAGE_KEY = 'field_5bc05591d2017';
    const LABEL_KEY = 'field_5bc055a2d2018';
    const TAXONOMY_KEY = 'field_5bc055aad2019';
    const DISPLAY_HINT_KEY = 'field_5bc0566fff85d';

    public function getLayout(): ACFLayout
    {
        $fields = collect([
            $this->getTitle(),
            $this->getDescription(),
            $this->getImage(),
            $this->getLabel(),
            $this->getDisplayHint(),
            $this->getTaxonomy(),
            $this->getTaxonomySelector(AcfName::FIELD_CATEGORY, 'Categories', AcfName::TAXONOMY_CATEGORY),
            $this->getTaxonomySelector(AcfName::FIELD_TAG, 'Tags', AcfName::TAXONOMY_TAG),
        ])->merge($this->getCustomTaxonomySelectors());

        $layout = new ACFLayout(self::KEY);
        $layout->setLabel('Taxonomy Teaser List')
            ->setName(AcfName::WIDGET_TAXONOMY_TEASER_LIST)
            ->setSubFields($fields);

        return $layout;
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName(AcfName::FIELD_TITLE)
            ->setRequired(1);

        return $title;
    }

    private function getDescription()
    {
        $description = new MarkdownEditor(self::DESCRIPTION_KEY);
        $description->setConfig('simple')
            ->setLabel('Description')
            ->setName(AcfName::FIELD_DESCRIPTION);

        return $description;
    }

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName(AcfName::FIELD_IMAGE);

        return $image;
    }

    private function getLabel()
    {
        $label = new Text(self::LABEL_KEY);
        $label->setLabel('Label')
            ->setName(AcfName::FIELD_LABEL);

        return $label;
    }

    private function getTaxonomy()
    {
        $taxonomy = new Select(self::TAXONOMY_KEY);
        $taxonomy->setChoices(array_merge([
                AcfName::FIELD_CATEGORY => 'Category',
                AcfName::FIELD_TAG => 'Tag'
            ], $this->getCustomTaxonomies()->toArray()))
            ->setDefaultValue([AcfName::FIELD_CATEGORY])
            ->setLabel('Taxonomy')
            ->setName(AcfName::FIELD_TAXONOMY);

        return $taxonomy;
    }

    private function getTaxonomySelector(string $name, string $label, string $taxonomy)
    {
        $taxonomySelector = new Taxonomy('field_' . hash('md5', $name . $label . $taxonomy));
        $taxonomySelector->setTaxonomy($taxonomy)
            ->setFieldType('multi_select')
            ->setReturnFormat('id')
            ->setLabel($label)
            ->setName($name)
            ->setRequired(1)
            ->setConditionalLogic([
                [
                    [
                        'field' => 'field_5bc055aad2019',
                        'operator' => '==',
                        'value' => $name,
                    ],
                ],
            ]);

        return $taxonomySelector;
    }

    private function getDisplayHint()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                AcfName::DISPLAY_HINT_DEFAULT => 'Default',
                AcfName::DISPLAY_HINT_PRESENTATION => 'Presentation'
            ])
            ->setDefaultValue(AcfName::DISPLAY_HINT_DEFAULT)
            ->setLabel('Display Format')
            ->setName(AcfName::FIELD_DISPLAY_HINT);

        return $displayHint;
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
