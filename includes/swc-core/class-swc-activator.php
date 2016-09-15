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

		require_once (SWCPATH . "/includes/swc-core/class-swc-dbcreate.php");
		$dbcreate = new DBCreate();
		$dbcreate->create();		
	}
	
	
	
	
	
	

}

?>