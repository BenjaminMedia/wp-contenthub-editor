<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Meta;

use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Sitemap implements WidgetContract
{
    const KEY = 'field_5bfe50afe902e';
    const NAME = 'sitemap';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'label' => 'Display in Sitemaps',
            'name' => self::NAME,
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'message' => 'Should this page be displayed on Sitemaps?',
            'default_value' => 1,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ];
    }
}
