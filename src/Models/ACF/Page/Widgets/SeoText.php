<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFLayout;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\BaseWidget;

class SeoText extends BaseWidget
{
    const KEY = 'layout_5bbc54bacaf10';
    const TITLE_KEY = 'field_5bbc5050c8158';
    const DESCRIPTION_KEY = 'field_5bbc5058c8159';
    const IMAGE_KEY = 'field_5bbc505fc815a';

    public function __construct(array $config = [])
    {
        parent::__construct(AcfName::WIDGET_SEO_TEXT, $config);
    }

    public function getLayout(): ACFLayout
    {
        $layout = new ACFLayout(self::KEY);
        $layout->setName(AcfName::WIDGET_SEO_TEXT)
            ->setLabel('SEO Text')
            ->addSubField($this->getTitleField())
            ->addSubField($this->getDescriptionField())
            ->addSubField($this->getImageField());

        return $layout;
    }

    private function getTitleField()
    {
        $title = new Text(self::TITLE_KEY);
        $title->setLabel('Title')
            ->setName(AcfName::FIELD_TITLE)
            ->setRequired(1);

        return $title;
    }

    private function getDescriptionField()
    {
        $description = new MarkdownEditor(self::DESCRIPTION_KEY);
        $description->setLabel('Description')
            ->setName(AcfName::FIELD_DESCRIPTION);

        return $description;
    }

    private function getImageField()
    {
        $image = new Image(self::IMAGE_KEY);
        $image->setLabel('Image')
            ->setName(AcfName::FIELD_IMAGE);

        return $image;
    }
}
