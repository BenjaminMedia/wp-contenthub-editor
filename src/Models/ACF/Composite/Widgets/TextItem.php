<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class TextItem implements WidgetContract
{
    const KEY = '58aae53c26608';
    const BODY_KEY = 'field_58aae55326609';

    public function getLayout(): array
    {
        $textItem = new ACFLayout(self::KEY);
        $textItem->setName('text_item')
            ->setLabel('Text')
            ->addSubField($this->getBody());

        return $textItem->toArray();
    }

    private function getBody()
    {
        return [
            'key' => self::BODY_KEY,
            'label' => 'Body',
            'name' => 'body',
            'type' => 'markdown-editor',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'simple_mde_config' => 'standard',
            'font_size' => 14,
        ];
    }
}
