<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Exception;

class HtmlToMarkdown extends \GuzzleHttp\Client
{
    const ENDPOINT = 'http://html-to-markdown.interactives.dk';

    protected static $instance = null;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'base_uri' => static::ENDPOINT
        ]);
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function parseHtml($html)
    {
        try {
            $request = static::getInstance()->post('/', [
                'form_params' => [
                    'html' => $html,
                ]
            ]);
            return $request->getBody()->getContents();
        } catch (Exception $exception) {
            return null;
        }
    }
}
