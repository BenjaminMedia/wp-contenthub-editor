<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers;

use Bonnier\WP\ContentHub\Editor\Helpers\SlugHelper;
use WP_CLI;

/**
 * Class WpTerm
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models
 */
class WpTerm
{
    public static function create($name, $languageCode, $contentHubId, $taxonomy, $parentTermId = null) {
        $createdTerm = wp_insert_term($name, $taxonomy, [
            'parent' => $parentTermId,
            'slug' => SlugHelper::create_slug($name)
        ]);
        if(is_wp_error($createdTerm)) {
            WP_CLI::warning( "Failed creating $taxonomy: $name Locale: $languageCode content_hub_id: $contentHubId Errors: "
                . json_encode($createdTerm->errors, JSON_UNESCAPED_UNICODE));
            return null;
        }
        pll_set_term_language($createdTerm['term_id'], $languageCode);
        update_term_meta($createdTerm['term_id'], 'content_hub_id', $contentHubId);
        WP_CLI::success( "Created $taxonomy: $name Locale: $languageCode content_hub_id: $contentHubId" );
        return $createdTerm['term_id'];
    }

    public static function update($existingTermId, $name, $languageCode, $contentHubId, $taxonomy, $parentTermId = null) {
        $updatedTerm = wp_update_term($existingTermId, $taxonomy, [
            'name' => $name,
            'parent' => $parentTermId,
            'slug' => SlugHelper::create_slug($name)
        ]);
        if(is_wp_error($updatedTerm)) {
            WP_CLI::warning( "Failed updating $taxonomy: $name Locale: $languageCode content_hub_id: $contentHubId Errors: "
                . json_encode($updatedTerm->errors, JSON_UNESCAPED_UNICODE));
            return null;
        }
        pll_set_term_language($existingTermId, $languageCode);
        update_term_meta($existingTermId, 'content_hub_id', $contentHubId);
        WP_CLI::success( "Updated $taxonomy: $name Locale: $languageCode content_hub_id: $contentHubId" );
        return $existingTermId;
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
