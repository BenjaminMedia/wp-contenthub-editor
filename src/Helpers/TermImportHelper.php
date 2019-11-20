<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Exception;

class TermImportHelper
{
    protected $taxonomy;

    public function __construct($taxonomy)
    {
        $this->taxonomy = $this->getWpTaxonomy($taxonomy);
    }

    public function importTermAndLinkTranslations($externalTerm)
    {
        $termIdsByLocale = collect($externalTerm->name)->map(function ($name, $languageCode) use ($externalTerm) {
            if (!collect(pll_languages_list())->contains($languageCode)) {
                return null;
            }
            return [$languageCode, $this->importTerm($name, $languageCode, $externalTerm)];
            // Creates an associative array with language code as key and term id as value
        })->toAssoc()->rejectNullValues()->toArray();
        pll_save_term_translations($termIdsByLocale);
        return $termIdsByLocale;
    }

    public function deleteTerm(\WP_Term $term)
    {
        wp_delete_term($term->term_id, $this->taxonomy);
        return false;
    }

    protected function importTerm($name, $languageCode, $externalTerm)
    {
        $contentHubId = $externalTerm->content_hub_ids->{$languageCode};
        $whitealbumId = $externalTerm->whitealbum_id->{$languageCode} ?? null;
        $parentTermId = $this->getParentTermId($languageCode, $externalTerm->parent ?? null);
        $taxonomy = isset($externalTerm->vocabulary) ?
            WpTaxonomy::get_taxonomy($externalTerm->vocabulary->content_hub_id) :
            $this->taxonomy;
        // Needed by Polylang to allow same term name in different languages
        $_POST['term_lang_choice'] = $languageCode;

        $description = $externalTerm->description->{$languageCode} ?? null;
        $internal = $externalTerm->internal ?? false;

        if ($existingTermId = WpTerm::id_from_contenthub_id($contentHubId)) {
            $tag = null;
            if ($this->taxonomy === 'post_tag') {
                if (($posttag = get_tag($existingTermId)) && $posttag instanceof \WP_Term) {
                    $tag = $posttag;
                }
            }
            // Term exists so we update it
            return WpTerm::update(
                $existingTermId,
                $name,
                $languageCode,
                $contentHubId,
                $taxonomy,
                $parentTermId,
                $description,
                $internal,
                $whitealbumId
            );
        }
        // Create new term
        return WpTerm::create(
            $name,
            $languageCode,
            $contentHubId,
            $taxonomy,
            $parentTermId,
            $description,
            $internal,
            $whitealbumId
        );
    }

    protected function getParentTermId($languageCode, $externalCategory)
    {
        if (! isset($externalCategory->name->{$languageCode})) {
            // Make sure we only create the parent term if a translation exists for the language of the child term
            return null;
        }
        if ($existingTermId = WpTerm::id_from_contenthub_id($externalCategory->content_hub_ids->{$languageCode})) {
            // Term already exists so no need to create it again
            return $existingTermId;
        }
        return $this->importTermAndLinkTranslations($externalCategory)[$languageCode] ?? null;
    }

    public function deleteTermAndTranslations($externalTerm)
    {
        collect($externalTerm->content_hub_ids)->each(function ($contentHubId) {
            if ($wpTermId = WpTerm::id_from_contenthub_id($contentHubId) ?? null) {
                $wpTerm = get_term($wpTermId);
                if ($wpTerm instanceof \WP_Term) {
                    $this->deleteTerm($wpTerm);
                }
            }
        });
    }

    protected function getWpTaxonomy($taxonomy)
    {
        $wpTaxonomy = collect([
            'category' => 'category',
            'tag'      => 'post_tag',
            'post_tag' => 'post_tag'
        ])
            ->merge(collect(WpTaxonomy::get_custom_taxonomies()->pluck('machine_name')->keyBy(function ($value) {
                return $value;
            })))
            ->get($taxonomy);

        if (! $wpTaxonomy) {
            throw new Exception(sprintf('Unsupported taxonomy: %s', $taxonomy));
        }
        return $wpTaxonomy;
    }
}
