<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Repeater;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Gallery implements WidgetContract
{
    const KEY = '5a4f4dea1745f';
    const TITLE_KEY = 'field_5a952a1a811d2';
    const DESCRIPTION_KEY = 'field_5bbb153867a6f';
    const IMAGES_KEY = 'field_5a4f4dfd17460';
    const IMAGE_KEY = 'field_5a4f4e0f17461';
    const IMAGE_TITLE_KEY = 'field_5bbb151067a6e';
    const IMAGE_DESCRIPTION_KEY = 'field_5af2a0fcb1027';
    const LOCKED_KEY = 'field_5a4f4e5f17462';
    const DISPLAY_HINT_KEY = 'field_5af2a198b1028';

    public function getLayout(): array
    {
        $gallery = new ACFLayout(self::KEY);
        $gallery->setName('gallery')
            ->setLabel('Gallery')
            ->addSubField($this->getTitle())
            ->addSubField($this->getDescription())
            ->addSubField($this->getImages())
            ->addSubField($this->getLockedContent())
            ->addSubField($this->getDisplayHint());

        return $gallery->toArray();
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

        return $title->toArray();
    }

    private function getDescription()
    {
        $description = new MarkdownEditor(self::DESCRIPTION_KEY);
        $description->setLabel('Description')
            ->setName('description');

        return $description->toArray();
    }

    private function getImages()
    {
        $images = new Repeater(self::IMAGES_KEY);
        $images->setLayout('block')
            ->setButtonLabel('Add Image to Gallery')
            ->addSubField($this->getImage())
            ->addSubField($this->getImageTitle())
            ->addSubField($this->getImageDescription())
            ->setLabel('Images')
            ->setName('images')
            ->setRequired(1);

        return $images->toArray();
    }

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName('image')
            ->setRequired(1);

        return $image->toArray();
    }

    private function getImageTitle()
    {
        $title = new Text(self::IMAGE_TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

        return $title->toArray();
    }

    private function getImageDescription()
    {
        $description = new MarkdownEditor(self::IMAGE_DESCRIPTION_KEY);
        $description->setLabel('Description')
            ->setName('description');

        return $description->toArray();
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

        return $locked->toArray();
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

        return $displayHint->toArray();
    }
}
