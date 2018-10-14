<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Repeater;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Inventory implements WidgetContract
{
    const KEY = '58aeadaacbe5c';
    const TITLE_KEY = 'field_58e3971e4d277';
    const INVENTORY_KEY = 'field_58aeadcdcbe5d';
    const NAME_KEY = 'field_58aeae3fcbe61';
    const VALUE_KEY = 'field_58aeae4ccbe62';
    const LOCKED_KEY = 'field_5922be6d5cda7';

    public function getLayout(): ACFLayout
    {
        $inventory = new ACFLayout(self::KEY);
        $inventory->setName('inventory')
            ->setLabel('Inventory')
            ->addSubField($this->getTitle())
            ->addSubField($this->getInventory())
            ->addSubField($this->getLockedContent());

        return $inventory;
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title')
            ->setRequired(1);

        return $title;
    }

    private function getInventory()
    {
        $inventory = new Repeater(self::INVENTORY_KEY);
        $inventory->setButtonLabel('Add Row')
            ->addSubField($this->getName())
            ->addSubField($this->getValue())
            ->setLabel('Inventory Items')
            ->setName('inventory_items');

        return $inventory;
    }

    private function getName()
    {
        $name = new Text(self::NAME_KEY);
        $name->setLabel('Name')
            ->setName('name')
            ->setRequired(1);

        return $name;
    }

    private function getValue()
    {
        $value = new Text(self::VALUE_KEY);
        $value->setLabel('Value')
            ->setName('value');

        return $value;
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
