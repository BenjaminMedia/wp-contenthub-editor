<?php

namespace Bonnier\WP\ContentHub\Editor\Http;
use Red_Item;
use WordPress_Module;

/**
 * Class Redirects
 *
 * @package \Bonnier\WP\ContentHub\Editor\Http
 */
class Redirects
{
    /*
     * This class is still WIP and the logic needs to bee thoroughly tested before it is enabled
     * As right now the code will in some cases create infinite redirect loops.
     * But it is a good foundation for later adding automatic redirects when the slug is changed
     */

    public static function create_redirect_on_slug_change($postType){
        add_action( 'pre_post_update', function ($postId, $postData) use($postType){

            if($postData['post_type'] !== $postType) // only run for the required post type
               return;

            $source = ltrim($_POST['redirection_slug'], '/') ?? null; // Remove prefixed slash to match the structure of
            $target = $_POST['custom_permalink'] ?? null;
            $originalPermalink = custom_permalinks_original_post_link($postId);

            /*
             * If target is empty it means we are going back to auto generated url
             * therefore we should create a new redirect from the manually
             * entered url, to the new auto generated url
             */
            if(empty($target) && $source !== $originalPermalink) {
                $target = $originalPermalink;
            }

            // Make sure that we avoid creating duplicate redirects by checking for existing redirects
            if (self::redirect_exists($source, $target))
                return;

            if($source && $target && $target !== $source && $source !== $originalPermalink && !static::redirect_exists($source, $target)) {
                static::create_redirect($source, $target);
            }

        }, 10, 2 );
    }

    private static function redirect_exists($source, $target) {
        return collect(Red_Item::get_for_url('/' . ltrim($source, '/'), 'url'))->map(function(Red_Item $redItem){
            return $redItem->get_action_data();
        })->contains('/' . ltrim($target, '/'));
    }

    private static function update_existing($source, $target) {
        return collect(Red_Item::get_for_url('/' . ltrim($source, '/'), 'url'))->map(function(Red_Item $redItem) use($target, $source){
            $redItem->update([
                'old' => '/' . ltrim($source, '/'),
                'target' => '/' . ltrim($target, '/'),
                'red_action' => 'url',
                'match' => 'url',
                'action_code' => 301,
            ]);
        });
    }

    private static function create_redirect($source, $target) {
        return Red_Item::create([
            'source' => '/' . ltrim($source, '/'),
            'target' => '/' . ltrim($target, '/'),
            'red_action' => 'url',
            'match' => 'url',
            'action_code' => 301,
            'group_id' => 2,
        ]);
    }

    static function get_for_source( $source ) {

        $source = '/' . ltrim($source, '/');

        global $wpdb;

        $sql = $wpdb->prepare( "SELECT {$wpdb->prefix}redirection_items.*,{$wpdb->prefix}redirection_groups.position AS group_pos FROM {$wpdb->prefix}redirection_items INNER JOIN {$wpdb->prefix}redirection_groups ON {$wpdb->prefix}redirection_groups.id={$wpdb->prefix}redirection_items.group_id AND {$wpdb->prefix}redirection_groups.status='enabled' AND {$wpdb->prefix}redirection_groups.module_id=%d WHERE ({$wpdb->prefix}redirection_items.regex=1 OR {$wpdb->prefix}redirection_items.action_data=%s)", WordPress_Module::MODULE_ID, $source );

        $rows = $wpdb->get_results( $sql );
        $items = array();
        if ( count( $rows ) > 0 ) {
            foreach ( $rows as $row ) {
                $items[] = array( 'position' => ( $row->group_pos * 1000 ) + $row->position, 'item' => new Red_Item( $row ) );
            }
        }

        usort( $items, array( 'Red_Item', 'sort_urls' ) );
        $items = array_map( array( 'Red_Item', 'reduce_sorted_items' ), $items );

        // Sort it in PHP
        ksort( $items );
        $items = array_values( $items );
        return $items;
    }
}
