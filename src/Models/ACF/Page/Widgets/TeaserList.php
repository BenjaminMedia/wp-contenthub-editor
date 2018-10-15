<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Tab;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\URL;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;

class TeaserList extends BaseWidget
{
    const KEY = '5bb3190811fdf';
    const SETTINGS_KEY = 'field_5bb31940ffcf0';
    const TITLE_KEY = 'field_5bb31a3bffcf2';
    const LABEL_KEY = 'field_5bb759ce606a7';
    const DESCRIPTION_KEY = 'field_5bb31a6c1d390';
    const IMAGE_KEY = 'field_5bb31a7d1d391';
    const LINK_KEY = 'field_5bb31a9c1d392';
    const LINK_LABEL_KEY = 'field_5bb759f880c92';
    const DISPLAY_HINT_KEY = 'field_5bb319a1ffcf1';

    public function __construct(array $config = [])
    {
        parent::__construct(AcfName::WIDGET_TEASER_LIST, $config);
    }

    public function getLayout(): ACFLayout
    {
        $fields = collect([
            $this->getSettingsTab(),
            $this->getTitleField(),
            $this->getLabelField(),
            $this->getDescriptionField(),
            $this->getImageField(),
            $this->getLinkField(),
            $this->getLinkLabelField(),
            $this->getDisplayHintField()
        ])->merge($this->getSortByFields());

        $layout = new ACFLayout(self::KEY);
        $layout->setName(AcfName::WIDGET_TEASER_LIST)
            ->setLabel('Teaser List')
            ->setSubFields($fields);

        return $layout;
    }

    private function getSettingsTab()
    {
        $tab = new Tab(self::SETTINGS_KEY);
        $tab->setLabel('Settings');

        return $tab;
    }

    private function getTitleField()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName(AcfName::FIELD_TITLE)
            ->setRequired(1);

        return $title;
    }

    private function getLabelField()
    {
        $label = new Text(self::LABEL_KEY);
        $label->setLabel('Label')
            ->setName(AcfName::FIELD_LABEL);

        return $label;
    }

    private function getDescriptionField()
    {
        $description = new Textarea(self::DESCRIPTION_KEY);
        $description->setLabel('Description')
            ->setName(AcfName::FIELD_DESCRIPTION);

        return $description;
    }

    private function getImageField()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName(AcfName::FIELD_IMAGE);

        return $image;
    }

    private function getLinkField()
    {
        $link = new URL(self::LINK_KEY);
        $link->setLabel('Link')
            ->setName(AcfName::FIELD_LINK);

        return $link;
    }

    private function getLinkLabelField()
    {
        $linkLabel = new Text(self::LINK_LABEL_KEY);
        $linkLabel->setLabel('Link Label')
            ->setName(AcfName::FIELD_LINK_LABEL)
            ->setConditionalLogic([
                [
                    [
                        'field' => 'field_5bb31a9c1d392',
                        'operator' => '!=empty',
                    ],
                ],
            ]);

        return $linkLabel;
    }

    private function getDisplayHintField()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                AcfName::DISPLAY_HINT_DEFAULT => 'Standard',
                AcfName::DISPLAY_HINT_PRESENTATION => 'Presentation',
                AcfName::DISPLAY_HINT_ORDERED_LIST => 'Ordered List',
                AcfName::DISPLAY_HINT_MAGAZINE_ISSUE => 'Magazine Issue',
                AcfName::DISPLAY_HINT_SLIDER => 'Slider',
                AcfName::DISPLAY_HINT_FEATURED_WITH_RELATED => 'Featured with Related',
            ])
            ->setDefaultValue(AcfName::DISPLAY_HINT_DEFAULT)
            ->setLabel('Display Format')
            ->setName(AcfName::FIELD_DISPLAY_HINT);

        return $displayHint;
    }
}
