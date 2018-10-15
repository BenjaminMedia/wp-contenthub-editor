<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Newsletter implements WidgetContract
{
    const KEY = 'layout_5bbc54bacaf10';

    public function getLayout(): ACFLayout
    {
        $layout = new ACFLayout(self::KEY);
        $layout->setName(AcfName::WIDGET_NEWSLETTER)
            ->setLabel('Newsletter');

        return $layout;
    }
}
