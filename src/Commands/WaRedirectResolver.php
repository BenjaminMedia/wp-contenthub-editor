<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum\ContentRepository;
use Bonnier\WP\Redirect\Http\BonnierRedirect;
use WP_CLI;

class WaRedirectResolver extends BaseCmd
{
    const CMD_NAMESPACE = 'wa redirect resolve';

    /** @var int */
    private $delay;

    /** @var ContentRepository */
    private $repository;

    private $failedRedirects = [];

    public static function register()
    {
        WP_CLI::add_command(sprintf(
            '%s %s',
            CmdManager::CORE_CMD_NAMESPACE,
            self::CMD_NAMESPACE
        ), __CLASS__);
    }

    /**
     * Creates redirects for old White Album articles
     * that has a different url than on Willow sites.
     *
     * ## OPTIONS
     * [--delay=<seconds>]
     * : How long between each request to White Album
     * ---
     * default: 1
     * ---
     *
     * ## EXAMPLES
     * wp contenthub editor wa redirect resolve run
     */
    public function run($args, $assoc_args)
    {
        $this->delay = $assoc_args['delay'] ?? 1;
        $this->repository = new ContentRepository();

        WP_CLI::line('Fetching composite ids and white album ids from database...');
        $compositeIds = $this->getCompositeIds();

        $progress = WP_CLI\Utils\make_progress_bar('Resolving redirects...', $compositeIds->count());
        $compositeIds->each(function (int $whiteAlbumID, int $compositeID) use (&$progress) {
            if (get_post_status($compositeID) === 'publish') {
                $this->resolveRedirect($whiteAlbumID, $compositeID);
                sleep($this->delay);
            }
            $progress->tick();
        });
        $progress->finish();

        if ($this->failedRedirects) {
            collect($this->failedRedirects)->each(function ($message) {
                WP_CLI::warning($message);
            });
        }

        WP_CLI::line('-----------------------');
        WP_CLI::line('|                     |');
        WP_CLI::line('|         DONE        |');
        WP_CLI::line('|      ----------     |');
        WP_CLI::line('| Redirects resolved! |');
        WP_CLI::line('|                     |');
        WP_CLI::line('-----------------------');
    }

    private function getCompositeIds()
    {
        global $wpdb;
        $result = $wpdb->get_results(
            "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'white_album_id'"
        );
        return collect($result)->mapWithKeys(function ($row) {
            return [$row->post_id => $row->meta_value];
        });
    }

    private function resolveRedirect(int $whiteAlbumID, int $compositeID)
    {
        if (!$compositePath = parse_url(get_permalink($compositeID), PHP_URL_PATH)) {
            $this->failedRedirects[] = [
                sprintf('Could not get path for composite id %s', $compositeID),
            ];
            return;
        }

        if (!$whiteAlbumPath = $this->getWhiteAlbumPath($whiteAlbumID)) {
            $this->failedRedirects[] = [
                sprintf(
                    'Could not get path for white album id %s - composite id %s',
                    $whiteAlbumID,
                    $compositeID
                )
            ];
            return;
        }

        if ($compositePath !== $whiteAlbumPath) {
            $locale = LanguageProvider::getPostLanguage($compositeID);
            if (!BonnierRedirect::addRedirect(
                $whiteAlbumPath,
                $compositePath,
                $locale,
                'WA-WILLOW:REDIRECTS',
                $compositeID
            )) {
                $this->failedRedirects[] = [
                    sprintf(
                        'Failed creating redirect for composite %s: \'%s\' -> \'%s\'',
                        $compositeID,
                        $whiteAlbumPath,
                        $compositePath
                    )
                ];
            }
        }
        return;
    }

    private function getWhiteAlbumPath(int $whiteAlbumID)
    {
        if ($article = $this->repository->findById($whiteAlbumID, ContentRepository::ARTICLE_RESOURCE)) {
            return $article->widget_content->path ?? null;
        } elseif ($gallery = $this->repository->findById($whiteAlbumID, ContentRepository::GALLERY_RESOURCE)) {
            return $gallery->widget_content->path ?? null;
        }

        return null;
    }
}
