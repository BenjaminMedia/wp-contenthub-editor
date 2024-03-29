<?php
/**
 * Do not edit this file. Edit the config files found in the config/ dir instead.
 * This file is required in the root directory so WordPress can find it.
 * WP is hardcoded to look in its own directory or one directory up for wp-config.php.
 */
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/** @var string Directory containing all of the site's files */
$root_dir = dirname(__DIR__);

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', 'testing');

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/wp-content');
define('WP_CONTENT_DIR', $root_dir . CONTENT_DIR);

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $root_dir . '/wordpress/');
}
