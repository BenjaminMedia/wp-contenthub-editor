<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum;

/**
 * Class CompositeRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\Scaphold
 */
class ContentRepository
{
    const ARTICLE_RESOURCE = '/api/v1/articles/';

    protected $client;


    /**
     * ContentRepository constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => getenv('WA_ENDPOINT'),
            'auth' => [
                'whitealbum',
                'Appelsin18'
            ]
        ]);
    }

    public function find_by_id($id)
    {
        $response = @$this->client->get(static::ARTICLE_RESOURCE . $id);
        if($response->getStatusCode() !== 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents());
    }
}
