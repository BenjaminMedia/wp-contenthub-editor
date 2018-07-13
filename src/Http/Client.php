<?php

namespace Bonnier\WP\ContentHub\Editor\Http;

use Exception;

class Client
{
    private $baseUri;

    const DEFAULT_OPTIONS = [
        'timeout' => 15,
        'redirection' => 15,
    ];

    public function __construct(array $options = [])
    {
        if (!isset($options['base_uri'])) {
            throw new Exception('Missing required option: base_uri');
        }
        $this->baseUri = $options['base_uri'];
    }

    public function get($path, array $options = [])
    {
        $request = wp_remote_get($this->buildUri($path), array_merge(self::DEFAULT_OPTIONS, $options));

        return new HttpResponse($request);
    }

    public function post($path, array $options = [])
    {
        $request = wp_remote_post($this->buildUri($path), array_merge(self::DEFAULT_OPTIONS, $options));

        return new HttpResponse($request);
    }

    private function buildUri($path)
    {
        return rtrim($this->baseUri, '/') . '/' . ltrim($path, '/');
    }
}
