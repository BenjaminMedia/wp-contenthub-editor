<?php

namespace Bonnier\WP\ContentHub\Editor\Services\SiteManager;

/**
 * Class Client
 *
 * @package \Bonnier\WP\ContentHub\Editor\Services\SiteManager
 */
class Client extends \GuzzleHttp\Client
{
    protected static $instance = null;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'base_uri' => getenv('SITE_MANAGER_HOST'),
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ]
        ]);
    }

    /**
     * @return \GuzzleHttp\Client $client
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}
