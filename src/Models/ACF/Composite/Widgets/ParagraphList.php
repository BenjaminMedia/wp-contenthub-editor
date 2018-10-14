<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Repeater;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class ParagraphList implements WidgetContract
{
    const KEY = 'layout_5bb4bd1afd048';
    const TITLE_KEY = 'field_5bb4bd2ffd049';
    const DESCRIPTION_KEY = 'field_5bb4bd38fd04a';
    const IMAGE_KEY = 'field_5bb4bd65fd04b';
    const DISPLAY_HINT_KEY = 'field_5bb4bd75fd04c';
    const ITEMS_KEY = 'field_5bb4be68fd04d';
    const CUSTOM_BULLET_KEY = 'field_5bb4c1bb8cdbc';
    const ITEM_TITLE_KEY = 'field_5bb4be86fd04e';
    const ITEM_DESCRIPTION_KEY = 'field_5bb4be91fd04f';
    const ITEM_IMAGE_KEY = 'field_5bb4bea5fd050';

    public function getLayout(): ACFLayout
    {
        $paragraphList = new ACFLayout(self::KEY);
        $paragraphList->setName('paragraph_list')
            ->setLabel('Paragraph List')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getImage())
            ->addSubField($this->getDisplayHint())
            ->addSubField($this->getItems());

        return $paragraphList;
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

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

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName('image');

        return $image;
    }

    private function getDisplayHint()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                'ordered' => 'Number List',
                'unordered' => 'Bullet List',
                'image' => 'Image List',
                'custom' => 'Custom List',
            ])
            ->setDefaultValue('ordered')
            ->setLabel('Display Format')
            ->setName('display_hint');

        return $displayHint;
    }

    private function getItems()
    {
        $items = new Repeater(self::ITEMS_KEY);
        $items->setLayout('row')
            ->setButtonLabel('Add item')
            ->addSubField($this->getCustomBullet())
            ->addSubField($this->getItemTitle())
            ->addSubField($this->getItemDescription())
            ->addSubField($this->getItemImage())
            ->setLabel('Items')
            ->setName('items');

        return $items;
    }

    private function getCustomBullet()
    {
        $customBullet = new Text(self::CUSTOM_BULLET_KEY);
        $customBullet->setLabel('Custom Bullet')
            ->setName('custom_bullet')
            ->setRequired(1)
            ->setConditionalLogic([
                [
                    [
                        'field' => self::DISPLAY_HINT_KEY,
                        'operator' => '==',
                        'value' => 'custom',
                    ],
                ],
            ]);

        return $customBullet;
    }

    private function getItemTitle()
    {
        $title = new Text(self::ITEM_TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title')
            ->setRequired(1);

        return $title;
    }

    private function getItemDescription()
    {
        $description = new MarkdownEditor(self::ITEM_DESCRIPTION_KEY);
        $description->setConfig('simple')
            ->setLabel('Description')
            ->setName('description')
            ->setRequired(1);

        return $description;
    }

    private function getItemImage()
    {
        $image = new Image(self::ITEM_IMAGE_KEY);
        $image->setLabel('Image')
            ->setName('image');

        return $image;
    }
}
