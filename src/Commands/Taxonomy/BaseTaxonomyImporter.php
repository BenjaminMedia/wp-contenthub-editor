<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy;

use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\ContentHub\Editor\Plugin;
use WP_CLI_Command;
use WP_CLI;

/**
 * Class BaseTaxonomyImporter
 *
 * @package \Bonnier\WP\ContentHub\Editor\Commands\Taxonomy
 */
class BaseTaxonomyImporter extends WP_CLI_Command
{
    protected $taxonomy;
    protected $getTermCallback;

    protected function triggerImport($taxononmy, $getTermCallback) {
        $this->taxonomy = $taxononmy;
        $this->getTermCallback = $getTermCallback;
        $this->mapSites(function ($site){
            $this->mapTerms($site, function($externalTag){
                $this->importTermAndLinkTranslations($externalTag);
            });
        });
    }

    protected function mapSites($callable) {
        collect(Plugin::instance()->settings->get_languages())->pluck('locale')->map(function($locale) use($callable){
            return Plugin::instance()->settings->get_site($locale);
        })->rejectNullValues()->each($callable);
    }

    protected function mapTerms($site, $callable)
    {
        $termQuery = call_user_func($this->getTermCallback, $site->brand->id);

        while (!is_null($termQuery)) {
            WP_CLI::line( "Begning import of page: " . $termQuery->meta->pagination->current_page );
            collect($termQuery->data)->each($callable);
            if(isset($termQuery->meta->pagination->links->next)) {
                $nextPage = $termQuery->meta->pagination->current_page +1;
                $termQuery = call_user_func($this->getTermCallback, $site->brand->id, $nextPage);
                continue;
            }
            $termQuery = null;
        }
    }

    protected function importTermAndLinkTranslations($externalTerm) {
        $termIdsByLocale = collect($externalTerm->name)->map(function($name, $languageCode) use($externalTerm) {
            return [ $languageCode, $this->importTerm($name, $languageCode, $externalTerm) ];
        })->toAssoc()->rejectNullValues()->toArray(); // Creates an associative array with language code as key and term id as value
        pll_save_term_translations($termIdsByLocale);
        return $termIdsByLocale;
    }

    protected function importTerm($name, $languageCode, $externalTerm) {
        $contentHubId = $externalTerm->content_hub_ids->{$languageCode};
        $parentTermId = $this->getParentTermId($languageCode, $externalTerm->parent ?? null);
        $taxonomy = $externalTerm->vocabulary ? WpTaxonomy::get_taxonomy($externalTerm->vocabulary->content_hub_id) : $this->taxonomy;
        $_POST['term_lang_choice'] = $languageCode; // Needed by Polylang to allow same term name in different languages
        if($existingTermId = WpTerm::id_from_contenthub_id($contentHubId)) {
            // Term exists so we update it
            return WpTerm::update($existingTermId, $name, $languageCode, $contentHubId, $taxonomy, $parentTermId);
        }
        // Create new term
        WpTerm::create($name, $languageCode, $contentHubId, $taxonomy, $parentTermId);
    }

    protected function getParentTermId($languageCode, $externalCategory) {
        if(!isset($externalCategory->name->{$languageCode}))
            return null; // Make sure we only create the parent term if a translation exists for the language of the child term
        if($existingTermId = WpTerm::id_from_contenthub_id($externalCategory->content_hub_ids->{$languageCode}))
            return $existingTermId; // Term already exists so no need to create it again
        $this->importTermAndLinkTranslations($externalCategory)[$languageCode] ?? null;
    }

}
