<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use Bonnier\WP\ContentHub\Editor\ACF\CustomRelationship;
use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\FeaturedContent;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\TeaserList;

class WpPage
{
    public static function register()
    {
        add_action('plugins_loaded', [__CLASS__, 'registerPageWidgets']);
    }

    public static function registerPageWidgets()
    {
        if (function_exists('acf_add_local_field_group')) {
            CustomRelationship::register();

            acf_add_local_field_group([
                'key' => 'group_5bb31817b40e4',
                'title' => 'Page',
                'fields' => [
                    [
                        'key' => 'field_5bb318f2ffcef',
                        'label' => 'Page Widgets',
                        'name' => AcfName::GROUP_PAGE_WIDGETS,
                        'type' => 'flexible_content',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'layouts' => [
                            with(new FeaturedContent)->getLayout(),
                            with(new TeaserList)->getLayout(),
                        ],
                        'button_label' => 'Add Widget',
                        'min' => '',
                        'max' => '',
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'page',
                        ],
                    ],
                ],
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'seamless',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => [
                    0 => 'discussion',
                    1 => 'comments',
                    2 => 'revisions',
                    3 => 'featured_image',
                    4 => 'send-trackbacks',
                ],
                'active' => 1,
                'description' => '',
            ]);
        }
    }
}
