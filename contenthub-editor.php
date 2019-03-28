<?php
/**
 * Plugin Name: ContentHub Editor
 * Version: 5.1.0
 * Plugin URI: https://github.com/BenjaminMedia/contenthub-editor
 * Description: This plugin integrates Bonnier Contenthub and adds a custom post type Composite
 * Author: Bonnier - Alf Henderson
 * License: GPL v3
 */

// Do not access this file directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return \Bonnier\WP\ContentHub\Editor\ContenthubEditor $instance returns an instance of the plugin
 */
function loadContenthubEditor()
{
    return \Bonnier\WP\ContentHub\Editor\ContenthubEditor::instance();
}

// Register a flag to flush the rewrite rules after the custom rules have been added
register_activation_hook(__FILE__, function () {
    update_option(\Bonnier\WP\ContentHub\Editor\ContenthubEditor::FLUSH_REWRITE_RULES_FLAG, true);
});

// Flush rewrite rules to generate new permalinks when plugin is deactivated
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

// If the plugin is currently being deactivated we do no want to register our rewrite rules, so we abort.
if (isset($_GET['action'], $_GET['plugin']) &&
    'deactivate' === $_GET['action'] &&
    plugin_basename(__FILE__) === $_GET['plugin']
) {
    return;
}

add_action('plugins_loaded', 'loadContenthubEditor', 0);
