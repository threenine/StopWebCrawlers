<?php
/*
 Plugin Name: Stop Web Crawlers
 Plugin URI: http://threenine.co.uk/product/stop-web-crawlers/
 Description: Blocks traffic referrer spam bots
 Version: 1.0.0
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
		$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : false;

		if ( empty( $referer ) ) {
			return;
		}

		$referer = strtolower($referer);

		$bot_array = $bot_array = $this->build_bot_array();

		foreach($bot_array as $bots) {
			if ( strpos( $referer, $bots ) !== false ) {
				wp_die( '', '', array( 'response' => 403 ) );
				exit;
			}
		}
	}
	
	private function build_bot_array(){
	
		$bot_array = array('semalt.com', 'buttons-for-website.com', 'darodar.com', 'social-buttons.com', '7makemoneyonline.com', 'ilovevitaly.co', 'simple-share-buttons.com',
				'clicksor.com', 'bestwebsitesawards.com', 'aliexpress.com', 'savetubevideo.com', 'kambasoft.com', 'priceg.com', 'blackhatworth.com', 'hulfingtonpost.com',
				'econom.co', 'ranksonic.org', 'ranksonic.info', '4webmasters.org', 'anticrawler.org', 'bestsub.com', 'o-o-6-o-o.com', 'sitequest.ru', 'search.tb.ask.com',
				'wow.com', 'adviceforum.info', 'makemoneyonline.com', 'best-seo-solution.com', 'get-free-traffic-now.com', 'buy-cheap-online.info', 'best-seo-offer.com',
				'buttons-for-your-website.com', 'googlsucks.com', 'pornhub-forum.ga', 'depositfiles-porn.ga', 'theguardlan.com', 'torture.ml', 'youporn-forum.ga', 'hol.es',
				'domination.ml', 'free-share-buttons.com', 'uni.me', 'sashagreyblog.ga', 'search.myway.com', 'guardlink.com', 'event-tracking.com', 'free-social-buttons.com',
				'kabbalah-red-bracelets.com', 'guardlink.org', 'sanjosestartups.com', '100dollars-seo.com', 'howtostopreferralspam.eu', 'ertelecom.ru', 'corbina.ru',
				'floating-share-buttons.com', 'mts-nn.ru', 'kes.ru', 'bashtel.ru', 'is74.ru', 'netbynet.ru', 'avtlg.ru', 'mts.ru', 'nationalcablenetworks.ru',
				'videos-for-your-business.com', 'success-seo.com', 'webmonetizer.net', 'trafficmonetizer.net', 'e-buyeasy.com', 'traffic2money.com', 'sexyali.com',
				'get-free-social-traffic.com', 'chinese-amezon.com', 'erot.co', 'hongfanji.com', 'video--production.com', 'rankscanner.com', 'yourserverisdown.com',
				'free-floating-buttons.com', 'how-to-earn-quick-money.com', 'qualitymarketzone.com', 'best-seo-software.xyz', 'seo-platform.com', 'rankings-analytics.com',
				'copyrightclaims.org', 'snip.to', 'amazonaws.com', 'top1-seo-service.com', 'rusexy.xyz', 'share-buttons.xyz', 'traffic2cash.xyz', 'site-16528012-1.snip.tw',
				'website-analyzer.info', 'rank-checker.online', 'keywords-monitoring-your-success.com', 'works.if.ua', 'free-video-tool.com', 'social-traffic-', 'uptime.com',
				'social-buttons-ii.xyz', 'monetizationking.net');
		return $bot_array;
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