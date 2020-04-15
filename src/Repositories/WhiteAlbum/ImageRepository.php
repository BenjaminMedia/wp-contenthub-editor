<?php

namespace Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum;

use Exception;

/**
 * Class ImageRepository
 *
 * @package \Bonnier\WP\ContentHub\Repositories\WhiteAlbum
 */
class ImageRepository
{
    const IMAGE_RESOURCE = '/api/v2/images/';

    protected $client;


    /**
     * PanelRepository constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(
            [
                'base_uri' => 'https://whitealbum.dk',
            ]
        );
    }

    /**
     * @param      $whitealbumId
     * @param null $resource
     *
     * @return array|mixed|null|object
     */
    public function findById($whitealbumId)
    {
        return $this->get(
            static::IMAGE_RESOURCE . $whitealbumId,
            [
                'auth' => [
                    env('WHITEALBUM_USER'),
                    env('WHITEALBUM_PASSWORD'),
                ]
            ]
        );
    }

    private function get($url, $options, $attempt = 1)
    {
        try {
            $response = @$this->client->get($url, $options);
        } catch (Exception $e) {
            \WP_CLI::warning(sprintf('unable to fetch %s. %s will retry', $url, $e->getMessage()), false);
            if ($attempt < 5) {
                sleep(1); // Delay for a second before retrying
                return $this->get($url, $options, $attempt +1);
            }
            \WP_CLI::warning(sprintf('Failed 5 times will skip: %s', $url));
            $this->writeFailedImport($url, $e);
            return null;
        }
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents());
    }
}
