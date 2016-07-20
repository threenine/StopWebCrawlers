<?php
/*
 Plugin Name: Stop Web Crawlers
 Plugin URI: http://threenine.co.uk/product/stop-web-crawlers/
 Description: Blocks traffic referrer spam bots
 Version: 1.0.3
 Author: Three Nine Consulting
 Author URI: http://threenine.co.uk
 License: GPLv2 or later
 */



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('SWC', '0.2' );
define('SWCPATH', plugin_dir_path(__file__) );
define('SWCURL', plugin_dir_url(__file__));
define('SWCDOMAIN', get_site_url() );


// Add settings link on plugin page
function swc_plugin_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=Stop-Web-Crawlers">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'swc_plugin_settings_link' );

function swc_start() {
	$wp_bs_loaded = new Stop_Web_Crawlers ();
}

require_once (SWCPATH . "settings/load-plugin.php");
require_once (SWCPATH . "settings/options/plugin_options_tabbed.php");
require_once (SWCPATH . "functions/functions.php");
require_once(ABSPATH . 'wp-includes/pluggable.php');

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

require dirname( __FILE__ ) . '/includes/list-tables/class-swc-list-table.php';

add_action( 'admin_menu', 'swc_add_menu_items' );

function swc_add_menu_items() {
	add_submenu_page(
			'stop-web-crawlers', // $parent_slug
			'Web Crawlers Table', // string $page_title
			'Web Crawlers Table', // string $menu_title
			'manage_options', // string $capability
			'web-crawlers-table',
			'swc_render_list_page' );
}


add_action( 'plugins_loaded', 'swc_start' );

class Stop_Web_Crawlers {

	public function __construct() {
		add_action( 'parse_request', array( $this, 'swc_execute' ) );
		
	}

	public function swc_execute() {
		
		global $wpdb;
		$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : false;

		if ( empty( $referer ) ) {
			return;
		}

		$referer = strtolower($referer);
		$table_name = $wpdb->prefix . "swc_blacklist";
		
		$sql = "SELECT boturl FROM " . $table_name . " WHERE botstate = 'Enabled'";
		$bots = $wpdb->get_results($sql) or die(mysql_error());

		foreach($bots as $row) {
			if ( strpos( $referer, $row->boturl ) !== false ) {
				wp_die( '', '', array( 'response' => 403 ) );
				exit;
			}
		}
	}
	
	
}
	function swc_render_list_page() {
		$test_list_table = new swc_List_Table();
		$test_list_table->prepare_items();
		require dirname( __FILE__ ) . '/includes/list-tables/page.php';
	}
	register_activation_hook( __FILE__, 'swc_plugin_activated');
	add_action( 'plugins_loaded', 'swc_plugin_db_update' );

?>
