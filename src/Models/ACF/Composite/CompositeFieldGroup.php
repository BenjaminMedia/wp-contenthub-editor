<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;

use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Models\ACF\ACFGroup;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Radio;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Taxonomy;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Text;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\Textarea;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Fields\User;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class CompositeFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class CompositeFieldGroup
{
    const KEY = 'group_58abfd3931f2f';
    const AUTHOR_KEY = 'field_5af9888b4b7a1';
    const KIND_KEY = 'field_58e388862daa8';
    const DESCRIPTION_KEY = 'field_58abfebd21b82';
    const AUTHOR_DESCRIPTION_KEY = 'field_5a8d44d026528';
    const CATEGORY_KEY = 'field_58e39a7118284';
    const TAG_KEY = 'field_58f606b6e1fb0';

    public static function register()
    {
        static::create_acf_field_group();
        static::register_author_hooks();
    }

    private static function create_acf_field_group()
    {
        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(self::getFieldGroup());
        endif;
    }

    public static function getFieldGroup()
    {
        $fieldGroup = new ACFGroup(self::KEY);
        $fieldGroup->setTitle('Composite Fields')
            ->addField(self::getKind())
            ->addField(self::getDescription())
            ->addField(self::getAuthor())
            ->addField(self::getAuthorDescription())
            ->addField(self::getCategory())
            ->addField(self::getTag())
            ->setLocation([
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => WpComposite::POST_TYPE,
                    ],
                ],
            ])
            ->setPosition('acf_after_title')
            ->setStyle('seamless')
            ->setHideOnScreen(['slug', 'categories', 'author'])
            ->setActive(1);

        return $fieldGroup->toArray();
    }

    private static function register_author_hooks()
    {
        add_filter('acf/load_value/key=' . self::AUTHOR_KEY, function ($value) {
            return get_post()->post_author ?: wp_get_current_user()->ID;
        }, 10, 1);
        add_filter('acf/update_value/key=' . self::AUTHOR_KEY, function ($newAuthor) {
            $post = get_post();
            $oldAuthor = $post->post_author;
            if (intval($newAuthor) !== intval($oldAuthor)) {
                $post->post_author = $newAuthor;
                wp_update_post($post);
            }
            return null;
        }, 10, 1);
    }

    private static function getKind()
    {
        $kind = new Radio(self::KIND_KEY);
        $kind->setChoices([
                'Article' => 'Article',
                'Gallery' => 'Gallery',
                'Story' => 'Story',
                'Review' => 'Review',
                'Recipe' => 'Recipe',
            ])
            ->setDefaultValue('Article')
            ->setLayout('horizontal')
            ->setLabel('Kind')
            ->setName('kind')
            ->setRequired(1);

        return $kind;
    }

    private static function getDescription()
    {
        $description = new Textarea(self::DESCRIPTION_KEY);
        $description->setLabel('Description')
            ->setName('description');

        return $description;
    }

    private static function getAuthor()
    {
        $author = new User(self::AUTHOR_KEY);
        $author->setLabel('Author')
            ->setName('author');

        return $author;
    }

    private static function getAuthorDescription()
    {
        $authorDescription = new Text(self::AUTHOR_DESCRIPTION_KEY);
        $authorDescription->setLabel('Author Description')
            ->setName('author_description')
            ->setInstructions('Extra information about the authors ie. who took the photos or did the styling');

        return $authorDescription;
    }

    private static function getCategory()
    {
        $category = new Taxonomy(self::CATEGORY_KEY);
        $category->setTaxonomy(AcfName::TAXONOMY_CATEGORY)
            ->setFieldType('select')
            ->setSaveTerms(1)
            ->setLabel('Category')
            ->setName('category')
            ->setRequired(1);

        return $category;
    }

    private static function getTag()
    {
        $tag = new Taxonomy(self::TAG_KEY);
        $tag->setTaxonomy(AcfName::TAXONOMY_TAG)
            ->setFieldType('multi_select')
            ->setSaveTerms(1)
            ->setLabel('Tags')
            ->setName('tags');

        return $tag;
    }
}
