<?php

namespace Bonnier\WP\ContentHub\Editor\Models\ACF\Composite;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Illuminate\Support\Collection;

/**
 * Class TaxonomyFields
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\ACF\Composite
 */
class TaxonomyFields
{
    public static function add_fields(Collection $customTaxonomies) {
        static::create_acf_field_group($customTaxonomies->map(function($customTaxonomy){
            return static::get_acf_taxonomy_field($customTaxonomy);
        })->toArray());
    }

    private static function create_acf_field_group($formattedFields) {
        acf_add_local_field_group([
            'key' => 'group_5937df68c8ff8',
            'title' => 'Taxonomy',
            'fields' => $formattedFields,
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => WpComposite::POST_TYPE,
                    ],
                ],
            ],
            'menu_order' => 1,
            'position' => 'acf_after_title',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ]);
    }

    private static function get_acf_taxonomy_field($customTaxonomy) {
        return [
            'key' => 'field_5937df6dd2735',
            'label' => $customTaxonomy->name,
            'name' => $customTaxonomy->machine_name,
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => $customTaxonomy->machine_name,
            'field_type' => 'select',
            'allow_null' => 1,
            'add_term' => 0,
            'save_terms' => 1,
            'load_terms' => 0,
            'return_format' => 'object',
            'multiple' => 0,
        ];
    }
}
