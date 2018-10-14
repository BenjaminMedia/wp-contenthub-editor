<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeContentFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class Video implements WidgetContract
{
    const KEY = '58aaea63b12d2';
    const VIDEO_TEASER_IMAGE_FIELD = 'field_5a8d7ae021e44';
    const URL_KEY = 'field_5938fe71ed0bb';
    const CAPTION_KEY = 'field_58aaeb26b12d4';
    const LOCKED_KEY = 'field_5922be0e5cda4';

    public function getLayout(): ACFLayout
    {
        $video = new ACFLayout(self::KEY);
        $video->setName('video')
            ->setLabel('Video')
            ->addSubField($this->getTeaserImage())
            ->addSubField($this->getUrl())
            ->addSubField($this->getCaption())
            ->addSubField($this->getLockedContent());

        return $video;
    }

    private function getTeaserImage()
    {
        $teaserImage = new Checkbox(self::VIDEO_TEASER_IMAGE_FIELD);
        $teaserImage->setLabel('Teaser Image')
            ->setName('video_teaser_image')
            ->setInstructions('This will generate an image from the video
                                        and set it as a <b>teaser image</b> for the article.');

        return $teaserImage;
    }

    private function getUrl()
    {
        $url = new Text(self::URL_KEY);
        $url->setLabel('Embed Url')
            ->setName('embed_url')
            ->setInstructions('Paste the embed url from your video provider, supported providers are:
                                        Vimeo, YouTube, 23Video')
            ->setRequired(1);

        return $url;
    }

    private function getCaption()
    {
        $caption = new Textarea(self::CAPTION_KEY);
        $caption->setLabel('Caption')
            ->setName('caption');

        return $caption;
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
