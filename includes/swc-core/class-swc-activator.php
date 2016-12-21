<?php
/**
 * Fired during plugin activation
 *
 * @link       https://threenine.co.uk
 * @since      1.3.5
 *
 * @package    StopWebCrawlers
 * @subpackage StopWebCrawlers/includes
 */


class StopWebCrawlers_Activator {

	
	/**
	 * Do plugin install tasks.
	 *
	 * Long Description.
	 *
	 * @since    1.3.5
	 */
	public static function activate() {

		// Create the database schema for the  crawlers and loggers tables

		require_once 'class-swc-dbcreate.php';
		$dbcreate = new createSwcDatabase();

		$dbcreate->create();	
		add_site_option('SWC_VERSION', SWC_VERSION);
		
		
		
	}
	
	private static function SetOptions(){
		//Assign initial options
		$activate_options = array
		(
				'subdomains'=> 'on',
				'redirecturl'=> 'http://semalt.com',
				'domains'=> '',
				'update_url'=> 'https://api.github.com/repos/threenine/stopwebcrawlers/contents/list/referer.csv',
				'last_update_time'=> '',
				'blocked_count'=> 0,
				'send_stats' => 'on'
		);
		
		add_option( 'bot_block', $activate_options );
	}
	
	
	
	

}

?>