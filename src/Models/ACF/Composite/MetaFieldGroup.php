<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Checkbox;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\File;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Group;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Image;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Select;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\URL;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class MetaFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class MetaFieldGroup
{
    const KEY = 'group_58fde819ea0f1';

    const COMMERCIAL_TYPES = [
        'Advertorial' => 'Advertorial',
        'AffiliatePartner' => 'AffiliatePartner',
        'CommercialContent' => 'CommercialContent',
        'Offer' => 'Offer',
    ];
    const CANONICAL_KEY = 'field_5af188bbb5b45';
    const COMMERCIAL_KEY = 'field_58fde84d034e4';
    const COMMERCIAL_TYPE_KEY = 'field_58fde876034e5';
    const COMMERCIAL_LOGO_KEY = 'field_5a8d72d39ee48';
    const INTERNAL_COMMENT_KEY = 'field_58fde876034e6';
    const MAGAZINE_YEAR_KEY = 'field_58f5febf3cb9c';
    const MAGAZINE_ISSUE_KEY = 'field_58e3878b2dc76';
    const AUDIO_KEY = 'field_5bb4abe52f3d7';
    const AUDIO_FILE_KEY = 'field_5bb4ac792f3d8';
    const AUDIO_TITLE_KEY = 'field_5bb4aca02f3d9';
    const AUDIO_THUMBNAIL_KEY = 'field_5bb4acb12f3da';

    public static function register()
    {
        static::create_acf_field_group();
        static::register_translations();
    }

    public static function getFieldGroup()
    {
        $fieldGroup = new ACFGroup(self::KEY);
        $fieldGroup->setTitle('Meta')
            ->addField(self::getCanonicalURL())
            ->addField(self::getCommercial())
            ->addField(self::getCommercialType())
            ->addField(self::getCommercialLogo())
            ->addField(self::getInternalComment())
            ->addField(self::getMagazineYear())
            ->addField(self::getMagazineIssue())
            ->addField(self::getAudio())
            ->setLocation([
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => WpComposite::POST_TYPE,
                    ],
                ],
            ])
            ->setMenuOrder(4)
            ->setPosition('side')
            ->setStyle('default')
            ->setActive(1);

        return $fieldGroup->toArray();
    }

    public static function getIssues()
    {
        $issues = array_map(function ($issue) {
            if ($issue <= 9) {
                return '0' . $issue;
            }
            return $issue;
        }, range(1, 18));
        return array_combine($issues, $issues);
    }

    public static function getYears()
    {
        $years = array_reverse(range(1980, date("Y") + 1));
        return array_combine($years, $years);
    }

    private static function getCanonicalURL()
    {
        $canonical = new URL(self::CANONICAL_KEY);
        $canonical->setLabel('Canonical URL')
            ->setName('canonical_url');

        return $canonical;
    }

    private static function getCommercial()
    {
        $commercial = new Checkbox(self::COMMERCIAL_KEY);
        $commercial->setLabel('Commercial')
            ->setName('commercial');

        return $commercial;
    }

    private static function getCommercialType()
    {
        $type = new Select(self::COMMERCIAL_TYPE_KEY);
        $type->setChoices(self::COMMERCIAL_TYPES)
            ->setAllowNull(1)
            ->setLabel('Commercial Type')
            ->setName('commercial_type')
            ->setConditionalLogic([
                [
                    [
                        'field' => self::COMMERCIAL_KEY,
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ]);

        return $type;
    }

    private static function getCommercialLogo()
    {
        $logo = new Image(self::COMMERCIAL_LOGO_KEY);
        $logo->setLabel('Commercial logo')
            ->setName('commercial_logo')
            ->setConditionalLogic([
                [
                    [
                        'field' => self::COMMERCIAL_KEY,
                        'operator' => '==',
                        'value' => '1',
                    ],
                ],
            ]);

        return $logo;
    }

    private static function getInternalComment()
    {
        $comment = new Textarea(self::INTERNAL_COMMENT_KEY);
        $comment->setRows(3)
            ->setLabel('Internal Comment')
            ->setName('internal_comment');

        return $comment;
    }

    private static function getMagazineYear()
    {
        $magazineYear = new Select(self::MAGAZINE_YEAR_KEY);
        $magazineYear->setChoices(self::getYears())
            ->setAllowNull(1)
            ->setLabel('Magazine Year')
            ->setName('magazine_year')
            ->setInstructions('The magazine year ie. 2017 if the article was published in 2017');

        return $magazineYear;
    }

    private static function getMagazineIssue()
    {
        $issue = new Select(self::MAGAZINE_ISSUE_KEY);
        $issue->setChoices(self::getIssues())
            ->setAllowNull(1)
            ->setLabel('Magazine Issue')
            ->setName('magazine_issue')
            ->setInstructions('The magazine issue ie. 01 for the first issue of a given year');

        return $issue;
    }

    private static function getAudio()
    {
        $audio = new Group(self::AUDIO_KEY);
        $audio->addSubField(self::getAudioFile())
            ->addSubField(self::getAudioTitle())
            ->addSubField(self::getAudioThumbnail())
            ->setLabel('Audio')
            ->setName('audio');

        return $audio;
    }

    private static function getAudioFile()
    {
        $file = new File(self::AUDIO_FILE_KEY);
        $file->setLabel('File')
            ->setName('file');

        return $file;
    }

    private static function getAudioTitle()
    {
        $title = new Text(self::AUDIO_TITLE_KEY);
        $title->setLabel('Title')
            ->setName('title');

        return $title;
    }

    private static function getAudioThumbnail()
    {
        $thumbnail = new Image(self::AUDIO_THUMBNAIL_KEY);
        $thumbnail->setLabel('Audio Thumbnail')
            ->setName('audio_thumbnail');

        return $thumbnail;
    }

    private static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(self::getFieldGroup());
        }
    }

    private static function register_translations()
    {
        collect(static::COMMERCIAL_TYPES)->each(function ($commercialType) {
            LanguageProvider::registerStringTranslation($commercialType, $commercialType, 'content-hub-editor');
        });
    }
}
