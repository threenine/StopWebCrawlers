<?php

final class _1_3_5 extends updater{

	private $SWC_CRAWLER_LOG = 'swc_crawler_log';
	public function update(){
		
		
		$this->CreateCrawlerLogTable();



	}

	private function CreateCrawlerLogTable(){

		//echo 'Call me Al';
		

		global $wpdb;
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		$baseTable = $this->SWC_CRAWLER_LOG;
		$tableName = $wpdb->prefix.$baseTable;
        $charset_collate = $wpdb->get_charset_collate ();
	
		$sql = "CREATE TABLE IF NOT EXISTS $tableName  (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`boturl` text NOT NULL,
		`attempt` int NOT NULL,

		UNIQUE (`id`),
		UNIQUE (`boturl`)

		) $charset_collate;";
		
		try{
	     dbDelta ( $sql );
		}catch(Exception $e){
			$error = $e->getMessage();
		}
	     

	}

} 
?>
