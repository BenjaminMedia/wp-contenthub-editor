<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\BaseTaxonomyImporter;

class UpdateEndpointHelper extends BaseTaxonomyImporter
{
    public function __construct()
    {
    }

    public static function updateCategory($term)
    {
        dd('updateCategory');
    }

    public static function updateTag($term)
    {
        dd('updatetag');
    }
}
