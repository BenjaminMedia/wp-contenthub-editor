<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\AssociatedComposite;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Audio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\File;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Gallery;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\HotspotImage;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Infobox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\InsertedCode;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Inventory;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\LeadParagraph;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Link;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\ParagraphList;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Quote;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\TextItem;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Video;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\FlexibleContent;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Message;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Select;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class CompositeContentFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class CompositeContentFieldGroup
{
    const KEY = 'group_5af991aeda261';
    const CONTENT_FIELD = 'field_58aae476809c6';
    const LOCKED_CONTENT_KEY = 'field_5921f0c676974';
    const REQUIRED_ROLE_KEY = 'field_5921f0e576975';
    const ARTICLE_CONTENT_KEY = 'field_5afa811fbf221';

    public static function register()
    {
        static::createACFFieldGroup();
    }

    public static function createACFFieldGroup()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(self::getFieldGroup());
        }
    }

    public static function getFieldGroup(): array
    {
        $fieldGroup = new ACFGroup(self::KEY);
        $fieldGroup->setTitle('Article Content')
            ->addField(self::getArticleContent())
            ->addField(self::getLockedContent())
            ->addField(self::getRequiredUserRole())
            ->addField(self::getWidgets())
            ->setLocation([
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => WpComposite::POST_TYPE,
                    ],
                ],
            ])
            ->setMenuOrder(2)
            ->setPosition('acf_after_title')
            ->setStyle('seamless')
            ->setHideOnScreen(['slug', 'categories', 'author'])
            ->setActive(1);

        return $fieldGroup->toArray();
    }

    private static function getArticleContent(): Message
    {
        $content = new Message(self::ARTICLE_CONTENT_KEY);
        $content->setMessage('<hr>')
            ->setNewLines('wpautop')
            ->setLabel('Article Content')
            ->setInstructions('Click the add widget button to add content');

        return $content;
    }

    private static function getLockedContent(): Checkbox
    {
        $locked = new Checkbox(self::LOCKED_CONTENT_KEY);
        $locked->setLabel('Locked Content')
            ->setName('locked_content')
            ->setInstructions('Check this box if you want parts of the content to be locked.
                        Please note that you should mark each content item that you want to be locked,
                        by checking the "Locked Content" checkbox.');

        return $locked;
    }

    private static function getRequiredUserRole(): Select
    {
        $requiredUserRole = new Select(self::REQUIRED_ROLE_KEY);
        $requiredUserRole->setChoices([
                'RegUser' => 'Registered User',
                'Subscriber' => 'Subscriber',
            ])
            ->setLabel('Required User Role')
            ->setName('required_user_role')
            ->setInstructions('Select the role required to access the locked parts of the content')
            ->setConditionalLogic([
                [
                    [
                        'field' => self::LOCKED_CONTENT_KEY,
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ]);

        return $requiredUserRole;
    }

    private static function getWidgets(): FlexibleContent
    {
        $widgets = new FlexibleContent(self::CONTENT_FIELD);
        $widgets->setButtonLabel('Add Widget')
            ->addLayout(new TextItem)
            ->addLayout(new Image)
            ->addLayout(new Audio)
            ->addLayout(new File)
            ->addLayout(new Video)
            ->addLayout(new Link)
            ->addLayout(new Gallery)
            ->addLayout(new InsertedCode)
            ->addLayout(new Infobox)
            ->addLayout(new LeadParagraph)
            ->addLayout(new ParagraphList)
            ->addLayout(new AssociatedComposite)
            ->addLayout(new Inventory)
            ->addLayout(new HotspotImage)
            ->addLayout(new Quote)
            ->setLabel('Content (Widgets)')
            ->setName('composite_content')
            ->setRequired(1);

        return $widgets;
    }
}
