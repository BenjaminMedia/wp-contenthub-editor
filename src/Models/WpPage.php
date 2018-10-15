<?php

namespace Bonnier\WP\ContentHub\Editor\Models;

use Bonnier\WP\ContentHub\Editor\ACF\CustomRelationship;
use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\FlexibleContent;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\BannerPlacement;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\FeaturedContent;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\Newsletter;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\SeoText;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\TaxonomyList;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Page\Widgets\TeaserList;

class WpPage
{
    const KEY = 'group_5bb31817b40e4';
    const WIDGETS_KEY = 'field_5bb318f2ffcef';

    public static function register()
    {
        add_action('plugins_loaded', [__CLASS__, 'registerPageWidgets']);
    }

    public static function registerPageWidgets()
    {
        if (function_exists('acf_add_local_field_group')) {
            CustomRelationship::register();

            acf_add_local_field_group(self::getPageGroup());
        }
    }

    public static function getPageGroup()
    {
        $pageGroup = new ACFGroup(self::KEY);
        $pageGroup->setTitle('Page')
            ->setFields(collect([self::getPageWidgets()]))
            ->setLocation([
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ],
                ],
            ])
            ->setPosition('normal')
            ->setStyle('seamless')
            ->setHideOnScreen([
                'discussion',
                'comments',
                'revisions',
                'featured_image',
                'send-trackbacks',
            ])
            ->setActive(1);

        return $pageGroup->toArray();
    }

    public static function getPageWidgets()
    {
        $widgets = new FlexibleContent(self::WIDGETS_KEY);
        $widgets
            ->addLayout(new FeaturedContent)
            ->addLayout(new TeaserList)
            ->addLayout(new SeoText)
            ->addLayout(new TaxonomyList)
            ->addLayout(new Newsletter)
            ->addLayout(new BannerPlacement)
            ->setButtonLabel('Add Widget')
            ->setLabel('Page Widgets')
            ->setName(AcfName::GROUP_PAGE_WIDGETS);

        return $widgets;
    }
}
