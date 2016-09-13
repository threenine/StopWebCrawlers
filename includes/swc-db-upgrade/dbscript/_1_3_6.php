<?php

final class _1_3_6 extends updater{

	public function update(){
		
		
		$this->CreateCrawlerLogTable();



	}

	private function CreateCrawlerLogTable(){

		echo 'Call me Al';
		
/*
		global $wpdb;
		$tableName = $wpdb->prefix.parent::$this->SWC_CRAWLER_LOG;
        $charset_collate = $wpdb->get_charset_collate ();
	
		$sql = "CREATE TABLE IF NOT EXISTS $table (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`boturl` text NOT NULL,
		`attempt` int NOT NULL,

		UNIQUE (`id`),
		UNIQUE (`boturl`)

		) $charset_collate;";
	     dbDelta ( $sql );
	     */

	}

} 
?>
