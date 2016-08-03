<?php

class swc_List_Table extends WP_List_Table {

	public function __construct() {
		// Set parent defaults.
		parent::__construct( array(
				'singular' => 'bot',     // Singular name of the listed records.
				'plural'   => 'bots',    // Plural name of the listed records.
				'ajax'     => false,       // Does this table support ajax?
		) );
	}


	public function get_columns() {
		$columns = array(
				'cb'       => '<input type="checkbox" />', // Render a checkbox instead of text.
				'botnickname'    => _x('Nickname', 'Column label', 'badbots' ),
				'botname'   => _x( 'Name', 'Column label', 'badbots' ),
				'botstate' => _x( 'Status', 'Column label', 'badbots' ),
				

		);

		return $columns;
	}

	protected function get_sortable_columns() {
		$sortable_columns = array(
				'botnickname'    => array( 'botnickname', true ),
				'botname'   => array( 'botname', true ),
				'botstate' => array( 'botstate', true ),
				'boturl' => array( 'boturl', true ),
		);

		return $sortable_columns;
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'botname':
			case 'botnickname':
			case 'boturl':
			case 'botstate':
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

	protected function column_nickname( $item ) {
		$page = wp_unslash( $_REQUEST['page'] ); // WPCS: Input var ok.

		// Build activate row action.
		$edit_query_args = array(
				'page'   => $page,
				'action' => 'activate',
				'bot'  => $item['id'],
		);

		$actions['activate'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( admin_url( add_query_arg( $edit_query_args ) ), 'editmovie_' . $item['ID'] ) ),
				_x( 'Change Status', 'List table row action', 'stopwebcrawlers' )
				);


		// Build desactivate row action.
		$delete_query_args = array(
				'page'   => $page,
				'action' => 'Disable',
				'bot'  => $item['id'],
		);

		$actions['desactivate'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( admin_url( add_query_arg( $delete_query_args ) ), 'deletemovie_' . $item['ID'] ) ),
				_x( 'Disable', 'List table row action', 'stopwebcrawlers' )
				);



		// Return the title contents.
		return sprintf( '%1$s <span style="color:silver;">(id:%2$s)</span>%3$s',
				$item['nickname'],
				$item['id'],
				$this->row_actions( $actions )
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
		if ( 'Enable' === $this->current_action() ) {

			if(isset($_GET['bot']))
			{
				$ctd = 0;
				 
				foreach($_GET['bot'] as $botid) {

					$ctd++;
					$wpdb->show_errors();

					$result =   $wpdb->update (
							$wpdb->prefix .'swc_blacklist',
							array(
									'botstate' => 'Enabled'
							),
							array(
									"id" => $botid
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

			if(isset($_GET['bot']))
			{
				$ctd = 0;
				 
				foreach($_GET['bot'] as $botid) {

					$ctd++;
					$wpdb->show_errors();

					$result =   $wpdb->update (
							$wpdb->prefix .'swc_blacklist',
							array(
									'botstate' => 'Disabled'
							),
							array(
									"id" => $botid
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


		$current_table = $wpdb->prefix .'swc_blacklist';

		if(isset($_GET['order']))
			$order = $_GET['order'];
			else
				$order = 'asc';


				if(isset($_GET['orderby']))
					$orderby = $_GET['orderby'];
					else
						$orderby = 'botnickname';

						if( isset($_GET['s']) ){

							$my_search = sanitize_text_field($_GET['s']);

							$results = $wpdb->get_results( "SELECT * FROM $current_table  WHERE
									`botnickname` LIKE  '%". $my_search . "%'
             order by ". $orderby ." " .$order);

						}
						else {

							$results = $wpdb->get_results( "SELECT * FROM $current_table order by ". $orderby ." " .$order);

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
		$orderby = ! empty( $_REQUEST['orderby'] ) ? wp_unslash( $_REQUEST['orderby'] ) : 'botnickname'; // WPCS: Input var ok.

		// If no order, default to asc.
		$order = ! empty( $_REQUEST['order'] ) ? wp_unslash( $_REQUEST['order'] ) : 'asc'; // WPCS: Input var ok.

		// Determine sort order.
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		return ( 'asc' === $order ) ? $result : - $result;
	}

}
