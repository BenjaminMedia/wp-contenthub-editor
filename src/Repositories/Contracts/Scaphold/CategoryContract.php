<?php
namespace Bonnier\WP\ContentHub\Editor\Repositories\Contracts\Scaphold;

interface CategoryContract
{
    public static function get_all($cursor = '', $limit = 100);
    public static function find_by_id($id);
    public static function find_by_brand_id($id, $cursor = '', $limit = 100);
}
