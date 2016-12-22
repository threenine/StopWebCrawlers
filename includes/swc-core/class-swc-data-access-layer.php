<?php
/*
 * All access to updating the data tables within the applicaiton are handled in this class
 * the intention is to de-clutter the code base from having sql statements and references to
 * global $wpdb  
 * 
 */

 class data_access_layer{
 	
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
 	$this->tablePrefix = $wpdb->prefix;
 	$this->collation = $wpdb->get_charset_collate ();
 		
 		
 	}
 	
 	/**
 	 * Get identified referer spammer URL.
 	 *
 	 *  Get a list of Referer spammer urls.
 	 *
 	 * @since    1.3.5
 	 */
 	public function GetCrawlers($referer){
 		
 		$crawler_table = $this->tablePrefix . $this->SWC_CRAWLERS;
 		$sql = "SELECT url FROM " . $crawler_table . " WHERE url = '" . $referer . "' AND status = 'Enabled'";
 		$bots = $this->wpdb->get_results ( $sql);
 		return $bots;
 		
 		}
 		

 		/**
 		 * Insert a new web crawler.
 		 *
 		 * At present this only defaults to adding Referer spammers in future versions this will accomodate other crawlers.
 		 *
 		 * @since    1.3.5
 		 */
 		public function insert_crawler($name,$url){
 			$crawler_table = $this->tablePrefix . $this->SWC_CRAWLERS;
 			
 			$this->wpdb->insert($crawler_table, array (
 					'name' =>$name,
 					'url' => $url,			
 					'typeid' => '1',
 					'status' => 'Enabled'
 			) );
 			
 		
 		}
 		
	
}