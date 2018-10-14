<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Select;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Link implements WidgetContract
{
    const KEY = '590b1798c8768';
    const URL_KEY = 'field_590b17c4c876a';
    const BUTTON_LABEL_KEY = 'field_590b179fc8769';
    const TARGET_KEY = 'field_590b17d4c876b';
    const LOCKED_KEY = 'field_5922be3e5cda5';

    public function getLayout(): ACFLayout
    {
        $link = new ACFLayout(self::KEY);
        $link->setName('link')
            ->setLabel('Link')
            ->addSubField($this->getUrl())
            ->addSubField($this->getButtonLabel())
            ->addSubField($this->getTarget())
            ->addSubField($this->getLockedContent());

        return $link;
    }

    private function getUrl()
    {
        $url = new Text(self::URL_KEY);
        $url->setLabel('URL')
            ->setName('url')
            ->setRequired(1);

        return $url;
    }

    private function getButtonLabel()
    {
        $label = new Text(self::BUTTON_LABEL_KEY);
        $label->setLabel('Button text')
            ->setName('title');

        return $label;
    }

    private function getTarget()
    {
        $target = new Select(self::TARGET_KEY);
        $target->setChoices([
                'Default' => 'Default For the Site',
                'Self' => 'Open in same window/tab',
                'Blank' => 'Open in a new tab',
                'Download' => 'Force download a file',
            ])
            ->setDefaultValue(['Default'])
            ->setLabel('Target')
            ->setName('target');

        return $target;
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
