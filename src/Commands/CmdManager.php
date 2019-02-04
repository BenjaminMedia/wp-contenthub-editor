<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\Willow\Base\Commands\AuthorFix;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Categories;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Tags;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Vocabularies;

if (defined('WP_CLI') && WP_CLI) {
    // fix errors when running wp cli
    if (!isset($_SERVER['HTTP_HOST'])) {
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

    public static function register()
    {
        if (defined('WP_CLI') && WP_CLI) {
            if (!defined('PLL_ADMIN')) {
                define('PLL_ADMIN', true); // Tell Polylang to be in admin mode so that various term filters are loaded
            }
            AdvancedCustomFields::register();
            Tags::register();
            Categories::register();
            Vocabularies::register();
            WaContent::register();
            WaRedirectResolver::register();
            AuthorFix::register();
            Attachments::register();
        }
    }
}
