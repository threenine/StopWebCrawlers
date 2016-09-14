<?php

final class _1_3_5 extends updater{

	private $SWC_CRAWLER_LOG = 'swc_crawler_log';
	

	public function __construct(){
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	}
	
	public function update(){
		
		
		$this->CreateCrawlerLogTable();



	}
	/**
	 * Create Crawler log table.
	 *
	 * A log table to track attempts made by crawlers to the website to display in dashboard.
	 *
	 * @since    1.3.5
	 */
	private function CreateCrawlerLogTable(){

		global $wpdb;
		
		$tableName = $wpdb->prefix . $this->SWC_CRAWLER_LOG;
        $charset_collate = $wpdb->get_charset_collate ();
	
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`botid` int NOT NULL,
		`attempt` int NOT NULL,
		UNIQUE (`id`)
		) $charset_collate;";
		
		
	    dbDelta ( $sql );
	}

} 
?>
