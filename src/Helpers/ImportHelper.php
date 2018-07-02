<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;


class ImportHelper
{
    protected function triggerImport($taxononmy, $getTermCallback)
    {
        $this->taxonomy = $taxononmy;
        $this->getTermCallback = $getTermCallback;
        $this->mapTerms($this->get_site(), function ($externalTag) {
            $this->importTermAndLinkTranslations($externalTag);
        });
    }

    protected function mapTerms($site, $callable)
    {
        $termQuery = call_user_func($this->getTermCallback, $site->brand->id);

        while (!is_null($termQuery)) {
            WP_CLI::line("Beginning import of page: " . $termQuery->meta->pagination->current_page);
            collect($termQuery->data)->each($callable);
            if (isset($termQuery->meta->pagination->links->next)) {
                $nextPage = $termQuery->meta->pagination->current_page +1;
                $termQuery = call_user_func($this->getTermCallback, $site->brand->id, $nextPage);
                continue;
            }
            $termQuery = null;
        }
    }

    protected function importTermAndLinkTranslations($externalTerm)
    {
        $termIdsByLocale = collect($externalTerm->name)->map(function ($name, $languageCode) use ($externalTerm) {
            return [ $languageCode, $this->importTerm($name, $languageCode, $externalTerm) ];
            // Creates an associative array with language code as key and term id as value
        })->toAssoc()->rejectNullValues()->toArray();
        pll_save_term_translations($termIdsByLocale);
        return $termIdsByLocale;
    }

    protected function importTerm($name, $languageCode, $externalTerm)
    {
        $contentHubId = $externalTerm->content_hub_ids->{$languageCode};
        $parentTermId = $this->getParentTermId($languageCode, $externalTerm->parent ?? null);
        $taxonomy = isset($externalTerm->vocabulary) ?
            WpTaxonomy::get_taxonomy($externalTerm->vocabulary->content_hub_id) :
            $this->taxonomy;
        // Needed by Polylang to allow same term name in different languages
        $_POST['term_lang_choice'] = $languageCode;

        $description = $externalTerm->description->{$languageCode} ?? null;
        $internal = $externalTerm->internal ?? false;

        if ($existingTermId = WpTerm::id_from_contenthub_id($contentHubId)) {
            // Term exists so we update it
            return WpTerm::update(
                $existingTermId,
                $name,
                $languageCode,
                $contentHubId,
                $taxonomy,
                $parentTermId,
                $description,
                $internal
            );
        }
        // Create new term
        WpTerm::create($name, $languageCode, $contentHubId, $taxonomy, $parentTermId, $description, $internal);
    }

    protected function getParentTermId($languageCode, $externalCategory)
    {
        if (!isset($externalCategory->name->{$languageCode})) {
            // Make sure we only create the parent term if a translation exists for the language of the child term
            return null;
        }
        if ($existingTermId = WpTerm::id_from_contenthub_id($externalCategory->content_hub_ids->{$languageCode})) {
            // Term already exists so no need to create it again
            return $existingTermId;

        }
        return $this->importTermAndLinkTranslations($externalCategory)[$languageCode] ?? null;
    }

    public function clean_terms($taxononmy, $removeEmpty = false)
    {
        collect(get_terms([
            'taxonomy'   => $taxononmy,
            'hide_empty' => false,
            'number'     => 0
        ]))->filter(function (\WP_Term $term) use ($removeEmpty) {
            if (!get_term_meta($term->term_id, 'content_hub_id', true) || $term->count === 0 && $removeEmpty) {
                return true;
            }
            return false;
        })->pipe(function ($terms) {
            WP_CLI::line('A total of: ' . $terms->count() . ' will be removed');
            return $terms;
        })->each(function (\WP_Term $term) use ($taxononmy) {
            wp_delete_term($term->term_id, $taxononmy);
            WP_CLI::line('Removed term: ' . $term->term_id);
        });

        WP_CLI::success('Done cleaning ' . $taxononmy);
    }
}
