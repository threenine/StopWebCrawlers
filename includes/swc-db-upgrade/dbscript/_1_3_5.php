<?php

final class _1_3_5 extends updater{

	private $SWC_CRAWLERS_LOG = 'swc_crawlers_log';
	private $SWC_CRAWLERS = 'swc_crawlers';
	private $SWC_CRAWLER_TYPE = 'swc_crawler_type';
	private $SWC_BLACKLIST = 'swc_blacklist';
	
	private $tablePrefix;
	private $collation;

	public function __construct(){
		global $wpdb;
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		$this->tablePrefix = $wpdb->prefix;
		$this->collation = $wpdb->get_charset_collate ();
	}
	
	
	public function update(){
		
		$this->CreateCrawlerType();
		$this->CreateCrawlerTable();
		$this->CreateCrawlerLogTable();
		$this->InsertCrawlerTypes();
		$this->MigrateCrawlerData();

	}
	
	/*
	 * Create Crawler Type table.
	 *
	 * A lookup table to enable the seperation of crawlers into different types.
	 *
	 * @since    1.3.5
	 */
	private function CreateCrawlerType(){
		global $wpdb;
		$crawlerType = $this->tablePrefix  . $this->SWC_CRAWLER_TYPE;
		
		
		$sql = "CREATE TABLE IF NOT EXISTS $crawlerType(
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
  		`name` varchar(45) DEFAULT NULL,
  		 PRIMARY KEY (`id`),
  		UNIQUE KEY `id_UNIQUE` (`id`)
		) $this->collation;";
		
		
		dbDelta ( $sql );
		
	}
	
	/*
	 * Create Crawler table.
	 *
	 * Replacing initial SWC_Blacklist table .
	 *
	 * @since    1.3.5
	 */
	private function CreateCrawlerTable(){
		global $wpdb;
		$crawlerTable = $this->tablePrefix . $this->SWC_CRAWLERS;
		$crawlerType =  $this->tablePrefix . $this->SWC_CRAWLER_TYPE;
		
		
		$sql = "CREATE TABLE IF NOT EXISTS $crawlerTable(
		`id` mediumint(9)  NOT NULL AUTO_INCREMENT,
  		`Name` varchar(255) NOT NULL,
  		`url` varchar(255) NOT NULL,
  		`typeid` mediumint(9) DEFAULT NULL,
  		`status`  varchar(10) NOT NULL,
  		PRIMARY KEY (`id`),
  		KEY `id_idx` (`typeid`),
  		CONSTRAINT `id` FOREIGN KEY (`typeid`) REFERENCES `$crawlerType` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) $this->collation;";
		
		
		dbDelta ( $sql );
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
		
		$crawlerTable = $this->tablePrefix . $this->SWC_CRAWLERS;
		$logTable= $this->tablePrefix . $this->SWC_CRAWLERS_LOG;
		
      	$sql = "CREATE TABLE IF NOT EXISTS $logTable(
			  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
			  `crawlerid` mediumint(9) DEFAULT NULL,
			  `attempts` mediumint(9) DEFAULT NULL,
			  `lastAttempt` varchar(45) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `crawlerid_idx` (`crawlerid`),
			  CONSTRAINT `crawlerid` FOREIGN KEY (`crawlerid`) REFERENCES `$crawlerTable` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			)$this->collation;";
		
		
	    dbDelta ( $sql );
	}
	
	/**
	 * Insert Crawlers types.
	 *
	 * Standing data to populate the look up fields.
	 *
	 * @since    1.3.5
	 */
	private function InsertCrawlerTypes(){
		global $wpdb;
		$crawlerType =  $this->tablePrefix . $this->SWC_CRAWLER_TYPE;
		
		$names = array('Referer', 'Scraper', 'Hacker', 'Impersonator');
		foreach ($names as $name) {
			$sql = "INSERT INTO $crawlerType (`name`) VALUES ('$name');";
			$r = $wpdb->get_results ($sql );
		}
		
	}
	/**
	 * Migrate Legacy Crawlers.
	 *
	 *  Get all values from legacy blacklist and insert into new list.
	 *
	 * @since    1.3.5
	 */
	private function MigrateCrawlerData(){
		global $wpdb;
		$crawlerType =  $this->tablePrefix . $this->SWC_CRAWLER_TYPE;
		$crawlerTable = $this->tablePrefix . $this->SWC_CRAWLERS;
		$blacklist = $this->tablePrefix .$this->SWC_BLACKLIST;
		
		$refSel = "SELECT id FROM $crawlerType WHERE name='Referer'";
		$referer = $results9 = $wpdb->get_results ($refSel );
		
		$sql = "INSERT INTO $crawlerTable (Name, Url, typeid, status)
				SELECT botname, boturl, $referer , botstatus
				FROM  $blacklist";
		
		dbDelta ( $sql );
		
	}

} 
?>
