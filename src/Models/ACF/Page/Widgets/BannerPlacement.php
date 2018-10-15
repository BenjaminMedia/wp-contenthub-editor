<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class BannerPlacement implements WidgetContract
{
    const KEY = 'layout_5bbc54cbcaf11';

    public function getLayout(): ACFLayout
    {
        $layout = new ACFLayout(self::KEY);
        $layout->setName(AcfName::WIDGET_BANNER_PLACEMENT)
            ->setLabel('Banner Placement');

        return $layout;
    }
}
