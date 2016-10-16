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
<<<<<<< HEAD
		require_once 'class-swc-dbcreate.php';
		$dbcreate = new createSwcDatabase();
=======
		
		$dbcreate = new DBCreate();
>>>>>>> svnsync
		$dbcreate->create();	
		add_site_option('SWC_VERSION', SWC_VERSION);
		
		
		
	}
	
	
	
	
	
	

}

?>