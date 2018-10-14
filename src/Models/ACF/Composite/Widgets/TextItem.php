<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class TextItem implements WidgetContract
{
    const KEY = '58aae53c26608';
    const BODY_KEY = 'field_58aae55326609';

    public function getLayout(): ACFLayout
    {
        $textItem = new ACFLayout(self::KEY);
        $textItem->setName('text_item')
            ->setLabel('Text')
            ->addSubField($this->getBody());

        return $textItem;
    }

    private function getBody()
    {
        $body = new MarkdownEditor(self::BODY_KEY);
        $body->setLabel('Body')
            ->setName('body')
            ->setRequired(1);

        return $body;
    }
}
