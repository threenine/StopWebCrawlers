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
		
		$tableName = $wpdb->prefix . $this->SWC_CRAWLER_LOG;
        $charset_collate = $wpdb->get_charset_collate ();
	
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`botid` int NOT NULL,
		`attempt` int NOT NULL,
		UNIQUE (`id`)
		) $charset_collate;";
		
		try {
	     	dbDelta ( $sql );
		}
		catch(Exception $e){
			$error = $e->getMessage();
		}
	     

	}

} 
?>
