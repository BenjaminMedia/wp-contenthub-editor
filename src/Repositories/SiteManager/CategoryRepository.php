<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\SiteManager;

use Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager\CategoryContract;
use Bonnier\WP\ContentHub\Editor\Services\SiteManager\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class SiteRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\SiteManager
 */
class CategoryRepository implements CategoryContract
{

    public static function get_all()
    {
        try {
            $response = Client::getInstance()->get('/api/v1/categories');
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
            $response = Client::getInstance()->get('/api/v1/categories/'.$id);
        } catch (ClientException $e) {
            return null;
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents()) :
            null;
    }

    public static function find_by_brand_id($id)
    {
        try {
            $response = Client::getInstance()->get('/api/v1/categories/brand/'.$id);
        } catch (ClientException $e) {
            return null;
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents()) :
            null;
    }
}
