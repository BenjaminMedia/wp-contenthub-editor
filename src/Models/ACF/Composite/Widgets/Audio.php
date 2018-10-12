<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\File;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Audio implements WidgetContract
{
    const KEY = 'layout_5b6aee597180e';
    const FILE_KEY = 'field_5b6aee6a7180f';
    const TITLE_KEY = 'field_5b6bf0163e57d';
    const IMAGE_KEY = 'field_5b716358c2e60';

    public function getLayout(): array
    {
        $audio = new ACFLayout(self::KEY);
        $audio->setName('audio')
            ->setLabel('Audio')
            ->addSubField($this->getFile())
            ->addSubField($this->getTitle())
            ->addSubField($this->getImage());

        return $audio->toArray();
    }

    private function getFile()
    {
        $file = new File(self::FILE_KEY);
        $file->setLabel('File')
            ->setName('file')
            ->setRequired(1);

        return $file->toArray();
    }

    private function getTitle()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

        return $title->toArray();
    }

    private function getImage()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName('image')
            ->setInstructions('picture shown on audio of the audio file.
                                        If not set, it\'ll default to the lead image.');

        return $image->toArray();
    }
}
