<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

if ( defined('WP_CLI') && WP_CLI ) {
    // fix errors when running wp cli
    if(!isset($_SERVER['HTTP_HOST'])) {
        $_SERVER['HTTP_HOST'] = WP_HOME;
    }
}

/**
 * Class CmdManager
 *
 * @package \Bonnier\WP\ContentHub\Editor\Commands
 */
class CmdManager
{
    const CORE_CMD_NAMESPACE = 'contenthub editor';

    public static function register() {
        if ( defined('WP_CLI') && WP_CLI ) {
            Migrate::register();
            AdvancedCustomFields::register();
            Scaphold::register();
            Tags::register();
        }
    }
}
