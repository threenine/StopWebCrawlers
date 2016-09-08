<?php 
/*
       * Plugin Name: Stop Web Crawlers
       * Plugin URI: http://threenine.co.uk/product/stop-web-crawlers/
       * Description: Blocks traffic referrer spam bots
       * Version: 1.3.3
       * Author: Three Nine Consulting
       * Author URI: http://threenine.co.uk
       * License: GPLv2 or later
       *  Stable tag: 1.3.3
       * Copyright 2016 Three Nine Consulting (email : support@threenine.co.uk)
       * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as
       * published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
       * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
       * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
       *
       * See the GNU General Public License for more details.
       * You should have received a copy of the GNU General Public License
       * along with this program; if not, write to the
       * Free Software Foundation, Inc.,
       * 51 Franklin St, Fifth Floor,
       * Boston,
       * MA 02110-1301 USA
       * svn cp https://plugins.svn.wordpress.org/stop-web-crawlers/trunk https://plugins.svn.wordpress.org/stop-web-crawlers/tags/1.3.1
       */
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly



if (! class_exists ( 'Stop_Web_Crawlers' )) {
	final class Stop_Web_Crawlers {
		private static $instance;
		public static function instance() {
			if (! isset ( self::$instance ) && ! (self::$instance instanceof Stop_Web_Crawlers)) {
				
				self::$instance = new Stop_Web_Crawlers ();
				self::$instance->constants ();
				self::$instance->includes ();
				self::$instance->swc_execute ();
				
				add_action ( 'admin_menu', 'swc_create_menu' );
				add_action ( 'plugins_loaded', 'swc_plugin_db_update' );
				add_action ( 'parse_request', array (
						$this,
						'swc_execute' 
				) );
				
				
				add_action('admin_enqueue_scripts', 'swc_enqueue_resources_admin');
				register_activation_hook ( __FILE__, 'swc_plugin_activated' );
			}
			
			return self::$instance;
		}
		
		private function constants() {
			if (! defined ( 'SWC' ))
				define ( 'SWC', '1.3.3' );
			if (! defined ( 'SWCPATH' ))
				define ( 'SWCPATH', plugin_dir_path ( __FILE__ ) );
			if (! defined ( 'SWCURL' ))
				define ( 'SWCURL', plugin_dir_url ( __FILE__ ) );
			if (! defined ( 'SWCDOMAIN' ))
				define ( 'SWCDOMAIN', get_site_url () );
				if (! defined ( 'SWCAPPNAME' ))
					define ( 'SWCAPPNAME' , 'Stop Web Crawlers' );
			if (!defined('SWC_FILE'))    define('SWC_FILE',    plugin_basename(__FILE__));
		}
		
		private function includes() {
			
			require_once (SWCPATH . "mainmenu.php");
			require_once (SWCPATH . "functions/functions.php");
			require_once (ABSPATH . "wp-includes/pluggable.php");
			
			if (! class_exists ( 'WP_List_Table' )) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
			}
			
			require dirname ( __FILE__ ) . '/includes/list-tables/class-swc-list-table.php';
		}

	
		
		public static function swc_execute() {
			
			// Simply exit if logged or in admin area
			if (is_user_logged_in () || is_admin ()) {
				return;
			}
			
			global $wpdb;
			$referer = isset ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : false;
			
			if (empty ( $referer )) {
				return;
			}
			
			$referer = strtolower ( $referer );
			$table_name = $wpdb->prefix . "swc_blacklist";
			
			$sql = "SELECT boturl FROM " . $table_name . " WHERE boturl = '" . $referer . "' AND botstate = 'Enabled'";
			$bots = $wpdb->get_results ( $sql );
			
			foreach ( $bots as $row ) {
				if (strpos ( $referer, $row->boturl ) !== false) {
					wp_die ( '', '', array (
							'response' => 403 
					) );
					exit ();
				}
			}
		}
	}
}

if (class_exists ( 'Stop_Web_Crawlers' )) {

	
	if (! function_exists ( 'stop_web_crawlers' )) {
		function stop_web_crawlers() {
			return Stop_Web_Crawlers::instance ();
		}
	}
	
	stop_web_crawlers ();
}


?>
