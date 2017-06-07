<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy;

use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\ContentHub\Editor\Plugin;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\VocabularyRepository;
use WP_Cli;

/**
 * Class Vocabularies
 *
 * @package \Bonnier\WP\ContentHub\Editor\Commands\Taxonomy
 */
class Vocabularies extends WP_CLI
{
    const CMD_NAMESPACE = 'vocabularies';

    public static function register() {
        WP_CLI::add_command( CmdManager::CORE_CMD_NAMESPACE  . ' ' . static::CMD_NAMESPACE , __CLASS__ );
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor vocabularies import
     *
     */
    public function import() {
        $vocabularies = collect();
        $this->mapSites(function($site) use($vocabularies) {
            $this->mapVocabularies($site, function ($vocabulary) use($vocabularies) {
                $vocabularies[$vocabulary->content_hub_id] = $vocabulary;
            });
        });
        WpTaxonomy::set_custom_taxonomies($vocabularies);
        WP_CLI::success( 'Done importing Vocabularies' );
    }

    protected function mapSites($callable) {
        collect(Plugin::instance()->settings->get_languages())->pluck('locale')->map(function($locale) use($callable){
            return Plugin::instance()->settings->get_site($locale);
        })->rejectNullValues()->each($callable);
    }

    protected function mapVocabularies($site, $callable)
    {
        $vocabularies = VocabularyRepository::find_by_app_id($site->app->id);

        while ($vocabularies) {
            WP_CLI::line( "Begning import of page: " . $vocabularies->meta->pagination->current_page );
            collect($vocabularies->data)->each($callable);
            if($vocabularies->meta->pagination->links->next) {
                $nextPage = $vocabularies->meta->pagination->current_page +1;
                $query = VocabularyRepository::find_by_app_id($site->app->id, $nextPage);
                continue;
            }
            $vocabularies = null;
        }
    }

    protected function importVocabulary($externalVocabulary){
        WpTaxonomy::add($externalVocabulary);
    }
}
