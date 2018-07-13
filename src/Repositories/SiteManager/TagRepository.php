<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\SiteManager;

use Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager\TagContract;
use Bonnier\WP\ContentHub\Editor\Services\SiteManager\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class TagRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\SiteManager
 */
class TagRepository implements TagContract
{
    public static function get_all($page = 1)
    {
        try {
            $response = Client::getInstance()->get('/api/v1/tags', [
                'query' => [
                    'page' => $page
                ]
            ]);
        } catch (ClientException $e) {
            return [];
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents())->data :
            [];
    }

    public static function find_by_id($id)
    {
        try {
            $response = Client::getInstance()->get('/api/v1/tags/'.$id);
        } catch (ClientException $e) {
            return null;
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents()) :
            null;
    }

    public static function find_by_brand_id($id, $page = 1)
    {
        try {
            $response = Client::getInstance()->get('/api/v1/tags/brand/'.$id, [
                'query' => [
                    'page' => $page
                ]
            ]);
        } catch (ClientException $e) {
            return null;
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents()) :
            null;
    }
}
