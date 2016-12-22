<?php

class swc_List_Table extends WP_List_Table {
	private $SWC_CRAWLERS_LOG = 'swc_crawlers_log';
	private $SWC_CRAWLERS = 'swc_crawlers';
	private $SWC_CRAWLER_TYPE = 'swc_crawler_type';
	
	public function __construct() {
		// Set parent defaults.
		parent::__construct( array(
				'singular' => 'crawler',     // Singular name of the listed records.
				'plural'   => 'crawlers',    // Plural name of the listed records.
				'ajax'     => false,       // Does this table support ajax?
		) );
	}


	public function get_columns() {
		$columns = array(
				'cb'       => '<input type="checkbox" />', // Render a checkbox instead of text.
				'name'   => _x( 'Name', 'Column label', 'swc' ),
				'url'   => _x(  'Url', 'Column label', 'swc' ),
				'status' => _x(  'Status', 'Column label', 'swc' ),
				'type' => _x(  'Type', 'Column label', 'swc' )
		);

		return $columns;
	}

	protected function get_sortable_columns() {
		$sortable_columns = array(
				'name'    => array( 'name', true ),
				'type'   => array( 'type', true ),
				'status' => array( 'status', true ),
				'url' => array( 'url', true ),
		);

		return $sortable_columns;
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
			case 'type':
			case 'url':
			case 'status':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
		}
	}


	protected function column_cb( $item ) {
		return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" />',
				$this->_args['singular'],  // Let's simply repurpose the table's singular label ("movie").
				$item['id']                // The value of the checkbox should be the record's ID.
				);
	}

	


	protected function get_bulk_actions() {
		$actions = array(
				'Enable' => _x( 'Enable', 'List table bulk action', 'stopwebcrawlers' ),
				'Disable' => _x( 'Disable', 'List table bulk action', 'stopwebcrawlers' ),

		);

		return $actions;
	}


	protected function process_bulk_action() {
		// Detect when a bulk action is being triggered.
		global $wpdb;
		$crawlers_table = $wpdb->prefix .$this->SWC_CRAWLERS;
		if ( 'Enable' === $this->current_action() ) {

			if(isset($_GET['crawler']))
			{
				$ctd = 0;
				 
				foreach($_GET['crawler'] as $crawlerid) {

					$ctd++;
					$wpdb->show_errors();

					$result =   $wpdb->update (
							$crawlers_table,
							array(
									'status' => 'Enabled'
							),
							array(
									"id" => $crawlerid
							)
							);
					 
					 
					if(gettype ($result) != 'integer')
						if(gettype ($result) != 'boolean')
							if(!result)
								$wpdb->print_error();
								 
								$wpdb->flush();
				}
				if($ctd > 0)
					echo '<h4>'. $ctd . ' updated!</h4>';

			}

		}


		if ( 'Disable' === $this->current_action() ) {

			if(isset($_GET['crawler']))
			{
				$ctd = 0;
				 
				foreach($_GET['crawler'] as $crawlerid) {

					$ctd++;
					$wpdb->show_errors();

					$result =   $wpdb->update (
							$crawlers_table,
							array(
									'status' => 'Disabled'
							),
							array(
									"id" => $crawlerid
							)
							);
					 
					if(gettype ($result) != 'integer')
						if(gettype ($result) != 'boolean')
							if(!result)
								$wpdb->print_error();
								 
								$wpdb->flush();
				}
				if($ctd > 0)
					echo '<h4>'. $ctd . ' updated!</h4>';
			}

		}
	}


	function prepare_items() {
		global $wpdb;
		global $option;

		$per_page = 15;


		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();


		$crawlers_table = $wpdb->prefix .$this->SWC_CRAWLERS;
		$crawlers_type_table = $wpdb->prefix .$this->SWC_CRAWLER_TYPE;

		if(isset($_GET['order']))
			$order = $_GET['order'];
			else
				$order = 'asc';


				if(isset($_GET['orderby']))
					$orderby = $_GET['orderby'];
					else
						$orderby = $crawlers_table . '.name';

						if( isset($_GET['s']) ){

							$my_search = sanitize_text_field($_GET['s']);

							$searchSql = "SELECT $crawlers_table.id as id, $crawlers_table.name as name, $crawlers_table.url as url, $crawlers_type_table.name as type, $crawlers_table.status as status   
							FROM $crawlers_table
							inner join $crawlers_type_table on $crawlers_type_table.id = $crawlers_table.typeid WHERE
									$crawlers_table.name LIKE  '%". $my_search . "%'
             order by ". $orderby ." " .$order;
							
							
							$results = $wpdb->get_results($searchSql );

						}
						else {
							
							$sql = "SELECT $crawlers_table.id as id, $crawlers_table.name as name, $crawlers_table.url as url, $crawlers_type_table.name as type, $crawlers_table.status as status   
							FROM $crawlers_table
							inner join $crawlers_type_table on $crawlers_type_table.id = $crawlers_table.typeid
							order by ". $orderby ." " .$order;
							

							$results = $wpdb->get_results( $sql);

						}

						$data=array();
						$i = 0;

						foreach ($results as $querydatum ) {
							array_push($data, (array)$querydatum);
						}
						$current_page = $this->get_pagenum();

						$total_items = count( $data );

						$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

						$this->items = $data;

						$this->set_pagination_args( array(
								'total_items' => $total_items,                     // WE have to calculate the total number of items.
								'per_page'    => $per_page,                        // WE have to determine how many items to show on a page.
								'total_pages' => ceil( $total_items / $per_page ), // WE have to calculate the total number of pages.
						) );
	}


	protected function usort_reorder( $a, $b ) {
		// If no sort, default to title.
		$orderby = ! empty( $_REQUEST['orderby'] ) ? wp_unslash( $_REQUEST['orderby'] ) : 'name'; // WPCS: Input var ok.

		// If no order, default to asc.
		$order = ! empty( $_REQUEST['order'] ) ? wp_unslash( $_REQUEST['order'] ) : 'asc'; // WPCS: Input var ok.

		// Determine sort order.
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		return ( 'asc' === $order ) ? $result : - $result;
	}

}
