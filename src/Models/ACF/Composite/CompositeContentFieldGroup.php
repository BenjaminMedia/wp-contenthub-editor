<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

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

    public static function getFieldGroup()
    {
        return [
            'key' => self::KEY,
            'title' => 'Article Content',
            'fields' => [
                self::getArticleContent(),
                self::getLockedContent(),
                self::getRequiredUserRole(),
                self::getWidgets(),
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => WpComposite::POST_TYPE,
                    ],
                ],
            ],
            'menu_order' => 2,
            'position' => 'acf_after_title',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => [
                'slug',
                'categories',
                'author',
            ],
            'active' => 1,
            'description' => '',
        ];
    }

    private static function getArticleContent()
    {
        return [
            'key' => self::ARTICLE_CONTENT_KEY,
            'label' => 'Article Content',
            'name' => '',
            'type' => 'message',
            'instructions' => 'Click the add widget button to add content',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'message' => '<hr>',
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ];
    }

    private static function getLockedContent()
    {
        return [
            'key' => self::LOCKED_CONTENT_KEY,
            'label' => 'Locked Content',
            'name' => 'locked_content',
            'type' => 'true_false',
            'instructions' =>
                'Check this box if you want parts of the content to be locked.
                        Please note that you should mark each content item that you want to be locked,
                        by checking the "Locked Content" checkbox.',
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

    private static function getRequiredUserRole()
    {
        return [
            'key' => self::REQUIRED_ROLE_KEY,
            'label' => 'Required User Role',
            'name' => 'required_user_role',
            'type' => 'select',
            'instructions' => 'Select the role required to access the locked parts of the content',
            'required' => 0,
            'conditional_logic' => [
                [
                    [
                        'field' => self::LOCKED_CONTENT_KEY,
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
            'choices' => [
                'RegUser' => 'Registered User',
                'Subscriber' => 'Subscriber',
            ],
            'default_value' => [
            ],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
        ];
    }

    private static function getWidgets()
    {
        return [
            'key' => self::CONTENT_FIELD,
            'label' => 'Content (Widgets]',
            'name' => 'composite_content',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'button_label' => 'Add Widget',
            'min' => '',
            'max' => '',
            'layouts' => [
                with(new TextItem)->getLayout(),
                with(new Image)->getLayout(),
                with(new Audio)->getLayout(),
                with(new File)->getLayout(),
                with(new Video)->getLayout(),
                with(new Link)->getLayout(),
                with(new Gallery)->getLayout(),
                with(new InsertedCode)->getLayout(),
                with(new Infobox)->getLayout(),
                with(new LeadParagraph)->getLayout(),
                with(new ParagraphList)->getLayout(),
                with(new AssociatedComposite)->getLayout(),
                with(new Inventory)->getLayout(),
                with(new HotspotImage)->getLayout(),
                with(new Quote)->getLayout(),
            ],
        ];
    }
}
