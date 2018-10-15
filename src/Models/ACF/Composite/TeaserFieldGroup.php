<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\Widgets\Video;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Tab;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;

/**
 * Class MetaFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class TeaserFieldGroup
{
    const KEY = 'group_58e38d7eca92e';
    const TEASER_IMAGE_FIELD = 'field_58e38da2194e3';
    const SITE_TAB_KEY = 'field_5aeac69f22931';
    const TEASER_KEY = 'field_58e38d86194e2';
    const TEASER_DESCRIPTION_KEY = 'field_58e38dd0194e4';
    const SEO_TAB_KEY = 'field_5aeac72bfaaf1';
    const SEO_TEASER_TITLE_KEY = 'field_5aeac749faaf2';
    const SEO_IMAGE_KEY = 'field_5aeac77c8d9cf';
    const SEO_DESCRIPTION_KEY = 'field_5aeac79e8d9d0';
    const FACEBOOK_TAB_KEY = 'field_5aeac7e96eb57';
    const FACEBOOK_TITLE_KEY = 'field_5aeac8356eb58';
    const FACEBOOK_IMAGE_KEY = 'field_5aeac8476eb59';
    const FACEBOOK_DESCRIPTION_KEY = 'field_5aeac8546eb5a';
    const TWITTER_TAB_KEY = 'field_5aeac86e39537';
    const TWITTER_TITLE_KEY = 'field_5aeac87839538';
    const TWITTER_IMAGE_KEY = 'field_5aeac88039539';
    const TWITTER_DESCRIPTION_KEY = 'field_5aeac88c3953a';

    public static function register()
    {
        static::createACFFieldGroup();
        add_filter(
            'acf/validate_value/key=' . TeaserFieldGroup::TEASER_IMAGE_FIELD,
            [__CLASS__, 'validateTeaserImageField']
        );
    }

    private static function createACFFieldGroup()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(self::getFieldGroup());
        }
    }

    public static function validateTeaserImageField($valid)
    {
        // If not valid and a Video-Widget with Teaser-Image checked exists
        return $valid ?:
            collect($_POST['acf'][CompositeContentFieldGroup::CONTENT_FIELD])
                ->contains(Video::VIDEO_TEASER_IMAGE_FIELD, '1');
    }

    public static function getFieldGroup()
    {
        $fieldGroup = new ACFGroup(self::KEY);
        $fieldGroup->setTitle('Teasers')
            ->addField(self::getSiteTeaserTab())
            ->addField(self::getTeaserTitle())
            ->addField(self::getTeaserImage())
            ->addField(self::getTeaserDescription())
            ->addField(self::getSEOTeaserTab())
            ->addField(self::getSEOTeaserTitle())
            ->addField(self::getSEOTeaserImage())
            ->addField(self::getSEOTeaserDescription())
            ->addField(self::getFacebookTeaserTab())
            ->addField(self::getFacebookTeaserTitle())
            ->addField(self::getFacebookTeaserImage())
            ->addField(self::getFacebookTeaserDescription())
            ->addField(self::getTwitterTeaserTab())
            ->addField(self::getTwitterTeaserTitle())
            ->addField(self::getTwitterTeaserImage())
            ->addField(self::getTwitterTeaserDescription())
            ->setLocation([
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'contenthub_composite',
                    ],
                ],
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ],
                ],
            ])
            ->setMenuOrder(1)
            ->setPosition('acf_after_title')
            ->setStyle('seamless')
            ->setActive(1);

        return $fieldGroup->toArray();
    }

    private static function getSiteTeaserTab()
    {
        $tab = new Tab(self::SITE_TAB_KEY);
        $tab->setLabel('Site Teaser (Eg. article teasers, search results, cxense etc.)');
        return $tab;
    }

    private static function getTeaserTitle()
    {
        $title = new Text(self::TEASER_KEY);
        $title->setLabel('Teaser Title')
            ->setName('teaser_title')
            ->setRequired(1);

        return $title;
    }

    private static function getTeaserImage()
    {
        $image = new Image(self::TEASER_IMAGE_FIELD);
        $image->setLabel('Teaser Image')
            ->setName('teaser_image')
            ->setRequired(1);

        return $image;
    }

    private static function getTeaserDescription()
    {
        $description = new Textarea(self::TEASER_DESCRIPTION_KEY);
        $description->setLabel('Teaser Description')
            ->setName('teaser_description')
            ->setRequired(1);

        return $description;
    }

    private static function getSEOTeaserTab()
    {
        $tab = new Tab(self::SEO_TAB_KEY);
        $tab->setLabel('SEO Teaser (Google)');

        return $tab;
    }

    private static function getSEOTeaserTitle()
    {
        $title = new Text(self::SEO_TEASER_TITLE_KEY);
        $title->setLabel('Teaser Title')
            ->setName('seo_teaser_title');

        return $title;
    }

    private static function getSEOTeaserImage()
    {
        $image = new Image(self::SEO_IMAGE_KEY);
        $image->setLabel('Teaser Image')
            ->setName('seo_teaser_image');

        return $image;
    }

    private static function getSEOTeaserDescription()
    {
        $description = new Textarea(self::SEO_DESCRIPTION_KEY);
        $description->setLabel('Teaser Description')
            ->setName('seo_teaser_description');

        return $description;
    }

    private static function getFacebookTeaserTab()
    {
        $tab = new Tab(self::FACEBOOK_TAB_KEY);
        $tab->setLabel('Facebook Teaser');

        return $tab;
    }

    private static function getFacebookTeaserTitle()
    {
        $title = new Text(self::FACEBOOK_TITLE_KEY);
        $title->setLabel('Teaser Title')
            ->setName('fb_teaser_title');

        return $title;
    }

    private static function getFacebookTeaserImage()
    {
        $image = new Image(self::FACEBOOK_IMAGE_KEY);
        $image->setLabel('Teaser Image')
            ->setName('fb_teaser_image');

        return $image;
    }

    private static function getFacebookTeaserDescription()
    {
        $description = new Textarea(self::FACEBOOK_DESCRIPTION_KEY);
        $description->setLabel('Teaser Description')
            ->setName('fb_teaser_description');

        return $description;
    }

    private static function getTwitterTeaserTab()
    {
        $tab = new Tab(self::TWITTER_TAB_KEY);
        $tab->setLabel('Twitter Teaser');

        return $tab;
    }

    private static function getTwitterTeaserTitle()
    {
        $title = new Text(self::TWITTER_TITLE_KEY);
        $title->setLabel('Teaser Title')
            ->setName('tw_teaser_title');

        return $title;
    }

    private static function getTwitterTeaserImage()
    {
        $image = new Image(self::TWITTER_IMAGE_KEY);
        $image->setLabel('Teaser Image')
            ->setName('tw_teaser_image');

        return $image;
    }

    private static function getTwitterTeaserDescription()
    {
        $description = new Textarea(self::TWITTER_DESCRIPTION_KEY);
        $description->setLabel('Teaser Description')
            ->setName('tw_teaser_description');

        return $description;
    }
}
