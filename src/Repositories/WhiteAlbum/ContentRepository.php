<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum;

use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
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
     *
     * @param null $locale Override default locale
     *
     * @throws \Exception
     */
    public function __construct($locale = null)
    {
        $endpoint = null;
        if ($locale) {
            $envKey = sprintf('WHITEALBUM_ENDPOINT_%s', strtoupper($locale));
            if (! $endpoint = env($envKey)) { // env returns null by default, which would be a falsey value
                throw new Exception(sprintf('%s has not been defined in your ENV file.', $envKey));
            }
        }
        $this->client = new \GuzzleHttp\Client(
            [
                'base_uri' => $endpoint ?: env('WHITEALBUM_ENDPOINT'),
            ]
        );
    }

    /**
     * @param      $whitealbumId
     * @param null $resource
     *
     * @return array|mixed|null|object
     */
    public function findById($whitealbumId, $resource = null)
    {
        if (! $resource) {
            $resource = static::ARTICLE_RESOURCE;
        }

        return $this->get(
            $resource . $whitealbumId,
            [
                'auth' => [
                    env('WHITEALBUM_USER'),
                    env('WHITEALBUM_PASSWORD'),
                ]
            ]
        );
    }

    public function getAll($page = 1, $perPage = 50)
    {
        return $this->get(
            static::CONTENT_RESOURCE,
            [
                'query' => [
                    'page'     => $page,
                    'per_page' => $perPage
                ]
            ]
        );
    }

    /**
     * @param      $callback
     * @param int  $page
     * @param bool $skipExisting
     */
    public function mapAll($callback, $page = 1, $skipExisting = false)
    {
        if ($skipExisting) {
            \WP_CLI::line('Skipping already imported content');
        }
        $contents = collect($this->getAll($page));
        while (! $contents->isEmpty()) {
            \WP_CLI::line(sprintf('Begining import of page: %d', $page));
            collect($contents)->each(
                function ($content) use ($callback, $skipExisting) {
                    $resource = collect(
                        [
                            'Article' => static::ARTICLE_RESOURCE,
                            'Gallery' => static::GALLERY_RESOURCE,
                        ]
                    )->get($content->type);
                    if (! $resource) {
                        \WP_CLI::warning(sprintf(
                            'Unsupported type: %s, skipping content: %s in locale: %s',
                            $content->type,
                            $content->title,
                            $content->locale
                        ));
                        return;
                    }
                    if ($skipExisting && WpComposite::id_from_white_album_id($content->id)) {
                        \WP_CLI::line(
                            sprintf(
                                '%s %s already exist, skipping import',
                                $content->type,
                                $content->id
                            )
                        );
                        return;
                    }
                    if ($waContent = $this->findById($content->id, $resource)) {
                        $callback($waContent);
                    }
                }
            );
            $page++;
            $contents = collect($this->getAll($page));
        }
    }

    private function get($url, $options)
    {
        try {
            $response = @$this->client->get($url, $options);
        } catch (Exception $e) {
            \WP_CLI::error(sprintf('unable to fetch %s. %s', $url, $e->getMessage()), false);
            return null;
        }
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents());
    }
}
