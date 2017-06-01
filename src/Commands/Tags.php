<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\WP\ContentHub\Editor\Plugin;
use Bonnier\WP\ContentHub\Editor\Repositories\SiteManager\TagRepository;
use WP_CLI;
use WP_CLI_Command;

/**
 * Class Tags
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class Tags extends WP_CLI_Command
{
    const CMD_NAMESPACE = 'tags';

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
            $this->importTagsForSite($settings->get_site($locale));
        });


        WP_CLI::success( 'Done importing tags' );
    }

    private function importTagsForSite($site = null) {
        if(is_null($site)) {
            return;
        }
        $this->mapTags($site, function($externalTag){
            collect($externalTag->name)->each(function($name, $languageCode) use($externalTag) {
                $this->saveTerm($name, $languageCode, $externalTag);
            });
        });
    }

    private function mapTags($site, $callable)
    {
        $tagQuery = TagRepository::find_by_brand_id($site->brand->id);

        while (isset($tagQuery->meta->pagination->links->next)) {
            collect($tagQuery->data)->each($callable);
            $tagQuery = TagRepository::find_by_brand_id($site->brand->id, $tagQuery->meta->pagination->current_page +1);
        }
    }

    private function saveTerm($name, $languageCode, $externalTag) {
        $contentHubId = $externalTag->content_hub_ids->{$languageCode};
        if($existingTermId = static::id_from_contenthub_id($contentHubId)) {
            // Term exists so we update it
            wp_update_term($existingTermId, 'post_tag', [
                'name' => $name,
                'slug' => sanitize_title_with_dashes($name)
            ]);
            pll_set_term_language($existingTermId, $languageCode);
            WP_CLI::success( 'Updated tag: ' . json_encode($externalTag->name, JSON_UNESCAPED_UNICODE) );
            return;
        }
        // Create new term
        $termInfo = wp_insert_term($name, 'post_tag');
        if(is_wp_error($termInfo))
            return;
        pll_set_term_language($termInfo['term_id'], $languageCode);
        update_term_meta($termInfo['term_id'], 'content_hub_id', $contentHubId);
        WP_CLI::success( 'Created tag: ' . json_encode($externalTag->name, JSON_UNESCAPED_UNICODE) );
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
