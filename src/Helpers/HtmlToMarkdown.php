<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use DOMAttr;
use DOMDocument;
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

    /**
     * @param      $html
     *
     * @param bool $fixAnchors
     *
     * @return null|string
     */
    public static function parseHtml($html, $fixAnchors = true)
    {
        if ($fixAnchors) {
            $html = static::fixAnchorTags($html);
        }
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

    private static function fixAnchorTags($html)
    {
        // Get all anchor tags as html strings
        preg_match_all('/<a[^>]*>(?:.|\n)*?<\/a>/i', $html, $matches);

        collect($matches)->flatten()->each(function ($anchorHMTL) use (&$html) {
            // convert encoding to special chars are read correctly
            $anchorHMTL = mb_convert_encoding($anchorHMTL, 'HTML-ENTITIES', "UTF-8");
            // Parse the anchor so we may use objects to access the attributes

            $domDocument = (new DOMDocument());
            $domDocument->loadHTML($anchorHMTL);
            $anchors = $domDocument->getElementsByTagName('a');
            /* @var $anchor \DOMElement */
            if ($anchor = $anchors->item(0)) {
                $attributes = collect($anchor->attributes)
                    ->only(['target', 'title', 'rel']) // Get only the attributes we are interested in
                    ->reduce(function ($attributes, DOMAttr $attribute) {
                        $attributes[$attribute->name] = $attribute->textContent;
                        return $attributes;
                    }, []);

                $markdown = sprintf('[%s](%s%s)',
                    static::parseHtml($anchor->textContent, false), // Fix any html that might be inside
                    $anchor->getAttribute('href'),
                    empty($attributes) ? '' : sprintf(' %s', json_encode($attributes))
                );
                $html = str_replace(html_entity_decode($anchorHMTL), $markdown, $html);
            }
        });
        return $html;
    }
}
