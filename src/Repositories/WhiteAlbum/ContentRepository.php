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
    const GALLERY_RESOURCE = '/api/v1/galleries/';
    const CONTENT_RESOURCE = '/api/v1/widget_contents';

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

    public function find_by_id($id, $resource = null)
    {
        if(!$resource) {
            $resource = static::ARTICLE_RESOURCE;
        }

        $response = @$this->client->get($resource . $id);
        if($response->getStatusCode() !== 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents());
    }

    public function get_all($page = 1, $perPage = 50) {
        $response = @$this->client->get(static::CONTENT_RESOURCE, [
            'query' => [
                'page' => $page,
                'per_page' => $perPage
            ]
        ]);
        if($response->getStatusCode() !== 200) {
            return null;
        }
    }

    public function mapAll($callback) {
        $page = 1;
        while($contents = $this->get_all($page)) {
            collect($contents)->each(function($content) use($callback) {
                if($content->type === 'Article') {
                    $callback($this->find_by_id($content->widget_contentable_id, static::ARTICLE_RESOURCE));
                }
                if($content->type === 'Gallery') {
                    $callback($this->find_by_id($content->widget_contentable_id, static::GALLERY_RESOURCE));
                }
            });
        }
        $contents = $this->get_all($page++);
    }
}
