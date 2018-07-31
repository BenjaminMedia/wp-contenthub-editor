<?php
namespace Bonnier\WP\ContentHub\Editor\Repositories\Contracts\Scaphold;

interface CompositeContract
{
    public static function get_all($cursor = '', $limit = 100);

    public static function find_by_id($compositeId);

    public static function find_by_brand_id($brandId, $cursor = '', $limit = 100);
}
