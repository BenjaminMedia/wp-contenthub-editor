<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\WP\ContentHub\Editor\Plugin;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\CategoryRepository;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\TagRepository;
use WP_CLI;
use WP_CLI_Command;

/**
 * Class Tags
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class Categories extends WP_CLI_Command
{
    const CMD_NAMESPACE = 'categories';

    protected $languages;

    public static function register() {
        WP_CLI::add_command( CmdManager::CORE_CMD_NAMESPACE  . ' ' . static::CMD_NAMESPACE , __CLASS__ );
    }

    /**
     * Imports taxonomy from site-manager
     *
     * ## EXAMPLES
     *
     * wp contenthub editor taxonomy import
     *
     */
    public function import() {

        $settings = Plugin::instance()->settings;
        $this->languages = collect($settings->get_languages());
        $this->languages->pluck('locale')->each(function($locale) use($settings){
            $this->importCategoriesForSite($settings->get_site($locale));
        });


        WP_CLI::success( 'Done importing categories' );
    }

    private function importCategoriesForSite($site = null) {
        if(is_null($site)) {
            return;
        }
        $this->mapCategories($site, function($externalTerm){
            $this->importTermAndLinkTranslations($externalTerm);
        });
    }

    private function mapCategories($site, $callable)
    {
        $tagQuery = CategoryRepository::find_by_brand_id($site->brand->id);

        while (isset($tagQuery->meta->pagination->links->next)) {
            collect($tagQuery->data)->each($callable);
            $tagQuery = CategoryRepository::find_by_brand_id($site->brand->id, $tagQuery->meta->pagination->current_page +1);
        }
    }

    private function importTermAndLinkTranslations($externalTerm) {
        $termIdsByLocale = collect($externalTerm->name)->map(function($name, $languageCode) use($externalTerm) {
            return [ $languageCode, $this->importTerm($name, $languageCode, $externalTerm) ];
        })->rejectNullValues()->toAssoc()->toArray(); // Creates an associative array with language code as key and term id as value
        pll_save_term_translations($termIdsByLocale);
        return $termIdsByLocale;
    }

    private function importTerm($name, $languageCode, $externalCategory) {
        $contentHubId = $externalCategory->content_hub_ids->{$languageCode};
        $parentTermId = $this->getParentTermId($languageCode, $externalCategory->parent);
        if($existingTermId = static::id_from_contenthub_id($contentHubId)) {
            // Term exists so we update it
            return $this->updateTerm($existingTermId, $parentTermId, $name, $languageCode, $contentHubId);
        }
        // Create new term
        return $this->createTerm($parentTermId, $name, $languageCode, $contentHubId);
    }

    private function getParentTermId($languageCode, $externalCategory) {
        if(!isset($externalCategory->name->{$languageCode}))
            return null; // Make sure we only create the parent term if a translation exists for the language of the child term
        if($existingTermId = static::id_from_contenthub_id($externalCategory->content_hub_ids->{$languageCode}))
            return $existingTermId; // Term already exists so no need to create it again
        $this->importTermAndLinkTranslations($externalCategory)[$languageCode] ?? null;
    }

    private function updateTerm($existingTermId, $parentTermId, $name, $languageCode, $contentHubId) {
        $updatedTerm = wp_update_term($existingTermId, 'category', [
            'name' => $name,
            'parent' => $parentTermId
        ]);
        if(is_wp_error($updatedTerm)) {
            WP_CLI::warning( 'Failed Updating category: ' . $name . ' Locale: ' . $languageCode  .  ' content_hub_id: '  . $contentHubId  .
                ' Errors: ' . json_encode($updatedTerm->errors, JSON_UNESCAPED_UNICODE));
            return null;
        }
        pll_set_term_language($existingTermId, $languageCode);
        update_term_meta($existingTermId, 'content_hub_id', $contentHubId);
        WP_CLI::success( 'Updated category: ' . $name . ' Locale: ' . $languageCode  .  ' content_hub_id: '  . $contentHubId);
        return $existingTermId;
    }

    private function createTerm($parentTermId, $name, $languageCode, $contentHubId) {
        $createdTerm = wp_insert_term($name, 'category', [
            'parent' => $parentTermId
        ]);
        if(is_wp_error($createdTerm)) {
            WP_CLI::warning( 'Failed creating category: ' . $name . ' Locale: ' . $languageCode  .  ' content_hub_id: '  . $contentHubId .
                ' Errors: ' . json_encode($createdTerm->errors, JSON_UNESCAPED_UNICODE));
            return null;
        }
        pll_set_term_language($createdTerm['term_id'], $languageCode);
        update_term_meta($createdTerm['term_id'], 'content_hub_id', $contentHubId);
        WP_CLI::success( 'Created category: ' . $name . ' Locale: ' . $languageCode  .  ' content_hub_id: '  . $contentHubId);
        return $createdTerm['term_id'];
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public static function id_from_contenthub_id($id) {
        global $wpdb;
        return $wpdb->get_var(
            $wpdb->prepare("SELECT term_id FROM wp_termmeta WHERE meta_key=%s AND meta_value=%s", 'content_hub_id', $id)
        );
    }


}
