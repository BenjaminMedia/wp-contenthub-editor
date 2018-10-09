<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Newsletter implements WidgetContract
{

    public function getLayout(): array
    {
        return [
            'key' => 'layout_5bbc54bacaf10',
            'name' => AcfName::WIDGET_NEWSLETTER,
            'label' => 'Newsletter',
            'display' => 'block',
            'sub_fields' => [],
            'min' => '',
            'max' => '',
        ];
    }
}
