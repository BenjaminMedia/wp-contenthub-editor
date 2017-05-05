<?php

namespace Bonnier\WP\ContentHub\Editor\Scaphold;

use ErrorException;
use Exception;

class Client extends \GuzzleHttp\Client
{

    protected static $instance = null;

    /**
     * Client constructor.
     *
     * @param array $scapholdEndpoint
     */
    public function __construct()
    {
        parent::__construct([
            'base_uri' => getenv('SCAPHOLD_ENDPOINT'),
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

    public static function query($query, $variables = null) {

        $params = [
            'query' => static::removeLineBreaks($query),
            'variables' => $variables
        ];

        $response = static::requestScaphold($params);

        return isset($response['body']->data->viewer) ? $response['body']->data->viewer : $response['body']->data;
    }

    private static function removeLineBreaks($string) {

        return str_replace(array("\r", "\n"), '', $string);
    }

    private static function requestScaphold(Array $params, $tries = 0) {
        try {
            $response = static::getInstance()->post(null, [
                'json' => $params
            ]);
            $responseBody = $response->getBody()->getContents();
            // Scaphold sometimes return empty body with response code 200, we try to catch this and re attempt the request
            if(empty($responseBody)) {
                throw new Exception("Scaphold returned empty response body, with response code:".$response->getStatusCode());
            }
            return [
                'body' => json_decode($responseBody),
                'code' => $response->getStatusCode()
            ];
        } catch (Exception $e) {
            sleep(1);
            if ($tries > 20) {
                throw new ErrorException("Request Attempts to scaphold exceeded 20, skipping node");
            }
            return static::requestScaphold($params, $tries = $tries + 1);
        }
    }
}
