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

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE  . ' ' . static::CMD_NAMESPACE, __CLASS__);
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor tags import
     *
     */
    public function import()
    {
        $this->triggerImport('post_tag', [TagRepository::class, 'find_by_brand_id']);

        WP_CLI::success('Done importing tags');
    }

    /**
     * Cleans failed tags imports
     *
     * ## OPTIONS
     * [--remove-empty]
     * : Whether or not to remove empty terms
     *
     * ## EXAMPLES
     *
     * wp contenthub editor tags clean
     *
     */
    public function clean($args, $assocArgs)
    {
        $this->clean_terms('post_tag', isset($assocArgs['remove-empty']));
    }
}
