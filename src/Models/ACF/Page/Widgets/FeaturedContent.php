<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\File;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Tab;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class FeaturedContent extends BaseWidget
{
    const KEY = '5bbb23424c064';
    const SETTINGS_KEY = 'field_5bbb4363c6cc8';
    const IMAGE_KEY = 'field_5bbb41245b291';
    const VIDEO_KEY = 'field_5bbb41625b292';
    const DISPLAY_HINT_KEY = 'field_5bbb417a5b293';

    public function __construct(array $config = [])
    {
        $config['minTeasers'] = 1;
        $config['maxTeasers'] = 1;
        $config['teaserCountDefault'] = 1;
        parent::__construct(AcfName::WIDGET_FEATURED_CONTENT, $config);
    }

    public function getLayout(): ACFLayout
    {
        $fields = collect([
            $this->getSettingsTab(),
            $this->getImage(),
            $this->getVideo(),
            $this->getDisplayHint()
        ])->merge($this->getSortByFields());

        $layout = new ACFLayout(self::KEY);
        $layout->setName(AcfName::WIDGET_FEATURED_CONTENT)
            ->setLabel('Featured Content')
            ->setSubFields($fields);

        return $layout;
    }

    private function getSettingsTab()
    {
        $tab = new Tab(self::SETTINGS_KEY);
        $tab->setLabel('Settings');

        return $tab;
    }

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName(AcfName::FIELD_IMAGE);

        return $image;
    }

    public function getVideo()
    {
        $video = new File(self::VIDEO_KEY);
        $video->setMimeTypes('mp4')
            ->setLabel('Video')
            ->setName(AcfName::FIELD_VIDEO);

        return $video;
    }

    private function getDisplayHint()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                AcfName::DISPLAY_HINT_DEFAULT => 'Default',
                AcfName::DISPLAY_HINT_LEAD => 'Lead'
            ])
            ->setDefaultValue(AcfName::DISPLAY_HINT_DEFAULT)
            ->setLabel('Display Format')
            ->setName(AcfName::FIELD_DISPLAY_HINT);

        return $displayHint;
    }
}
