<?php

namespace Bonnier\WP\ContentHub\Editor;

use Bonnier\WP\ContentHub\Editor\ACF\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Helpers\CollectionHelper;
use Bonnier\WP\ContentHub\Editor\Helpers\CompositeHelper;
use Bonnier\WP\ContentHub\Editor\Helpers\PolylangConfig;
use Bonnier\WP\ContentHub\Editor\Http\Api\UpdateEndpointController;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\ContentHub\Editor\Models\WpUserProfile;
use Bonnier\WP\ContentHub\Editor\Settings\SettingsPage;

class ContenthubEditor
{
    /**
     * Text domain for translators
     */
    const TEXT_DOMAIN = 'contenthub-editor';

    const CLASS_DIR = 'src';

    const FLUSH_REWRITE_RULES_FLAG = 'contenthub-editor-permalinks-rewrite-flush-rules-flag';

    /**
     * @var object Instance of this class.
     */
    private static $instance;

    public $settings;

    /**
     * @var string Directory of this class.
     */
    public $dir;

    /**
     * @var string Basename of this class.
     */
    public $basename;

    /**
     * @var string Plugins directory for this plugin.
     */
    public $plugin_dir;

    /**
     * @var string Plugins url for this plugin.
     */
    public $plugin_url;

    /**
     * Do not load this more than once.
     */
    private function __construct()
    {
        // Set plugin file variables
        $this->dir = __DIR__;
        $this->basename = plugin_basename($this->dir);
        $this->plugin_dir = plugin_dir_path($this->dir);
        $this->plugin_url = plugin_dir_url($this->dir);

        // Load textdomain
        load_plugin_textdomain(
            self::TEXT_DOMAIN,
            false,
            dirname($this->basename) . '/languages'
        );

        $this->settings = new SettingsPage();
        new CollectionHelper; // Extends Collection with extra methods
        new MarkdownEditor;
        new CompositeHelper;

        // Register custom post type
        WpTaxonomy::register();
        WpComposite::register();
        WpAttachment::register();
        WpUserProfile::register();
        CmdManager::register();
        PolylangConfig::register();

        $updateEndpoint = new UpdateEndpointController();
        $updateEndpoint->register_routes();
    }

    /**
     * Returns the instance of this class.
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
            global $contenhub_editor;
            $contenhub_editor = self::$instance;

            /**
             * Run after the plugin has been loaded.
             */
            do_action('contenthub_editor_loaded');
        }

        return self::$instance;
    }
}
