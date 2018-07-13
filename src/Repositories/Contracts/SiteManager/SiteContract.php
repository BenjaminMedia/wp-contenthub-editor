<?php
namespace Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager;

interface SiteContract
{
    public static function get_all();
    public static function find_by_id($id);
}
