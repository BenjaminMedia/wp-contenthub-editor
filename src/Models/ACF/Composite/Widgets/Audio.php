<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Audio implements WidgetContract
{
    const KEY = 'layout_5b6aee597180e';
    const FILE_KEY = 'field_5b6aee6a7180f';
    const TITLE_KEY = 'field_5b6bf0163e57d';
    const IMAGE_KEY = 'field_5b716358c2e60';

    public function getLayout(): array
    {
        return [
            'key' => self::KEY,
            'name' => 'audio',
            'label' => 'Audio',
            'display' => 'block',
            'sub_fields' => [
                $this->getFile(),
                $this->getTitle(),
                $this->getImage(),
            ],
            'min' => '',
            'max' => '',
        ];
    }

    private function getFile()
    {
        return [
            'key' => self::FILE_KEY,
            'label' => 'File',
            'name' => 'file',
            'type' => 'file',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'return_format' => 'id',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => '',
        ];
    }

    private function getTitle()
    {
        return [
            'key' => self::TITLE_KEY,
            'label' => 'Title',
            'name' => 'title',
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

    private function getImage()
    {
        return [
            'key' => self::IMAGE_KEY,
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'instructions' =>
                'picture shown on audio of the audio file.
                                            If not set, it\'ll default to the lead image.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'return_format' => 'id',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ];
    }
}
