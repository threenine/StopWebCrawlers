<?php 
/*
       * Plugin Name: Stop Web Crawlers
       * Plugin URI: https://threenine.co.uk/plugins/stop-web-crawlers/
       * Description: Blocks over 1400 known referer spammers from directly targeting your website.
       * Version: 1.3.6
       * Author: Three Nine Consulting
       * Author URI: http://threenine.co.uk
       * License: GPLv2 or later
       *  Stable tag: 1.3.4
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
       * 
       */
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly
	
	//Register the activation hooks
	function activate_StopWebCrawlers() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/swc-core/class-swc-activator.php';
		StopWebCrawlers_Activator::activate();
	}
	register_activation_hook( __FILE__, 'activate_StopWebCrawlers' );

if (! class_exists ( 'Stop_Web_Crawlers' )) {
	final class Stop_Web_Crawlers {
		
		private static $instance;
		public static function instance() {
			if (! isset ( self::$instance ) && ! (self::$instance instanceof Stop_Web_Crawlers)) {
				
				self::$instance = new Stop_Web_Crawlers ();
				self::$instance->constants ();
				self::$instance->includes ();
				//self::$instance->checkVersion();
				
				
				add_action ( 'admin_menu', 'swc_create_menu' );
				add_action ( 'parse_request', array ( 'Request_Parser', 'execute' ));
				add_filter ( 'plugin_action_links', array(self::$instance, 'action_links'), 10, 2);
				add_action( 'admin_init', 'checkversion' );
				
				add_action('admin_enqueue_scripts', 'swc_enqueue_resources_admin');
				
				
			}
			
			return self::$instance;
		}
		
		private function constants() {
			if (! defined ( 'SWC_VERSION' ))
				define ( 'SWC_VERSION', '1.3.5' );
			if (! defined ( 'SWCPATH' ))
				define ( 'SWCPATH', plugin_dir_path ( __FILE__ ) );
			if (! defined ( 'SWCURL' ))
				define ( 'SWCURL', plugin_dir_url ( __FILE__ ) );
			if (! defined ( 'SWCDOMAIN' ))
				define ( 'SWCDOMAIN', get_site_url () );
				if (! defined ( 'SWCAPPNAME' ))
					define ( 'SWCAPPNAME' , 'Stop Web Crawlers' );
			if (!defined('SWC_FILE'))    define('SWC_FILE',    plugin_basename(__FILE__));
			if(!defined('SWC_UPDATE_OPTIONS')) define('SWC_UPDATE_OPTIONS','' );
			if(!defined('SWC_LIST_UPDATE_URL')) define('SWC_LIST_UPDATE_URL', 'https://api.github.com/repos/threenine/stopwebcrawlers/contents/list/referer.csv');
		}
		
		private function includes() {
			
			require_once (SWCPATH . "mainmenu.php");
			require_once (SWCPATH . "functions/functions.php");
			require_once (ABSPATH . "wp-includes/pluggable.php");
			
			
			if (! class_exists ( 'WP_List_Table' )) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
			}
			
			require dirname ( __FILE__ ) . '/includes/list-tables/class-swc-list-table.php';
			require dirname ( __FILE__ ) . '/includes/swc-core/class-swc-request-parser.php';
			require dirname ( __FILE__ ) . '/includes/swc-db-upgrade/DatabaseUpdate.php';
			require dirname ( __FILE__ ) . '/includes/swc-core/class-swc-validator.php';
			require dirname ( __FILE__ ) . '/includes/swc-core/class-swc-data-access-layer.php';
			require dirname ( __FILE__ ) . '/includes/swc-core/class-swc-servervariables.php';
			require dirname ( __FILE__ ) . '/includes/swc-core/class-swc-download.php';
			require dirname ( __FILE__ ) . '/includes/swc-core/class-swc-dbcreate.php';
		}
		private function checkversion()
		{
			//Get Current version
			$installed_version = get_site_option('SWC_VERSION');
			if(!$installed_version){
				add_site_option('SWC_VERSION', SWC_VERSION);	
			}

			if(version_compare($installed_version, SWC_VERSION, '<')){
					$du = new DatabaseUpdate($installed_version, SWC_VERSION);
  					$du->upgrade();	
  				    update_site_option('SWC_VERSION', SWC_VERSION);
			}
			
			

		}

		public function action_links($links, $file) {
			if ($file == SWC_FILE) {
				$swc_links = '<a href="'. admin_url('admin.php?page=swc_main_menu') .'">'. esc_html__('Settings', 'stop-web-crawlers') .'</a>';
				array_unshift($links, $swc_links);
			}
			return $links;
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
