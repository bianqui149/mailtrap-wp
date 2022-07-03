<?php
/**
 * Plugin Name: WP Plugin Mailtrap
 * Plugin URI: 
 * Description: This plugin conect the smtp service with mailtrap.
 * Version: 0.1
 * Author: Julian Bianqui.
 * Author URI: 
 *
 * @package wp.plugin-mailtrap
 * @version 0.1
 * @author Julian Bianqui <bianquijulian@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_PLUGIN_MAILTRAP' ) ) {
	define( 'WP_PLUGIN_MAILTRAP', plugin_dir_path( __FILE__ ) );
}

require_once( WP_PLUGIN_MAILTRAP . '/private-scripts/class-wp-plugin-mailtrap-admin-settings.php' );
