<?php

namespace Bonnier\WP\ContentHub\Editor\Commands;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\Cache\Models\Post as BonnierCachePost;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Helpers\AcfName;
use Bonnier\WP\ContentHub\Editor\Helpers\HtmlToMarkdown;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\Partials\EstimatedReadingTime;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpAuthor;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum\ContentRepository;
use Bonnier\WP\ContentHub\Editor\Repositories\WhiteAlbum\PanelRepository;
use Bonnier\WP\Cxense\Models\Post as CxensePost;
use Illuminate\Support\Collection;
use WP_CLI;
use WP_Post;
use WP_Term;
use WP_User;

/**
 * Class AdvancedCustomFields
 *
 * @package \Bonnier\WP\ContentHub\Commands
 */
class WaPanel extends BaseCmd
{
    const CMD_NAMESPACE = 'wa panel';

    private $repository = null;
    private $isRefreshing = false;

    public static function register()
    {
        WP_CLI::add_command(CmdManager::CORE_CMD_NAMESPACE . ' ' . static::CMD_NAMESPACE, __CLASS__);
    }


    /**
     * Imports panels from WhiteAlbum
     *
     * ## OPTIONS
     *
     * [--id=<id>]
     * : The id of a single panel to import.
     *
     *
     * ## EXAMPLES
     * wp contenthub editor wa panel import
     *
     * @param $args
     * @param $assocArgs
     *
     * @throws \Exception
     */
    public function import($args, $assocArgs)
    {
        error_reporting(E_ALL); // Enable all error reporting to make sure we catch potential issues

        $this->disableHooks(); // Disable various hooks and filters during import

        $this->repository = new PanelRepository($assocArgs['locale'] ?? null);
        if ($contentId = $assocArgs['id'] ?? null) {
            $this->importPanel($this->repository->findById($contentId));
        } else {
            $this->repository->mapAll(function ($waContent) {
                $this->importPanel($waContent);
            });
        }
    }

    private function importPanel($waContent)
    {
        $this->fixNonceErrors();

        if (!$waContent || empty($waContent->categories)) {
            return;
        }

        WP_CLI::line(sprintf(
            'Beginning import of: %s id: %s',
            $waContent->title,
            $waContent->id
        ));

        $widgets = $this->formatWidgetGroups($waContent);

        if (($categoryId = WpTerm::id_from_whitealbum_id($waContent->categories[0])) && $category = get_category($categoryId)) {
            $this->saveWidgets($category, $widgets);
        }

        WP_CLI::success('imported: ' . $waContent->title);
    }



    private function formatWidgetGroups($waContent): ?Collection
    {
        return collect($waContent->widget_groups)
            ->map(function ($widgetGroup) {
                if (!empty($widgetGroup->title)) { // Prepend title as seo widget
                    $widgetGroup->widgets = array_prepend($widgetGroup->widgets, (object)[
                     'type' => 'Widgets::Text',
                     'properties' => (object)[
                         'title' => $widgetGroup->title,
                         'text' => null
                     ]
                   ]);
                }
                return $widgetGroup;
            })
            ->pluck('widgets')
            ->flatten(1)
            ->map(function ($waWidget) {
                return collect([
                    'type' => collect([ // Map the type
                        'Widgets::Text' => 'seo_text',
                        'Widgets::Standard' => 'teaser_list',
                        'Widgets::NewsletterSignup' => 'newsletter',
                        'Widgets::FullWidthContent' => 'teaser_list',
                        'Widgets::RotatorWithThumbnails' => 'teaser_list',
                    ])
                        ->get($waWidget->type, $waWidget->type),
                    'display_format' => collect([ // Map the type
                        'Widgets::Standard' => 'default',
                        'Widgets::RotatorWithThumbnails' => 'presentation',
                    ])
                        ->get($waWidget->type, 'default'),
                ])
                    ->merge($waWidget->properties)// merge properties
                    ->merge($waWidget->image ?? null);
            })
            ->itemsToObject()->map(function ($content) {
                return $this->fixFaultyImageFormats($content);
            });
    }

    private function saveWidgets(WP_Term $category, Collection $widgets)
    {
        $sortBySkipAmount = [];
        $content = $widgets
            ->map(function ($widget) use ($category, &$sortBySkipAmount) {
                if ($widget->type === 'seo_text') {
                    return [
                        'acf_fc_layout'  => $widget->type,
                        'title' => $widget->title ?? null,
                        'description' => HtmlToMarkdown::parseHtml($widget->text),
                    ];
                }
                if ($widget->type === 'teaser_list' && $widget->category_id) {
                    $teaserListWidget = [
                        'acf_fc_layout'  => $widget->type,
                        'display_format' => $widget->display_format,
                        'category' =>  WpTerm::id_from_whitealbum_id($widget->category_id),
                        'sort_by' => 'custom',
                        AcfName::FIELD_TEASER_AMOUNT => $widget->number_of_contents,
                        AcfName::FIELD_SKIP_TEASERS_AMOUNT => $sortBySkipAmount[$widget->category_id] ?? 0,
                    ];
                    if (!isset($sortBySkipAmount[$widget->category_id])) {
                        $sortBySkipAmount[$widget->category_id] = $widget->number_of_contents;
                    } else {
                        $sortBySkipAmount[$widget->category_id] += $widget->number_of_contents;
                    }
                    return $teaserListWidget;
                }
                if ($widget->type === 'newsletter') {
                    return [
                        'acf_fc_layout'  => $widget->type
                    ];
                }
                return null;
            })
            ->rejectNullValues()
            ->push([
                'acf_fc_layout'  => 'teaser_list',
                'display_format' => 'default',
                'sort_by' => 'custom',
                AcfName::FIELD_TEASER_AMOUNT => 12,
                AcfName::FIELD_SKIP_TEASERS_AMOUNT => $sortBySkipAmount[WpTerm::whiteablum_id($category->term_id)],
                'pagination' => true,
                'category' => $category->term_id
            ]);

        update_field('field_5bb318f2ffcef', $content->toArray(), $category);
    }

    private function disableHooks()
    {
        // Disable generation of image sizes on import to speed up the precess
        add_filter('intermediate_image_sizes_advanced', function ($sizes) {
            return [];
        });

        // Disable on save hook to prevent call to content hub, Cxense and Bonnier Cache Manager
        remove_action('save_post', [WpComposite::class, 'on_save'], 10);
        remove_action('publish_to_publish', [BonnierCachePost::class, 'update_post'], 10);
        remove_action('draft_to_publish', [BonnierCachePost::class, 'publishPost'], 10);
        remove_action('transition_post_status', [CxensePost::class, 'post_status_changed'], 10);
    }

    private function fixFaultyImageFormats($content)
    {
        if ($content->type === 'image'
            && ($extension = pathinfo($content->url, PATHINFO_EXTENSION))
            && in_array($extension, ['psd'])
        ) {
            // If we find the image extension to be in the blacklist then we tell imgix to return as png format
            $content->url .= '?fm=png';
        }
        return $content;
    }

    private function fixNonceErrors()
    {
        wp_set_current_user(1); // Make sure we act as admin to allow upload of all file types
        $_REQUEST['_pll_nonce'] = wp_create_nonce('pll_language');
    }
}
