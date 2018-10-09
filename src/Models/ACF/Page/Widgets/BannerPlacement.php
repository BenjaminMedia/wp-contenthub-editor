<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class BannerPlacement implements WidgetContract
{

    public function getLayout(): array
    {
        return [
            'key' => 'layout_5bbc54cbcaf11',
            'name' => AcfName::WIDGET_BANNER_PLACEMENT,
            'label' => 'Banner Placement',
            'display' => 'block',
            'sub_fields' => [],
            'min' => '',
            'max' => '',
        ];
    }
}
