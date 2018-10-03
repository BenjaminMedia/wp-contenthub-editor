<?php

namespace Bonnier\WP\ContentHub\Editor\ACF;

use Bonnier\WP\ContentHub\Editor\ContenthubEditor;

class CustomRelationship extends \acf_field
{
    const NAME = 'custom_relationship';

    public static function register()
    {
        acf_register_field_type(new self());
    }

    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date    5/03/2014
    *  @since   5.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */
    public function __construct()
    {
        // vars
        $this->name = self::NAME;
        $this->label = "Custom Relationship";
        $this->category = 'relational';
        $this->defaults = array(
            'post_type'            => array(),
            'taxonomy'            => array(),
            'post_tag'          => array(),
            'min'                => 0,
            'max'                => 0,
            'filters'            => array('search', 'post_type', 'taxonomy', 'post_tag'),
            'elements'            => array(),
            'return_format'        => 'object'
        );
        $this->l10n = array(
            'min'        => __("Minimum values reached ( {min} values )", 'acf'),
            'max'        => __("Maximum values reached ( {max} values )", 'acf'),
            'loading'    => __('Loading', 'acf'),
            'empty'        => __('No matches found', 'acf'),
        );

        // extra
        add_action('wp_ajax_acf/fields/relationship/query', array($this, 'ajax_query'));
        add_action('wp_ajax_nopriv_acf/fields/relationship/query', array($this, 'ajax_query'));

        // do not delete!
        parent::__construct();
    }


    /*
    *  ajax_query
    *
    *  description
    *
    *  @type    function
    *  @date    24/10/13
    *  @since   5.0.0
    *
    *  @param   $post_id (int)
    *  @return  $post_id (int)
    */
    public function ajax_query()
    {
        // validate
        if (!acf_verify_ajax()) {
            die();
        }

        // get choices
        $response = $this->get_ajax_query($_POST);

        // return
        acf_send_ajax_results($response);
    }


    /*
    *  get_ajax_query
    *
    *  This function will return an array of data formatted for use in a select2 AJAX response
    *
    *  @type    function
    *  @date    15/10/2014
    *  @since   5.0.9
    *
    *  @param   $options (array)
    *  @return  (array)
    */
    public function get_ajax_query($options = array())
    {
        // defaults
        $options = acf_parse_args($options, array(
            'post_id'        => 0,
            's'                => '',
            'field_key'        => '',
            'paged'            => 1,
            'post_type'        => '',
            'taxonomy'        => '',
            'post_tag'      => ''
        ));

        if (isset($options['pll_post_id'])) {
            $post_language = pll_get_post_language($options['pll_post_id']);
        } else {
            $post_language = pll_get_post_language($options['post_id']);
        }

        // load field
        $field = acf_get_field($options['field_key']);
        if (!$field) {
            return false;
        }

        // vars
        $results = array();
        $args = array();
        $search = false;
        $is_search = false;

        // paged
        $args['posts_per_page'] = 20;
        $args['paged'] = $options['paged'];

        // search
        if ($options['s'] !== '') {
            // strip slashes (search may be integer)
            $search = wp_unslash(strval($options['s']));


            // update vars
            $args['s'] = $search;
            $is_search = true;
        }

        // post_type
        if (!empty($options['post_type'])) {
            $args['post_type'] = acf_get_array($options['post_type']);
        } elseif (!empty($field['post_type'])) {
            $args['post_type'] = acf_get_array($field['post_type']);
        } else {
            $args['post_type'] = acf_get_post_types();
        }

        // taxonomy
        if (!empty($options['taxonomy'])) {
            // vars
            $term = acf_decode_taxonomy_term($options['taxonomy']);

            // tax query
            $args['tax_query'] = array();

            // append
            $args['tax_query'][] = array(
                'taxonomy'    => $term['taxonomy'],
                'field'        => 'slug',
                'terms'        => $term['term'],
            );
        } elseif (!empty($field['taxonomy'])) {
            // vars
            $terms = acf_decode_taxonomy_terms($field['taxonomy']);

            // append to $args
            $args['tax_query'] = array();

            // now create the tax queries
            foreach ($terms as $k => $v) {
                $args['tax_query'][] = array(
                    'taxonomy'    => $k,
                    'field'        => 'slug',
                    'terms'        => $v,
                );
            }
        }

        if (!empty($options['post_tag'])) {
            // vars
            $tag = acf_decode_taxonomy_term($options['post_tag']);

            // append tax_query
            $args['tax_query'][] = array(
                'taxonomy'    => $tag['taxonomy'],
                'field'        => 'slug',
                'terms'        => $tag['term'],
            );
        } elseif (!empty($field['post_tag'])) {
            // vars
            $tags = acf_decode_taxonomy_terms($field['post_tag']);

            // append tax_query to $args
            foreach ($tags as $k => $v) {
                $args['tax_query'][] = array(
                    'taxonomy'    => $k,
                    'field'        => 'slug',
                    'terms'        => $v,
                );
            }
        }

        // filters
        $args = apply_filters('acf/fields/relationship/query', $args, $field, $options['post_id']);
        $args = apply_filters(
            'acf/fields/relationship/query/name=' . $field['name'],
            $args,
            $field,
            $options['post_id']
        );
        $args = apply_filters('acf/fields/relationship/query/key=' . $field['key'], $args, $field, $options['post_id']);

        // get posts grouped by post type
        $groups = acf_get_grouped_posts($args);

        // bail early if no posts
        if (empty($groups)) {
            return false;
        }

        // loop
        foreach (array_keys($groups) as $group_title) {
            // vars
            $posts = acf_extract_var($groups, $group_title);

            // data
            $data = array(
                'text'        => $group_title,
                'children'    => array()
            );

            // convert post objects to post titles
            foreach (array_keys($posts) as $post_id) {
                $posts[ $post_id ] = $this->get_post_title($posts[ $post_id ], $field, $options['post_id']);
            }

            // order posts by search
            if ($is_search && empty($args['orderby'])) {
                $posts = acf_order_by_search($posts, $args['s']);
            }

            // append to $data
            foreach (array_keys($posts) as $post_id) {
                $child_post = $this->get_post_result($post_id, $posts[ $post_id ]);

                $child_lang = pll_get_post_language($child_post['id']);

                if ($child_lang !== $post_language) {
                    continue;
                }
                $data['children'][] = $child_post;
            }

            // append to $results
            $results[] = $data;
        }

        // add as optgroup or results
        if (count($args['post_type']) == 1) {
            $results = $results[0]['children'];
        }

        // vars
        $response = array(
            'results'    => $results,
            'limit'        => $args['posts_per_page']
        );

        // return
        return $response;
    }

    /*
    *  get_post_result
    *
    *  This function will return an array containing id, text and maybe description data
    *
    *  @type    function
    *  @date    7/07/2016
    *  @since   5.4.0
    *
    *  @param   $id (mixed)
    *  @param   $text (string)
    *  @return  (array)
    */
    public function get_post_result($id, $text)
    {
        // vars
        $result = array(
            'id'    => $id,
            'text'    => $text
        );

        // return
        return $result;
    }

    /*
    *  get_post_title
    *
    *  This function returns the HTML for a result
    *
    *  @type    function
    *  @date    1/11/2013
    *  @since   5.0.0
    *
    *  @param   $post (object)
    *  @param   $field (array)
    *  @param   $post_id (int) the post_id to which this value is saved to
    *  @return  (string)
    */
    public function get_post_title($post, $field, $post_id = 0, $is_search = 0)
    {
        // get post_id
        if (!$post_id) {
            $post_id = acf_get_form_data('post_id');
        }

        // vars
        $title = acf_get_post_title($post, $is_search);

        // featured_image
        if (acf_in_array('featured_image', $field['elements'])) {
            // vars
            $class = 'thumbnail';
            $thumbnail = acf_get_post_thumbnail($post->ID, array(17, 17));


            // icon
            if ($thumbnail['type'] == 'icon') {
                $class .= ' -' . $thumbnail['type'];
            }


            // append
            $title = '<div class="' . $class . '">' . $thumbnail['html'] . '</div>' . $title;
        }


        // filters
        $title = apply_filters('acf/fields/relationship/result', $title, $post, $field, $post_id);
        $title = apply_filters(
            'acf/fields/relationship/result/name=' . $field['_name'],
            $title,
            $post,
            $field,
            $post_id
        );
        $title = apply_filters('acf/fields/relationship/result/key=' . $field['key'], $title, $post, $field, $post_id);

        // return
        return $title;
    }

    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param   $field - an array holding all the field's data
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    */
    public function render_field($field)
    {
        // vars
        $atts = array(
            'id'                => $field['id'],
            'class'                => "acf-relationship {$field['class']}",
            'data-min'            => $field['min'],
            'data-max'            => $field['max'],
            'data-s'            => '',
            'data-post_type'    => '',
            'data-taxonomy'        => '',
            'data-post_tag'     => '',
            'data-paged'        => 1,
        );

        // Lang
        if (defined('ICL_LANGUAGE_CODE')) {
            $atts['data-lang'] = ICL_LANGUAGE_CODE;
        }

        // data types
        $field['post_type'] = acf_get_array($field['post_type']);
        $field['taxonomy'] = acf_get_array($field['taxonomy']);
        $field['post_tag'] = acf_get_array($field['post_tag']);

        // width for select filters
        $width = array(
            'search'    => 0,
            'post_type'    => 0,
            'taxonomy'    => 0,
            'post_tag'  => 0
        );

        if (!empty($field['filters'])) {
            $width = array(
                'search'    => 25,
                'post_type'    => 25,
                'taxonomy'    => 25,
                'post_tag'  => 25
            );

            foreach (array_keys($width) as $k) {
                if (! in_array($k, $field['filters'])) {
                    $width[ $k ] = 0;
                }
            }

            // search
            if ($width['search'] == 0) {
                $width['post_type'] = ($width['post_type'] == 0) ? 0 : 25;
                $width['taxonomy'] = ($width['taxonomy'] == 0) ? 0 : 50;
                $width['post_tag'] = ($width['post_tag'] == 0) ? 0 : 25;
            }

            // post_type
            if ($width['post_type'] == 0) {
                $width['search'] = ($width['search'] == 0) ? 0 : 50;
                $width['taxonomy'] = ($width['taxonomy'] == 0) ? 0 : 25;
                $width['post_tag'] = ($width['post_tag'] == 0) ? 0 : 25;
            }

            // taxonomy
            if ($width['taxonomy'] == 0) {
                $width['post_type'] = ($width['post_type'] == 0) ? 0 : 25;
                $width['post_tag'] = ($width['post_tag'] == 0) ? 0 : 50;
            }

            //post_tag
            if ($width['post_tag'] == 0 && $width['post_type'] == 0) {
                $width['taxonomy'] = ($width['taxonomy'] == 0) ? 0 : 50;
                $width['search'] = ($width['search'] == 0) ? 0 : 50;
            }

            // search
            if ($width['post_type'] == 0 && $width['taxonomy'] == 0 &&  $width['post_tag'] == 0) {
                $width['search'] = ($width['search'] == 0) ? 0 : 100;
            }
        }

        // post type filter
        $post_types = array();

        if ($width['post_type']) {
            if (!empty($field['post_type'])) {
                $post_types = $field['post_type'];
            }

            $post_types = acf_get_pretty_post_types($post_types);
        }

        $term_groups = array();

        if ($width['taxonomy']) {
            // terms
            $term_groups = acf_get_taxonomy_terms('category');
        }

        $post_tags = array();

        if ($width['post_tag']) {
            // terms
            $post_tags = acf_get_taxonomy_terms('post_tag');
        }
        // end taxonomy filter?>
        <div <?php acf_esc_attr_e($atts); ?>>

            <div class="acf-hidden">
                <input type="hidden" name="<?php echo $field['name']; ?>" value="" />
            </div>

            <?php if ($width['search'] || $width['post_type'] || $width['taxonomy']): ?>
                <div class="filters">
                    <ul class="acf-hl">
                        <?php if ($width['search']) : ?>
                            <li style="width:<?php echo $width['search']; ?>%;">
                                <div class="inner">
                                    <input
                                        class="filter"
                                        data-filter="s"
                                        placeholder="<?php _e("Search...", 'acf'); ?>"
                                        type="text" />
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ($width['post_type']) : ?>
                            <li style="width:<?php echo $width['post_type']; ?>%;">
                                <div class="inner">
                                    <select class="filter" data-filter="post_type">
                                        <option value=""><?php _e('Select post type', 'acf'); ?></option>
                                        <?php foreach ($post_types as $k => $v) : ?>
                                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ($width['post_tag']) : ?>
                            <li style="width:<?php echo $width['post_tag']; ?>%;">
                                <div class="inner">
                                    <select class="filter" data-filter="post_tag">
                                        <option value=""><?php _e('Select tag', 'acf'); ?></option>
                                        <?php foreach ($post_tags as $k_opt => $v_opt) : ?>
                                            <optgroup label="<?php echo $k_opt; ?>">
                                                <?php foreach ($v_opt as $k => $v) : ?>
                                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ($width['taxonomy']) : ?>
                            <li style="width:<?php echo $width['taxonomy']; ?>%;">
                                <div class="inner">
                                    <select class="filter" data-filter="taxonomy">
                                        <option value=""><?php _e('Select taxonomy', 'acf'); ?></option>
                                        <?php foreach ($term_groups as $k_opt => $v_opt) : ?>
                                            <optgroup label="<?php echo $k_opt; ?>">
                                                <?php foreach ($v_opt as $k => $v) : ?>
                                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            <?php endif; ?>

            <div class="selection acf-cf">

                <div class="choices">

                    <ul class="acf-bl list"></ul>

                </div>

                <div class="values">

                    <ul class="acf-bl list">

                        <?php if (!empty($field['value'])) :
                            // get posts
                            $posts = acf_get_posts(array(
                                'post__in' => $field['value'],
                                'post_type'    => $field['post_type']
                            ));

        // set choices
        if (!empty($posts)) :
                                foreach (array_keys($posts) as $i) :
                                    // vars
                                    $post = acf_extract_var($posts, $i); ?><li>
                                    <input
                                        type="hidden"
                                        name="<?php echo $field['name']; ?>[]"
                                        value="<?php echo $post->ID; ?>" />
                                    <span data-id="<?php echo $post->ID; ?>" class="acf-rel-item">
                                        <?php echo $this->get_post_title($post, $field); ?>
                                        <a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>
                                    </span>
                                    </li>
                                    <?php
                                endforeach;
        endif;
        endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    /*
    *  render_field_settings()
    *
    *  Create extra options for your field. This is rendered when editing a field.
    *  The value of $field['name'] can be used (like bellow) to save extra data to the $field
    *
    *  @type    action
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $field - an array holding all the field's data
    */
    public function render_field_settings($field)
    {
        // vars
        $field['min'] = empty($field['min']) ? '' : $field['min'];
        $field['max'] = empty($field['max']) ? '' : $field['max'];

        // post_type
        acf_render_field_setting($field, array(
            'label'            => __('Filter by Post Type', 'acf'),
            'instructions'    => '',
            'type'            => 'select',
            'name'            => 'post_type',
            'choices'        => acf_get_pretty_post_types(),
            'multiple'        => 1,
            'ui'            => 1,
            'allow_null'    => 1,
            'placeholder'    => __("All post types", 'acf'),
        ));

        // taxonomy
        acf_render_field_setting($field, array(
            'label'            => __('Filter by Taxonomy', 'acf'),
            'instructions'    => '',
            'type'            => 'select',
            'name'            => 'taxonomy',
            'choices'        => acf_get_taxonomy_terms(),
            'multiple'        => 1,
            'ui'            => 1,
            'allow_null'    => 1,
            'placeholder'    => __("All taxonomies", 'acf'),
        ));

        // tag
        acf_render_field_setting($field, array(
            'label'            => __('Filter by Tag', 'acf'),
            'instructions'    => '',
            'type'            => 'select',
            'name'            => 'post_tag',
            'choices'        => acf_get_taxonomy_terms(array('post_tag')),
            'multiple'        => 1,
            'ui'            => 1,
            'allow_null'    => 1,
            'placeholder'    => __("All tags", 'acf'),
        ));

        // filters
        acf_render_field_setting($field, array(
            'label'            => __('Filters', 'acf'),
            'instructions'    => '',
            'type'            => 'checkbox',
            'name'            => 'filters',
            'choices'        => array(
                'search'        => __("Search", 'acf'),
                'post_type'        => __("Post Type", 'acf'),
                'taxonomy'        => __("Taxonomy", 'acf'),
                'post_tag'      => __("Post Tag", 'acf'),
            ),
        ));

        // filters
        acf_render_field_setting($field, array(
            'label'            => __('Elements', 'acf'),
            'instructions'    => __('Selected elements will be displayed in each result', 'acf'),
            'type'            => 'checkbox',
            'name'            => 'elements',
            'choices'        => array(
                'featured_image'    => __("Featured Image", 'acf'),
            ),
        ));

        // min
        acf_render_field_setting($field, array(
            'label'            => __('Minimum posts', 'acf'),
            'instructions'    => '',
            'type'            => 'number',
            'name'            => 'min',
        ));

        // max
        acf_render_field_setting($field, array(
            'label'            => __('Maximum posts', 'acf'),
            'instructions'    => '',
            'type'            => 'number',
            'name'            => 'max',
        ));

        // return_format
        acf_render_field_setting($field, array(
            'label'            => __('Return Format', 'acf'),
            'instructions'    => '',
            'type'            => 'radio',
            'name'            => 'return_format',
            'choices'        => array(
                'object'        => __("Post Object", 'acf'),
                'id'            => __("Post ID", 'acf'),
            ),
            'layout'    =>    'horizontal',
        ));
    }

    /*
    *  format_value()
    *
    *  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
    *
    *  @type    filter
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $value (mixed) the value which was loaded from the database
    *  @param   $post_id (mixed) the $post_id from which the value was loaded
    *  @param   $field (array) the field array holding all the field options
    *
    *  @return  $value (mixed) the modified value
    */
    public function format_value($value, $post_id, $field)
    {
        // bail early if no value
        if (empty($value)) {
            return $value;
        }

        // force value to array
        $value = acf_get_array($value);

        // convert to int
        $value = array_map('intval', $value);

        // load posts if needed
        if ($field['return_format'] == 'object') {
            // get posts
            $value = $this->willow_acf_get_posts(array(
                'post__in' => $value,
                'post_type'    => $field['post_type']
            ));
        }

        // return
        return $value;
    }

    /*
    *  willow_acf_get_posts is a duplicated function from acf_get_posts (but without changing the order)
    *
    *  This function will return an array of posts.
    *  @param   $args (array)
    *  @return  (array)
    */
    public function willow_acf_get_posts($args = array())
    {
        // vars
        $posts = array();

        // defaults
        // leave suppress_filters as true becuase we don't want any plugins to modify the query as we know exactly what
        $args = wp_parse_args($args, array(
            'posts_per_page' => -1,
            'post_type' => '',
            'post_status' => 'any',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ));

        // post type
        if (empty($args['post_type'])) {
            $args['post_type'] = acf_get_post_types();
        }

        // validate post__in
        if ($args['post__in']) {
            // force value to array
            $args['post__in'] = acf_get_array($args['post__in']);

            // convert to int
            $args['post__in'] = array_map('intval', $args['post__in']);

            // order by post__in
            $args['orderby'] = 'post__in';
        }

        // load posts in 1 query to save multiple DB calls from following code
        $posts = get_posts($args);

        // return
        return $posts;
    }


    /*
    *  validate_value
    *
    *  description
    *
    *  @type    function
    *  @date    11/02/2014
    *  @since   5.0.0
    *
    *  @param   $post_id (int)
    *  @return  $post_id (int)
    */
    public function validate_value($valid, $value, $field, $input)
    {
        // default
        if (empty($value) || !is_array($value)) {
            $value = array();
        }


        // min
        if (count($value) < $field['min']) {
            $valid = _n(
                '%s requires at least %s selection',
                '%s requires at least %s selections',
                $field['min'],
                'acf'
            );
            $valid = sprintf($valid, $field['label'], $field['min']);
        }


        // return
        return $valid;
    }


    /*
    *  update_value()
    *
    *  This filter is appied to the $value before it is updated in the db
    *
    *  @type    filter
    *  @since   3.6
    *  @date    23/01/13
    *
    *  @param   $value - the value which will be saved in the database
    *  @param   $post_id - the $post_id of which the value will be saved
    *  @param   $field - the field array holding all the field options
    *
    *  @return  $value - the modified value
    */
    public function update_value($value, $post_id, $field)
    {
        // validate
        if (empty($value)) {
            return $value;
        }

        // force value to array
        $value = acf_get_array($value);

        // array
        foreach ($value as $k => $v) {
            // object?
            if (is_object($v) && isset($v->ID)) {
                $value[ $k ] = $v->ID;
            }
        }

        // save value as strings, so we can clearly search for them in SQL LIKE statements
        $value = array_map('strval', $value);

        // return
        return $value;
    }

    public function input_admin_enqueue_scripts()
    {
        wp_enqueue_script(
        'acf-custom-relationship',
            ContenthubEditor::instance()->pluginUrl . 'js/acf/fields/acf-custom-relationship.js'
        );
    }
}
