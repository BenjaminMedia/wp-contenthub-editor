<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Video implements WidgetContract
{
    const KEY = '58aaea63b12d2';
    const VIDEO_TEASER_IMAGE_FIELD = 'field_5a8d7ae021e44';
    const URL_KEY = 'field_5938fe71ed0bb';
    const CAPTION_KEY = 'field_58aaeb26b12d4';
    const LOCKED_KEY = 'field_5922be0e5cda4';

    public function getLayout(): array
    {
        $video = new ACFLayout(self::KEY);
        $video->setName('video')
            ->setLabel('Video')
            ->addSubField($this->getTeaserImage())
            ->addSubField($this->getUrl())
            ->addSubField($this->getCaption())
            ->addSubField($this->getLockedContent());

        return $video->toArray();
    }

    private function getTeaserImage()
    {
        return [
            'key' => static::VIDEO_TEASER_IMAGE_FIELD,
            'label' => 'Teaser Image',
            'name' => 'video_teaser_image',
            'type' => 'true_false',
            'instructions' =>
                'This will generate an image from the video
                                        and set it as a <b>teaser image</b> for the article.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ];
    }

    private function getUrl()
    {
        return [
            'key' => self::URL_KEY,
            'label' => 'Embed Url',
            'name' => 'embed_url',
            'type' => 'text',
            'instructions' =>
                'Paste the embed url from your video provider, supported providers are:
                                        Vimeo, YouTube, 23Video',
            'required' => 1,
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

    private function getCaption()
    {
        return [
            'key' => self::CAPTION_KEY,
            'label' => 'Caption',
            'name' => 'caption',
            'type' => 'textarea',
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
            'maxlength' => '',
            'rows' => '',
            'new_lines' => '',
        ];
    }

    private function getLockedContent()
    {
        return [
            'key' => self::LOCKED_KEY,
            'label' => 'Locked Content',
            'name' => 'locked_content',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => CompositeContentFieldGroup::LOCKED_CONTENT_KEY,
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ];
    }
}
