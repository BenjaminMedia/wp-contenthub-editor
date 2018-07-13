<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\Scaphold;

use Bonnier\WP\ContentHub\Editor\Repositories\Contracts\Scaphold\CategoryContract;
use Bonnier\WP\ContentHub\Editor\Scaphold\Client;
use Bonnier\WP\ContentHub\Editor\Scaphold\Queries;

/**
 * Class CategoryRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\Scaphold
 */
class CategoryRepository implements CategoryContract
{
    public static function get_all($cursor = '', $limit = 100)
    {
        return Client::query(Queries::GET_CATEGORIES, [
            'cursor' => $cursor,
            'limit' => $limit
        ])->allCategories;
    }

    public static function find_by_id($id)
    {
        return Client::query(Queries::GET_CATEGORY, ['id' => $id])->getCategory ?? null;
    }

    public static function find_by_brand_id($id, $cursor = '', $limit = 100)
    {
        return Client::query(Queries::GET_CATEGORIES_BY_BRAND, [
            'brandId' => $id,
            'cursor' => $cursor,
            'limit' => $limit
        ])->allCategories;
    }

    public static function find_by_brand_id_and_name($id, $name, $locale, $cursor = '', $limit = 100)
    {
        return Client::query(Queries::GET_CATEGORY_BY_BRAND_AND_NAME, [
            'name' => $name,
            'brandId' => $id,
            'locale' => $locale,
            'cursor' => $cursor,
            'limit' => $limit
        ])->allCategories;
    }

    public static function create($input)
    {
        return Client::query(Queries::CREATE_COMPOSITE, [
            'input' => $input
        ])->createComposite->changedEdge->node->id ?? null;
    }

    public static function update($input)
    {
        return Client::query(Queries::UPDATE_COMPOSITE, [
                'input' => $input
        ])->updateComposite->changedEdge->node->id ?? null;
    }
}
