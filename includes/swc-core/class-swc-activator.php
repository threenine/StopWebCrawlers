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
		
		$dbcreate = new DBCreate();
		$dbcreate->create();	
		
		
		
		
	}
	
	
	
	
	
	

}

?>