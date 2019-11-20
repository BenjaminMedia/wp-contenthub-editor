<?php

namespace Bonnier\WP\ContentHub\Editor;

use Bonnier\WP\ContentHub\Editor\ACF\ImageHotSpotCoordinates;
use Bonnier\WP\ContentHub\Editor\ACF\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Helpers\CollectionHelper;
use Bonnier\WP\ContentHub\Editor\Helpers\CompositeHelper;
use Bonnier\WP\ContentHub\Editor\Helpers\PolylangConfig;
use Bonnier\WP\ContentHub\Editor\Http\Api\UpdateEndpointController;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpPage;
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
    public $pluginDir;

    /**
     * @var string Plugins url for this plugin.
     */
    public $pluginUrl;

    /**
     * Do not load this more than once.
     */
    private function __construct()
    {
        // Set plugin file variables
        $this->dir = __DIR__;
        $this->basename = plugin_basename($this->dir);
        $this->pluginDir = plugin_dir_path($this->dir);
        $this->pluginUrl = plugin_dir_url($this->dir);

        // Load textdomain
        load_plugin_textdomain(
            self::TEXT_DOMAIN,
            false,
            dirname($this->basename) . '/languages'
        );

        add_action('admin_enqueue_scripts', [$this, 'loadAdminScripts']);

        $this->settings = new SettingsPage();
        CollectionHelper::register(); // Extends Collection with extra methods
        new MarkdownEditor;
        new ImageHotSpotCoordinates();
        new CompositeHelper;

        // Register custom post type
        WpTaxonomy::register();
        WpPage::register();
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
            /**
             * Run after the plugin has been loaded.
             */
            do_action('contenthub_editor_loaded');
        }

        return self::$instance;
    }

    public function loadAdminScripts()
    {
        wp_register_style(
            'contenthub_editor_stylesheet',
            sprintf('%s/css/admin.css', trim($this->pluginUrl, '/')),
            false,
            filemtime(sprintf('/%s/css/admin.css', trim($this->pluginDir, '/')))
        );
        wp_enqueue_style('contenthub_editor_stylesheet');
        WpTaxonomy::admin_enqueue_scripts();
    }
}
