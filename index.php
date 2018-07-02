<?php
/*
Plugin Name: Popsigns Collections
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: Woocommerce plugin that enables live-preview product manipulating by customers.
Version:     1.0
Author:      Myles Hyson
Author URI:  https://myleshyson.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vuecommerce
Domain Path: /languages
*/

/**
 * Require Composer Autoload File
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Set Global Plugin Directory Variable
 * @var string
 */
$pluginDir = plugin_dir_path(__FILE__);
$pluginUrl = plugin_dir_url(__FILE__);

/**
 * Load our tables to the database whenever the plugin is activated
 */
 global $wpdb;

$table_name = $wpdb->prefix . 'vue_forms';

if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    new App\Database\DatabaseHandler();
}

new App\Init();

/**
 * Register Our Controllers
 */
new App\Controllers\AdminController();
new App\Controllers\ProductController();
new App\Controllers\FrontEndController();
new App\Controllers\OrderController();
