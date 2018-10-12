<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Quote implements WidgetContract
{
    const KEY = 'layout_5bb315118c73b';
    const QUOTE_KEY = 'field_5bb315248c73c';
    const AUTHOR_KEY = 'field_5bb315e38c73d';

    public function getLayout(): array
    {
        $quote = new ACFLayout(self::KEY);
        $quote->setName('quote')
            ->setLabel('Quote')
            ->addSubField($this->getQuote())
            ->addSubField($this->getAuthor());

        return $quote->toArray();
    }

    private function getQuote()
    {
        return [
            'key' => self::QUOTE_KEY,
            'label' => 'Quote',
            'name' => 'quote',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 2,
            'new_lines' => '',
        ];
    }

    private function getAuthor()
    {
        return [
            'key' => self::AUTHOR_KEY,
            'label' => 'Author',
            'name' => 'author',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ];
    }
}
