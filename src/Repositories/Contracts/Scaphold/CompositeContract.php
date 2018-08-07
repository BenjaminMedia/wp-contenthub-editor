<?php
namespace Bonnier\WP\ContentHub\Editor\Repositories\Contracts\Scaphold;

interface CompositeContract
{
    public static function getAll($cursor = '', $limit = 100);

    public static function findById($compositeId);

    public static function findByBrandId($brandId, $cursor = '', $limit = 100);
}
