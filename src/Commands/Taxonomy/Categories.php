<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy;

use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\CategoryRepository;
use WP_CLI;

/**
 * Class Tags
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class Categories extends BaseTaxonomyImporter
{
    const CMD_NAMESPACE = 'categories';

    public static function register() {
        WP_CLI::add_command( CmdManager::CORE_CMD_NAMESPACE  . ' ' . static::CMD_NAMESPACE , __CLASS__ );
        define('PLL_ADMIN', true); // Tell Polylang to be in admin mode so that various term filters are loaded
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor categories import
     *
     */
    public function import() {

        $this->triggerImport('category', [CategoryRepository::class, 'find_by_brand_id']);

        WP_CLI::success( 'Done importing Categories' );
    }
}
