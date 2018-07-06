<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\WP\ContentHub\Editor\ContenthubEditor;
use Illuminate\Support\Collection;
use WP_CLI_Command;

/**
 * Class BaseCmd
 *
 * @package \Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers
 */
class BaseCmd extends WP_CLI_Command
{
    protected function mapSites($callable)
    {
        $this->getSites()->each($callable);
    }

    protected function getSites(): Collection
    {
        return collect(ContenthubEditor::instance()->settings->get_languages())
            ->pluck('locale')
            ->map(function ($locale) {
                return ContenthubEditor::instance()->settings->get_site($locale);
            })->reject(function ($locale) {
                return is_null($locale);
            });
    }

    protected function getSite()
    {
        return $this->getSites()->first();
    }
}
