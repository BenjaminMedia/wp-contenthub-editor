<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\Scaphold;

use Bonnier\WP\ContentHub\Editor\Repositories\Contracts\Scaphold\CompositeContract;
use Bonnier\WP\ContentHub\Editor\Scaphold\Client;
use Bonnier\WP\ContentHub\Editor\Scaphold\Queries;

/**
 * Class CompositeRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\Scaphold
 */
class CompositeRepository implements CompositeContract
{
    public static function getAll($cursor = '', $limit = 100)
    {
        return Client::query(Queries::GET_COMPOSITES, [
            'cursor' => $cursor,
            'limit' => $limit
        ])->allComposites;
    }

    public static function findById($compositeId)
    {
        return Client::query(Queries::GET_COMPOSITE, ['id' => $compositeId])->getComposite ?? null;
    }

    public static function findByBrandId($brandId, $cursor = '', $limit = 100)
    {
        return Client::query(Queries::GET_COMPOSITES_BY_BRAND, [
            'brandId' => $brandId,
            'cursor' => $cursor,
            'limit' => $limit
        ])->allComposites;
    }

    public static function findByBrandIdAndSource($brandId, $source, $cursor = '', $limit = 100)
    {
        return Client::query(Queries::GET_COMPOSITES_BY_BRAND_AND_SOURCE, [
            'source' => $source,
            'brandId' => $brandId,
            'cursor' => $cursor,
            'limit' => $limit
        ])->allComposites;
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
