<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

/**
 * Class SlugHelper
 *
 * @package \Bonnier\WP\ContentHub\Editor\Helpers
 */
class SlugHelper
{
    public static function create_slug($string) {

        $string = strtolower($string);

        collect([ // Special slug characters that need to be replaced in a certain way to retain previously generated urls
            'ö' => 'o',
            'ø' => 'oe',
            'å' => 'aa',
            'æ' => 'ae',
            'ä' => 'a',
        ])->each(function($replacementChar, $charToChange) use(&$string) {
            $string = str_replace($charToChange, $replacementChar, $string);
        });

        return sanitize_title($string);
    }
}
