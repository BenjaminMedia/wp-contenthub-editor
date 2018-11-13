<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;
use Bonnier\WP\ContentHub\Editor\Models\ACF\WidgetContract;

class FeaturedContent extends BaseWidget
{
    public function __construct(array $config = [])
    {
        $config['minTeasers'] = 1;
        $config['maxTeasers'] = 1;
        $config['teaserCountDefault'] = 1;
        parent::__construct(AcfName::WIDGET_FEATURED_CONTENT, $config);
    }

    public function getLayout(): array
    {
        return [
            'key' => '5bbb23424c064',
            'name' => AcfName::WIDGET_FEATURED_CONTENT,
            'label' => 'Featured Content',
            'display' => 'block',
            'sub_fields' => array_merge([
                $this->getSettingsTab(),
                $this->getImage(),
                $this->getVideo(),
                $this->getDisplayHint(),
            ], $this->getSortByFields()),
            'min' => '',
            'max' => '',
        ];
    }

    private function getSettingsTab()
    {
        return [
            'key' => 'field_5bbb4363c6cc8',
            'label' => 'Settings',
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'placement' => 'top',
            'endpoint' => 0,
        ];
    }

    private function getImage()
    {
        return [
            'key' => 'field_5bbb41245b291',
            'label' => 'Image',
            'name' => AcfName::FIELD_IMAGE,
            'type' => 'image',
            'instructions' => 'Image is used as fallback if you video is selected. That\'s why it is required.',
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

    public function getVideo()
    {
        return [
            'key' => 'field_5bbb41625b292',
            'label' => 'Video',
            'name' => AcfName::FIELD_VIDEO,
            'type' => 'file',
            'instructions' => '',
            'required' => 0,
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
            'mime_types' => 'mp4',
        ];
    }

    private function getDisplayHint()
    {
        return [
            'key' => 'field_5bbb417a5b293',
            'label' => 'Display Format',
            'name' => AcfName::FIELD_DISPLAY_HINT,
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => [
                AcfName::DISPLAY_HINT_DEFAULT => 'Default',
                AcfName::DISPLAY_HINT_LEAD => 'Lead',
            ],
            'allow_null' => 0,
            'other_choice' => 0,
            'default_value' => AcfName::DISPLAY_HINT_DEFAULT,
            'layout' => 'vertical',
            'return_format' => 'value',
            'save_other_choice' => 0,
        ];
    }
}
