<?php
namespace Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager;

interface CategoryContract {
    public static function get_all();
    public static function find_by_id($id);
    public static function find_by_brand_id($id);
}
