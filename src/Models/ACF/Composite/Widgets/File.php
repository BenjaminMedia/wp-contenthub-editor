<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Repeater;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\File as FileField;

class File implements WidgetContract
{
    const KEY = '590aef9de4a5e';
    const CAPTION_KEY = 'field_590aefe3e4a5f';
    const FILE_KEY = 'field_590af026e4a61';
    const IMAGES_KEY = 'field_5921e5a83f4ea';
    const LOCKED_KEY = 'field_590af0eee4a62';
    const IMAGE_KEY = 'field_5921e94c3f4eb';
    const LABEL_KEY = 'field_59e49490911cf';

    public function getLayout(): array
    {
        $file = new ACFLayout(self::KEY);
        $file->setName('file')
            ->setLabel('File')
            ->addSubField($this->getCaption())
            ->addSubField($this->getFile())
            ->addSubField($this->getImages())
            ->addSubField($this->getLockedContent())
            ->addSubField($this->getLabel());

        return $file->toArray();
    }

    private function getCaption()
    {
        $caption = new Textarea(self::CAPTION_KEY);
        $caption->setLabel('Caption')
            ->setName('caption');

        return $caption->toArray();
    }

    private function getFile()
    {
        $file = new FileField(self::FILE_KEY);
        $file->setReturnFormat('array')
            ->setLabel('File')
            ->setName('file');
        return $file->toArray();
    }

    private function getImages()
    {
        $images = new Repeater(self::IMAGES_KEY);
        $images->setButtonLabel('Add Image')
            ->addSubField($this->getImage())
            ->setLabel('Images')
            ->setName('images')
            ->setRequired(1);

        return $images->toArray();
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

    private function getLabel()
    {
        $label = new Text(self::LABEL_KEY);
        $label->setLabel('Download Button Text (Optional)')
            ->setName('download_button_text')
            ->setInstructions('This will override the default button text.');

        return $label->toArray();
    }

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('File')
            ->setName('file')
            ->setRequired(1);

        return $image->toArray();
    }
}
