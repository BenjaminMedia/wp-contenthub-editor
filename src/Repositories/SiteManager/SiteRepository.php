<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\SiteManager;

use Bonnier\WP\ContentHub\Editor\Repositories\Contracts\SiteManager\SiteContract;
use Bonnier\WP\ContentHub\Editor\Services\SiteManager\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class SiteRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\SiteManager
 */
class SiteRepository implements SiteContract
{
    public static function get_all()
    {
        try {
            $response = Client::getInstance()->get('/api/v1/sites');
        } catch (ClientException $e) {
            return [];
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents())->data :
            [];
    }

    public static function find_by_id($id)
    {
        if (is_null($id)) {
            return null;
        }
        try {
            $response = Client::getInstance()->get('/api/v1/sites/'.$id);
        } catch (ClientException $e) {
            return null;
        }
        return $response->getStatusCode() === 200 ?
            json_decode($response->getBody()->getContents()) :
            null;
    }
}
