<?php
    final class createSwcDatabase {
	
	private $SWC_CRAWLERS_LOG = 'swc_crawlers_log';
	private $SWC_CRAWLERS = 'swc_crawlers';
	private $SWC_CRAWLER_TYPE = 'swc_crawler_type';
	
	private $tablePrefix;
	private $collation;
	private $wpdb;
	
	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->tablePrefix = $wpdb->prefix;
		$this->collation = $wpdb->get_charset_collate ();
	}
	
	public function create(){
		
		$this->CreateCrawlerType();
		$this->CreateCrawlerTable();
		$this->CreateCrawlerLogTable();
		$this->InsertCrawlerTypes();
		$this->InsertCrawlers();
	}
	/*
	 * Create Crawler Type table.
	 *
	 * A lookup table to enable the seperation of crawlers into different types.
	 *
	 * @since    1.3.5
	 */
	private function CreateCrawlerType(){
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
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
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$crawlerTable = $this->tablePrefix . $this->SWC_CRAWLERS;
		$crawlerType =  $this->tablePrefix . $this->SWC_CRAWLER_TYPE;
	
	
		$sql = "CREATE TABLE IF NOT EXISTS $crawlerTable(
		`id` mediumint(9)  NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
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
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

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
		
		$crawlerType =  $this->tablePrefix . $this->SWC_CRAWLER_TYPE;
	
		
		
		$names = array('Referer', 'Scraper', 'Hacker', 'Impersonator');
		foreach ($names as $name) {
			
			$results9 = $this->wpdb->get_results ( "SELECT * FROM $crawlerType where name = '$name' limit 1" );
				
			
			if (count ( $results9 ) > 0 or empty ( $name ))
				continue;
			
			$sql = "INSERT INTO $crawlerType (`name`) VALUES ('$name');";
			$r = $this->wpdb->get_results ($sql );
		}
	
	}
	
	/**
	 * Insert Crawlers.
	 *
	 * Insert Initial list of crawlers - To be deprecated in 1.3.6.
	 *
	 * @since    1.3.5
	 */
	private function InsertCrawlers(){
		require_once (SWCPATH . "functions/crawlers.php");
		$dal = new data_access_layer();
		$z = count ( $wp_swc_blacklist );
		
		for($i = 0; $i < $z; $i ++) {
			$a = $wp_swc_blacklist [$i];
		
			$name = trim ( $a ['botnickname'] );
			$url = trim ( $a ['boturl'] );
			$crawler_table = $this->tablePrefix . $this->SWC_CRAWLERS;
			
			$results9 = $this->wpdb->get_results ( "SELECT * FROM $crawler_table where name = '$name' limit 1" );
			
			if (count ( $results9 ) > 0 or empty ( $name ))
				continue;
			
			$result = $dal->insert_crawler($name, $url);
			
		}
		
		
	}
	
}
	
