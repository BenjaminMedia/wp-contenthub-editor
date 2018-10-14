<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class InsertedCode implements WidgetContract
{
    const KEY = '58aae89d0f005';
    const CODE_KEY = 'field_58aae8b00f006';
    const LOCKED_KEY = 'field_5922bdbd5cda1';

    public function getLayout(): ACFLayout
    {
        $insertedCode = new ACFLayout(self::KEY);
        $insertedCode->setName('inserted_code')
            ->setLabel('Inserted Code')
            ->addSubField($this->getCode())
            ->addSubField($this->getLockedContent());

        return $insertedCode;
    }

    private function getCode()
    {
        $code = new Textarea(self::CODE_KEY);
        $code->setLabel('Code')
            ->setName('code');

        return $code;
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
