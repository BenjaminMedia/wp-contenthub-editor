<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Partials\Hotspots;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\ImageHotspotCoordinate;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Repeater;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class HotspotImage implements WidgetContract
{
    const KEY = 'layout_5bb21d074132f';
    const TITLE_KEY = 'field_5bb21d1841330';
    const DESCRIPTION_KEY = 'field_5bb21d2a2c2c4';
    const IMAGE_KEY = 'field_5bb21d3b2c2c5';
    const DISPLAY_HINT_KEY = 'field_5bb36df662c91';
    const HOTSPOT_KEY = 'field_5bb21d902c2c6';
    const HOTSPOT_TITLE_KEY = 'field_5bb21db12c2c7';
    const HOTSPOT_DESCRIPTION_KEY = 'field_5bb21db72c2c8';
    const HOTSPOT_COORDINATE_KEY = 'field_5bb21dc52c2c9';

    public function getLayout(): ACFLayout
    {
        $hotspotImage = new ACFLayout(self::KEY);
        $hotspotImage->setName('hotspot_image')
            ->setLabel('Hotspot Image')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getImage())
            ->addSubField($this->getDisplayHint())
            ->addSubField($this->getHotspot());

        return $hotspotImage;
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

        return $title;
    }

    private function getDescription()
    {
        $description = new MarkdownEditor(self::DESCRIPTION_KEY);
        $description->setLabel('Description')
            ->setName('description');

        return $description;
    }

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setPreviewSize('large')
            ->setReturnFormat('array')
            ->setLabel('Image')
            ->setName('image')
            ->setRequired(1);

        return $image;
    }

    private function getDisplayHint()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                'ordered' => 'ordered',
                'unordered' => 'unordered',
            ])
            ->setLabel('Display Format')
            ->setName('display_hint');

        return $displayHint;
    }

    private function getHotspot()
    {
        $hotspot = new Repeater(self::HOTSPOT_KEY);
        $hotspot->setLayout('block')
            ->setButtonLabel('Add Hotspot')
            ->addSubField($this->getHotspotTitle())
            ->addSubField($this->getHotspotDescription())
            ->addSubField($this->getHotspotCoordinates())
            ->setMin(1)
            ->setMax(0)
            ->setLabel('Hotspots')
            ->setName('hotspots')
            ->setRequired(1);

        return $hotspot;
    }

    private function getHotspotTitle()
    {
        $title = new Text(self::HOTSPOT_TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

        return $title;
    }

    private function getHotspotDescription()
    {
        $description = new MarkdownEditor(self::HOTSPOT_DESCRIPTION_KEY);
        $description->setConfig('simple')
            ->setLabel('Description')
            ->setName('description')
            ->setRequired(1);

        return $description;
    }

    private function getHotspotCoordinates()
    {
        $coordinate = new ImageHotspotCoordinate(self::HOTSPOT_COORDINATE_KEY);
        $coordinate->setLabel('Coordinates')
            ->setName('coordinates')
            ->setRequired(1);

        return $coordinate;
    }
}
