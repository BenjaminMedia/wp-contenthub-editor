<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\WP\ContentHub\Editor\Plugin;
use WP_CLI_Command;

/**
 * Class BaseCmd
 *
 * @package \Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers
 */
class BaseCmd extends WP_CLI_Command
{
    protected function map_sites($callable) {
        collect(Plugin::instance()->settings->get_languages())->pluck('locale')->map(function($locale) use($callable){
            return Plugin::instance()->settings->get_site($locale);
        })->rejectNullValues()->each($callable);
    }
}
