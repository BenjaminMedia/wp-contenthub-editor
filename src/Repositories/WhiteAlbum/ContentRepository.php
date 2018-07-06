<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum;

use Exception;

/**
 * Class CompositeRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\Scaphold
 */
class ContentRepository
{
    const ARTICLE_RESOURCE = '/api/v1/articles/';
    const GALLERY_RESOURCE = '/api/v1/galleries/';
    const CONTENT_RESOURCE = '/api/v1/widget_contents';

    protected $client;


    /**
     * ContentRepository constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => getenv('WHITEALBUM_ENDPOINT'),
        ]);
    }

    public function find_by_id($id, $resource = null)
    {
        if(!$resource) {
            $resource = static::ARTICLE_RESOURCE;
        }

        return $this->get($resource . $id, [
            'auth' => [
                env('WHITEALBUM_USER'),
                env('WHITEALBUM_PASSWORD'),
            ]
        ]);
    }

    public function get_all($page = 1, $perPage = 50) {
        return $this->get(static::CONTENT_RESOURCE, [
            'query' => [
                'page' => $page,
                'per_page' => $perPage
            ]
        ]);
    }

    public function map_all($callback) {
        $contents = collect($this->get_all($page = 1));
        while( ! $contents->isEmpty()) {
            collect($contents)->each(function($content) use($callback) {
                $resource = collect([
                    'Article' => static::ARTICLE_RESOURCE,
                    'Gallery' => static::GALLERY_RESOURCE,
                ])->get($content->type);
                if($contentFound = $this->find_by_id($content->id, $resource)) {
                    $callback($contentFound);
                }
            });
            $page++;
            $contents = collect($this->get_all($page));
        }
    }

    private function get($url, $options) {

        try {
            $response = @$this->client->get($url,$options);
        } catch(Exception $e)
        {
            return null;
        }
        if($response->getStatusCode() !== 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents());
    }
}
