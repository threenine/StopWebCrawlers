<?php

final class _1_3_5 extends updater{

	private $SWC_CRAWLERS_LOG = 'swc_crawlers_log';
	private $SWC_CRAWLERS = 'swc_crawlers';

	public function __construct(){
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	}
	
	
	public function update(){
		
		$this->CreateCrawlerTable();
		$this->CreateCrawlerLogTable();

	}
	
	/*
	 * Create Crawler table.
	 *
	 * Replacing initial SWC_Blacklist table .
	 *
	 * @since    1.3.5
	 */
	private function CreateCrawlerTable(){
		
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
		
		$crawlerTable = $wpdb->prefix . $this->SWC_CRAWLERS;
		$logTable= $wpdb->prefix . $this->SWC_CRAWLERS_LOG;
		
        $charset_collate = $wpdb->get_charset_collate ();
	
		$sql = "CREATE TABLE IF NOT EXISTS $logTable(
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `crawlerid` mediumint(9) DEFAULT NULL,
		  `attempts` mediumint(9) GENERATED ALWAYS AS (0) VIRTUAL,
		  `lastAttempt` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `id_idx` (`botid`),
		  CONSTRAINT `id` FOREIGN KEY (`crawlerid`) REFERENCES `$crawlerTable' (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) $charset_collate;";
		
		
	    dbDelta ( $sql );
	}

} 
?>
