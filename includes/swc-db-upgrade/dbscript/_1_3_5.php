<?php

final class _1_3_5 extends updater{

	private $SWC_CRAWLERS_LOG = 'swc_crawlers_log';
	private $SWC_CRAWLERS = 'swc_crawlers';
	private $SWC_CRAWLER_TYPE = 'swc_crawler_type';
	private $SWC_BLACKLIST = 'swc_blacklist';
	
	private $tablePrefix;
	private $collation;
	private $wpdb;

	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		$this->tablePrefix = $wpdb->prefix;
		$this->collation = $wpdb->get_charset_collate ();
	}
	
	
	public function update(){
		require (SWCPATH . "/includes/swc-core/class-swc-dbcreate.php");
		$dbcreate = new DBCreate();
		$dbcreate->create();		
		$this->MigrateCrawlerData();

	}
	
	/**
	 * Migrate Legacy Crawlers.
	 *
	 *  Get all values from legacy blacklist and insert into new list.
	 *
	 * @since    1.3.5
	 */
	private function MigrateCrawlerData(){

		$crawlerType =  $this->tablePrefix . $this->SWC_CRAWLER_TYPE;
		$crawlerTable = $this->tablePrefix . $this->SWC_CRAWLERS;
		$blacklist = $this->tablePrefix . $this->SWC_BLACKLIST;
		
		//Check if the blacklist table exists then migrate data
		if($this->wpdb->get_var("SHOW TABLES LIKE '$blacklist'") == $blacklist) {
		$refSel = "SELECT id FROM $crawlerType WHERE name='Referer';";
		$referer =   $this->wpdb->get_var($refSel );
		
		$sql = "INSERT INTO $crawlerTable (name, Url, typeid, status)
				SELECT botname, boturl, $referer , botstate
				FROM  $blacklist;";
		
		dbDelta ( $sql );
		}
		
	}
	
	/**
	 * Drop the old table.
	 *
	 *  We have migrated to a new improved schema, we now delete old table.
	 *
	 * @since    1.3.5
	 */
	private function DropLegacyTable(){
		
	}

} 
?>
