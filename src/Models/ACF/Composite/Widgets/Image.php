<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image as ImageField;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\URL;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Image implements WidgetContract
{
    const KEY = '58aaef9fb02bc';
    const LEAD_IMAGE_KEY = 'field_5908407c246cb';
    const FILE_KEY = 'field_58aaf042b02c1';
    const LOCKED_KEY = 'field_5922bd8e5cd9e';
    const LINK_KEY = 'field_5ba0c550e9e5f';
    const TARGET_KEY = 'field_5ba0c558e9e60';
    const RELATIONSHIP_KEY = 'field_5ba0c574e9e61';
    const DISPLAY_HINT_KEY = 'field_5bb4a00b2aa05';

    public function getLayout(): ACFLayout
    {
        $image = new ACFLayout(self::KEY);
        $image->setName('image')
            ->setLabel('Image')
            ->addSubField($this->getLeadImage())
            ->addSubField($this->getFile())
            ->addSubField($this->getLockedContent())
            ->addSubField($this->getLink())
            ->addSubField($this->getTarget())
            ->addSubField($this->getRelationship())
            ->addSubField($this->getDisplayHint());

        return $image;
    }

    private function getLeadImage()
    {
        $leadImage = new Checkbox(self::LEAD_IMAGE_KEY);
        $leadImage->setLabel('Lead Image')
            ->setName('lead_image');

        return $leadImage;
    }

    private function getFile()
    {
        $image = new ImageField(self::FILE_KEY);
        $image->setLabel('File')
            ->setName('file')
            ->setRequired(1);

        return $image;
    }

    private function getLockedContent()
    {
        $locked = new Checkbox(self::LOCKED_KEY);
        $locked->setLabel('Locked Content')
            ->setName('locked_content')
            ->setConditionalLogic([
                [
                    [
                        'field' => 'field_5921f0c676974',
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ]);

        return $locked;
    }

    private function getLink()
    {
        $link = new URL(self::LINK_KEY);
        $link->setLabel('Link')
            ->setName('link');

        return $link;
    }

    private function getTarget()
    {
        $target = new Radio(self::TARGET_KEY);
        $target->setChoices([
                '_self' => 'Same window',
                '_blank' => 'New window'
            ])
            ->setDefaultValue('_self')
            ->setLabel('Open in')
            ->setName('target')
            ->setConditionalLogic([
                [
                    [
                        'field' => self::LINK_KEY,
                        'operator' => '!=empty',
                    ]
                ]
            ]);

        return $target;
    }

    private function getRelationship()
    {
        $relationship = new Radio(self::RELATIONSHIP_KEY);
        $relationship->setChoices([
                'follow' => 'Follow',
                'nofollow' => 'No Follow',
            ])
            ->setDefaultValue('follow')
            ->setLabel('Relationship (Follow / No Follow)')
            ->setName('rel')
            ->setConditionalLogic([
                [
                    [
                        'field' => self::LINK_KEY,
                        'operator' => '!=empty',
                    ],
                ],
            ]);

        return $relationship;
    }

    private function getDisplayHint()
    {
        $displayHint = new Radio(self::DISPLAY_HINT_KEY);
        $displayHint->setChoices([
                'default' => 'Default',
                'inline' => 'Inline',
            ])
            ->setDefaultValue('default')
            ->setLabel('Display Format')
            ->setName('display_hint');

        return $displayHint;
    }
}
