<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;

/**
 * Class CompositeFieldGroup
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class CompositeFieldGroup
{
    public static function register() {
        static::create_acf_field_group();
        static::register_slug_hooks();
        static::register_author_hooks();
    }

    private static function create_acf_field_group() {
        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group([
                'key' => 'group_58abfd3931f2f',
                'title' => 'Composite Fields',
                'fields' => [
                    [
                        'key' => 'field_5af983e0cca4c',
                        'label' => 'Slug',
                        'name' => 'slug',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_58e388862daa8',
                        'label' => 'Kind',
                        'name' => 'kind',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'choices' => [
                            'Article' => 'Article',
                            'Gallery' => 'Gallery',
                            'Story' => 'Story',
                            'Review' => 'Review',
                            'Recipe' => 'Recipe',
                        ],
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'Article',
                        'layout' => 'horizontal',
                        'allow_null' => 0,
                        'return_format' => 'value',
                    ],
                    [
                        'key' => 'field_58abfebd21b82',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ],
                    [
                        'key' => 'field_5af9888b4b7a1',
                        'label' => 'Author',
                        'name' => 'author',
                        'type' => 'user',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'role' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                    ],
                    [
                        'key' => 'field_5a8d44d026528',
                        'label' => 'Author Description',
                        'name' => 'author_description',
                        'type' => 'text',
                        'instructions' => 'Extra information about the authors ie. who took the photos or did the styling',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ],
                    [
                        'key' => 'field_58e39a7118284',
                        'label' => 'Category',
                        'name' => 'category',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'taxonomy' => 'category',
                        'field_type' => 'select',
                        'allow_null' => 0,
                        'add_term' => 0,
                        'save_terms' => 1,
                        'load_terms' => 0,
                        'return_format' => 'object',
                        'multiple' => 0,
                    ],
                    [
                        'key' => 'field_58f606b6e1fb0',
                        'label' => 'Tags',
                        'name' => 'tags',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'taxonomy' => 'post_tag',
                        'field_type' => 'multi_select',
                        'allow_null' => 0,
                        'add_term' => 1,
                        'save_terms' => 1,
                        'load_terms' => 0,
                        'return_format' => 'object',
                        'multiple' => 0,
                    ],
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
                'menu_order' => 0,
                'position' => 'acf_after_title',
                'style' => 'seamless',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => [
                    'slug',
                    'categories',
                    'author'
                ],
                'active' => 1,
                'description' => 'test',
            ]);
        endif;

    }

    private static function register_slug_hooks()
    {
        add_filter('acf/load_value/name=slug', function($value){
            return get_post()->post_name;
        }, 10, 1);
        add_filter('acf/update_value/name=slug', function($value){
            $post = get_post();
            $newSlug = sanitize_title($value);
            $oldSlug = $post->post_name;

            if($newSlug !== $oldSlug) {
                $post->post_name = $newSlug;
                wp_update_post($post);
            }

            return null;
        }, 10, 1);
    }

    private static function register_author_hooks()
    {
        add_filter('acf/load_value/name=author', function($value){
            return get_post()->post_author ?: wp_get_current_user()->ID;
        }, 10, 1);
        add_filter('acf/update_value/name=author', function($newAuthor){
            $post = get_post();
            $oldAuthor = $post->post_author;
            if(intval($newAuthor) !== intval($oldAuthor)) {
                $post->post_author = $newAuthor;
                wp_update_post($post);
            }
            return null;
        }, 10, 1);
    }
}
