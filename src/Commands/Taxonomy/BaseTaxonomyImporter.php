<?php

namespace Bonnier\WP\ContentHub\Editor\Commands\Taxonomy;

use Bonnier\WP\ContentHub\Editor\Commands\BaseCmd;
use Bonnier\WP\ContentHub\Editor\Helpers\TermImportHelper;
use Bonnier\WP\ContentHub\Editor\ContenthubEditor;
use WP_CLI;

/**
 * Class BaseTaxonomyImporter
 *
 * @package \Bonnier\WP\ContentHub\Editor\Commands\Taxonomy
 */
class BaseTaxonomyImporter extends BaseCmd
{
    protected $taxonomy;
    protected $termImporter;
    protected $getTermCallback;

    protected function triggerImport($taxonomy, $getTermCallback)
    {
        $this->taxonomy = $taxonomy;
        $this->termImporter = new TermImportHelper($taxonomy);
        $this->getTermCallback = $getTermCallback;
        $this->mapTerms($this->get_site(), function ($externalTag) {
            $this->termImporter->importTermAndLinkTranslations($externalTag);
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
    protected function map_sites($callable)
    {
        $this->get_sites()->each($callable);
    }

    protected function get_sites()
    {
        return collect(ContenthubEditor::instance()->settings->get_languages())->pluck('locale')->map(function ($locale) {
            return ContenthubEditor::instance()->settings->get_site($locale);
        })->rejectNullValues();
    }

    protected function get_site()
    {
        return $this->get_sites()->first();
    }
}
