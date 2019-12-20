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
        Collection::macro('toAssocCombine', function () {
            return $this->reduce(function ($assoc, $taxonomyValue) {
                collect($taxonomyValue)->each(function ($value, $taxonomy) use (&$assoc) {
                    if (!isset($assoc[$taxonomy]) || !$assoc[$taxonomy] instanceof Collection) {
                        $assoc[$taxonomy] = new static;
                    }
                    $assoc[$taxonomy]->push($value);
                });
                return $assoc;
            }, new static);
        });
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
        /**
         * Map nested array to collection
         * @Return Collection
         */
        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value)) {
                    return collect($value)->recursive();
                }
                return $value;
            });
        });
    }
}
