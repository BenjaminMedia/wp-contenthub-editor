<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Quote implements WidgetContract
{
    const KEY = 'layout_5bb315118c73b';
    const QUOTE_KEY = 'field_5bb315248c73c';
    const AUTHOR_KEY = 'field_5bb315e38c73d';

    public function getLayout(): ACFLayout
    {
        $quote = new ACFLayout(self::KEY);
        $quote->setName('quote')
            ->setLabel('Quote')
            ->addSubField($this->getQuote())
            ->addSubField($this->getAuthor());

        return $quote;
    }

    private function getQuote()
    {
        $quote = new Textarea(self::QUOTE_KEY);
        $quote->setRows(2)
            ->setLabel('Quote')
            ->setName('quote')
            ->setRequired(1);

        return $quote;
    }

    private function getAuthor()
    {
        $author = new Text(self::AUTHOR_KEY);
        $author->setLabel('Author')
            ->setName('author');

        return $author;
    }
}
