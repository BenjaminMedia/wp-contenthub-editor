<?php
/**
 * Plugin Name: ContentHub Editor
 * Version: 1.1.19
 * Plugin URI: https://github.com/BenjaminMedia/contenthub-editor
 * Description: This plugin integrates Bonnier Contenthub and adds a custom post type Composite
 * Author: Bonnier - Alf Henderson
 * License: GPL v3
 */

namespace Bonnier\WP\ContentHub\Editor;

use Bonnier\WP\ContentHub\Editor\ACF\MarkdownEditor;
use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Helpers\CollectionHelper;
use Bonnier\WP\ContentHub\Editor\Helpers\PolylangConfig;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\ContentHub\Editor\Settings\SettingsPage;

// Do not access this file directly
if (!defined('ABSPATH')) {
    exit;
}

// Handle autoload so we can use namespaces
spl_autoload_register(function ($className) {
    if (strpos($className, __NAMESPACE__) !== false) {
        $classPath = str_replace( // Replace namespace with directory separator
            "\\",
            DIRECTORY_SEPARATOR,
            str_replace( // Replace namespace with path to class dir
                __NAMESPACE__,
                __DIR__ . DIRECTORY_SEPARATOR . Plugin::CLASS_DIR,
                $className
            )
        );
        require_once($classPath . '.php');
    }
});

// Load dependencies from includes
require_once( __DIR__ . '/includes/vendor/autoload.php' );

class Plugin
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
     * @var string Filename of this class.
     */
    public $file;

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
        $this->file = __FILE__;
        $this->basename = plugin_basename($this->file);
        $this->plugin_dir = plugin_dir_path($this->file);
        $this->plugin_url = plugin_dir_url($this->file);

        // Load textdomain
        load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname($this->basename) . '/languages');

        $this->settings = new SettingsPage();
        new CollectionHelper; // Extends Collection with extra methods
        new MarkdownEditor;
        // Register custom post type
        WpTaxonomy::register();
        WpComposite::register();
        WpAttachment::register();
        CmdManager::register();
        PolylangConfig::register();
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

/**
 * @return Plugin $instance returns an instance of the plugin
 */
function instance()
{
    return Plugin::instance();
}

// Register a flag to flush the rewrite rules after the custom rules have been added
register_activation_hook( __FILE__, function(){
    update_option( Plugin::FLUSH_REWRITE_RULES_FLAG, true );
});

// Flush rewrite rules to generate new permalinks when plugin is deactivated
register_deactivation_hook( __FILE__, 'flush_rewrite_rules');

// If the plugin is currently being deactivated we do no want to register our rewrite rules, so we abort.
if (isset($_GET['action'], $_GET['plugin']) && 'deactivate' === $_GET['action'] && plugin_basename(__FILE__) === $_GET['plugin'])
    return;

add_action('plugins_loaded', __NAMESPACE__ . '\instance', 0);
