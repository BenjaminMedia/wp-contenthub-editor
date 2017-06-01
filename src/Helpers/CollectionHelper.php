<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Illuminate\Support\Collection;

/**
 * Class Collection
 *
 * @package \Bonnier\WP\ContentHub\Editor\Helpers
 */
class CollectionHelper extends Collection
{
    /**
     * CollectionHelper constructor.
     */
    public function __construct()
    {
        Collection::macro('toAssoc', function () {
            return $this->reduce(function ($assoc, $keyValuePair) {
                list($key, $value) = $keyValuePair;
                $assoc[$key] = $value;
                return $assoc;
            }, new static);
        });
        Collection::macro('rejectNullValues', function () {
            return $this->reject(function ($value) {
                return is_null($value);
            });
        });
    }
}
