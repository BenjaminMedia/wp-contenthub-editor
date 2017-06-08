<?php
namespace Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager;

interface VocabularyContract {
    public static function get_all($page = 1);
    public static function find_by_id($id);
    public static function find_by_app_id($id, $page = 1);
}
