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

    public static function register()
    {
        try {
            WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE . ' ' . static::CMD_NAMESPACE, __CLASS__);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor categories import
     *
     */
    public function import()
    {
        $this->triggerImport('category', [CategoryRepository::class, 'find_by_brand_id']);

        WP_CLI::success('Done importing Categories');
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor categories sync
     *
     */
    public function sync()
    {
        $this->triggerSync('category', [CategoryRepository::class, 'find_by_content_hub_id']);

        WP_CLI::success('Done importing Categories');
    }

    /**
     * Cleans failed category imports
     *
     * ## OPTIONS
     * [--remove-empty]
     * : Whether or not to remove empty terms
     *
     * ## EXAMPLES
     *
     * wp contenthub editor categories clean
     * @param $args
     * @param $assocArgs
     */
    public function clean($args, $assocArgs)
    {
        $this->clean_terms('category', isset($assocArgs['remove-empty']));
    }
}
