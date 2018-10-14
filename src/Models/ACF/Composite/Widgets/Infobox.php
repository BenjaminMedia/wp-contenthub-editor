<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Infobox implements WidgetContract
{
    const KEY = '58aae479d3958';
    const TITLE_KEY = 'field_58aae4b6809c7';
    const BODY_KEY = 'field_58aae4d4809c8';
    const LOCKED_KEY = 'field_5922bdd55cda2';

    public function getLayout(): ACFLayout
    {
        $infobox = new ACFLayout(self::KEY);
        $infobox->setName('infobox')
            ->setLabel('Infobox')
            ->addSubField($this->getTitle())
            ->addSubField($this->getBody())
            ->addSubField($this->getLockedContent());

        return $infobox;
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title')
            ->setRequired(1);

        return $title;
    }

    private function getBody()
    {
        $body = new MarkdownEditor(self::BODY_KEY);
        $body->setConfig('simple')
            ->setLabel('Body')
            ->setName('body')
            ->setRequired(1);

        return $body;
    }

    private function getLockedContent()
    {
        $locked = new Checkbox(self::LOCKED_KEY);
        $locked->setLabel('Locked Content')
            ->setName('locked_content')
            ->setConditionalLogic([
                [
                    [
                        'field' => CompositeContentFieldGroup::LOCKED_CONTENT_KEY,
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ]);

        return $locked;
    }
}
