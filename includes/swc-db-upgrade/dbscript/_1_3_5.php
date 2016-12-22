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
		require_once plugin_dir_path( __FILE__ ) .  "includes/swc-core/class-swc-dbcreate.php";
		$dbcreate = new createSwcDatabase();
		$dbcreate->create();		
		

	}
	
} 
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
		require_once plugin_dir_path( __FILE__ ) .  "includes/swc-core/class-swc-dbcreate.php";
		$dbcreate = new createSwcDatabase();
		$dbcreate->create();		
		

	}
	
} 
?>