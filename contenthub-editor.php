<?php
/**
 * Plugin Name: ContentHub Editor
 * Version: 0.0.1
 * Plugin URI: https://github.com/BenjaminMedia/contenthub-editor
 * Description: This plugin integrates Bonnier Contenthub and adds a custom post type Composite
 * Author: Bonnier - Alf Henderson
 * License: GPL v3
 */

namespace Bonnier\WP\ContentHub\Editor;

use Bonnier\WP\ContentHub\Editor\Commands\CmdManager;
use Bonnier\WP\ContentHub\Editor\Models\Composite;
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

class Plugin
{
    /**
     * Text domain for translators
     */
    const TEXT_DOMAIN = 'contenthub-editor';

    const CLASS_DIR = 'src';

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

        // Register custom post type
        Composite::register();
        CmdManager::register();
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

add_action('plugins_loaded', __NAMESPACE__ . '\instance', 0);
