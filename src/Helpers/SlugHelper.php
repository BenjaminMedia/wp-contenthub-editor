<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

/**
 * Class SlugHelper
 *
 * @package \Bonnier\WP\ContentHub\Editor\Helpers
 */
class SlugHelper
{
    // Special slug characters that need to be replaced in a certain way to retain previously generated urls
    const REPLACEMENT_CHAR_INDEX = [
        'ö' => 'o',
        'ø' => 'oe',
        'å' => 'aa',
        'æ' => 'ae',
        'ä' => 'a',
    ];

    public static function create_slug($string)
    {
        return sanitize_title(
            str_replace(
                array_keys(static::REPLACEMENT_CHAR_INDEX),
                array_values(static::REPLACEMENT_CHAR_INDEX),
                mb_strtolower($string)
            )
        );
    }
}
