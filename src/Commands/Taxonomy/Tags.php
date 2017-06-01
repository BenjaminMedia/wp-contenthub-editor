<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy;

use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\TagRepository;
use WP_CLI;

/**
 * Class Tags
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class Tags extends BaseTaxonomyImporter
{
    const CMD_NAMESPACE = 'tags';

    public static function register() {
        WP_CLI::add_command( CmdManager::CORE_CMD_NAMESPACE  . ' ' . static::CMD_NAMESPACE , __CLASS__ );
        define(PLL_ADMIN, true); // Tell Polylang to be in admin mode so that various term filters are loaded
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor tags import
     *
     */
    public function import() {

        $this->triggerImport('post_tag', [TagRepository::class, 'find_by_brand_id']);

        WP_CLI::success( 'Done importing tags' );
    }
}
