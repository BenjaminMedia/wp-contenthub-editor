<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class LeadParagraph implements WidgetContract
{
    const KEY = 'layout_5bbb614643179';
    const TITLE_KEY = 'field_5bbb61464317a';
    const DESCRIPTION_KEY = 'field_5bbb61464317b';
    const DISPLAY_HINT_KEY = 'field_5bbb61464317d';

    public function getLayout(): ACFLayout
    {
        $leadParagraph = new ACFLayout(self::KEY);
        $leadParagraph->setName('lead_paragraph')
            ->setLabel('Lead Paragraph')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getDisplayHint());

        return $leadParagraph;
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title')
            ->setRequired(1);

        return $title;
    }

    private function getDescription()
    {
        $description = new MarkdownEditor(self::DESCRIPTION_KEY);
        $description->setConfig('simple')
            ->setLabel('Description')
            ->setName('description');

        return $description;
    }

    private function getDisplayHint()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                'default' => 'Default',
                'inline' => 'Chapter',
            ])
            ->setDefaultValue('ordered')
            ->setLabel('Display Format')
            ->setName('display_hint');

        return $displayHint;
    }
}
