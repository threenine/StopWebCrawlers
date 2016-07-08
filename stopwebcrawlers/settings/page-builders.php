<?php namespace stopwebcrawlersWPSettings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Base class for optons page builders.
 */
class OptionPageBuilder {

	protected $page;
	protected $tabs;
	protected $scripts;
	protected $styles;

	public function __construct (  $page, $scripts = array(), $styles = array() ) 
	{
		// Initialize page and register page action
		$this->page = $page;
		add_action('admin_menu', array($this, 'register_page'));

		// Add user supplied scripts for this page
		$this->scripts = $scripts;

		// Add user supplied stylesheets
		$this->styles = $styles;

		global $pcs_settings_config;

		// Load PCS Settings stylesheet.
		$this->styles[] = array('handle' => 'pcs-admin-settings', 'src'=> $pcs_settings_config['base_uri'] . 'styles/admin-settings.css', 'enqueue' => TRUE);

		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		      

	}

	public function register_page()
	{


        
        	   
		switch($this->page->type) {
			case 'menu':
                // $this->page->menu_title = 'Settings';
				// TODO: Add icon url and postion configuration values
				add_menu_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				$this->page->set_hook('toplevel_page_');
				break;
			case 'submenu':
				add_submenu_page( $this->page->parent_slug, $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				break;
			case 'settings':
				add_options_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				$this->page->set_hook('settings_page_');
				break;
			default:
				add_theme_page( $this->page->title, $this->page->menu_title, $this->page->capability, $this->page->slug, array($this, 'render') );
				$this->page->set_hook('appearance_page_');
				break;
		}
	}

	public function admin_enqueue_scripts($page_hook)
	{

		// Only load our scripts on our page
		if($this->page->hook == $page_hook) {
			// Process the Scripts
			foreach($this->scripts as $script) {

				$deps = (isset($script['deps'])) ? $script['deps'] : array();

				if(isset($script['enqueue']) && $script['enqueue'])  {

						if(isset($script['src']) && !wp_script_is( $script['handle'], 'registered' )) {
							wp_register_script( $script['handle'], $script['src'], $deps);
						}
						if(!wp_script_is( $script['handle'], 'enqueued')) {
							wp_enqueue_script($script['handle']);
						}	
				} else {
						
						if(isset($script['src']) && !wp_script_is( $script['handle'], 'registered' )) {
							wp_register_script( $script['handle'], $script['src'], $script['deps']);
						}
				}
			}

			// Process the Styles
			foreach($this->styles as $style) {

				$deps = (isset($style['deps'])) ? $style['deps'] : array();

				if(isset($style['enqueue']) && $style['enqueue'])  {

						if(isset($style['src']) && !wp_style_is( $style['handle'], 'registered' )) {
							wp_register_style( $style['handle'], $style['src'], $deps);
						}
						if(!wp_style_is( $style['handle'], 'enqueued')) {
							wp_enqueue_style($style['handle']);
						}	
				} else {
						
						if(isset($style['src']) && !wp_style_is( $style['handle'], 'registered' )) {
							wp_register_style( $style['handle'], $style['src'], $style['deps']);
						}
				}
			}
		}
	}

	public function render()
	{

		do_action('pcs_render_option_page');

		echo $this->page->markup_top;

		echo '<form method="post" action="options.php">';

		// TODO: only output errors on custom pages
		// settings_errors();

		settings_fields( $this->page->slug );
		do_settings_sections( $this->page->slug );

		submit_button();

		echo '</form>';

		$this->render_reset_form();

		echo $this->page->markup_bottom;

	}

	public function render_reset_form( $active_tab = NULL )
	{

		// echo reset form
		echo '<form method="post" action="' . str_replace( '&settings-updated=true', '', $_SERVER["REQUEST_URI"] ) . '" class="reset-form">';

		// Reset nonce
		wp_nonce_field( 'pcs_reset_options', 'pcs_reset_options_nonce' );

		echo '<input type="hidden" name="action" value="reset" />';

		if(!is_null($active_tab)) {
			echo '<button type="submit" class="button secondary reset-settings" title="Reset ' . $active_tab->title . '">Reset ' . $active_tab->title . '</button>';
		} else {
			echo '<button type="submit" class="button secondary reset-settings" title="Reset Options">Reset Options</button>';
		}

		echo '</form>';

	}

}

/**
 * Single options page builder
 */
class OptionPageBuilderSingle extends OptionPageBuilder {

	public function __construct ( $page, $section_settings = array(), $scripts = array(), $styles = array() ) 
	{
		parent::__construct( $page, $scripts, $styles );
		new SectionFactory( $page, $section_settings );
	}
}


/**
 * Tabbed options page builder.
 */
class OptionPageBuilderTabbed extends OptionPageBuilder {

	protected $tabs;

	public function __construct ( $page, $options_settings = array(), $scripts = array(), $styles = array() ) 
	{
		parent::__construct( $page, $scripts, $styles );

		$this->tabs = array();

		$counter = 0;

		// Runs when posting to option.php
		// Only create the active tab so the other page sections
		// Do not get overwritten
		$action = (isset($_POST['action'])) ? $_POST['action'] : FALSE;
		$page_key = (isset($_POST['option_page'])) ? $_POST['option_page'] : FALSE;
		if($page_key == $page->slug && $action == 'update') {

			// Extract the tab id from the referer post
			$referrer = (isset($_POST['_wp_http_referer'])) ? $_POST['_wp_http_referer'] : '';
			$matches = array();
			preg_match('/tab=([^&]*)/', $referrer , $matches );

			// Build the Tab Sections for the submitted tab
			foreach( $options_settings as $title=>$section_settings ) {	
				
				$id = str_replace('-', '_', sanitize_title_with_dashes($title));

				if(isset($matches[1]) && $matches[1] == $id) {
					
					// Tab submitted was determined
					$this->tabs[] = new Tab( $title, $id, $this->page, $section_settings, TRUE );
					break;
				
				}

				// Cache first id for use if no tab match is found
				if($counter == 0) {
					$first = array(
						'id' => $id,
						'title' => $title,
						'settings' => $section_settings
						);
				}
				
				$counter++;
				
			}

			// If no tab was created
			// create the default tab with the first id
			if(empty($this->tabs)) {

				$this->tabs[] = new Tab( $first['title'], $first['id'], $this->page, $first['settings'], TRUE );
			}
			
		} else {

			// Runs when displaying the options page
			// Show the first tab as active by default		
			foreach( $options_settings as $title=>$section_settings ) {	

				$id = str_replace('-', '_', sanitize_title_with_dashes($title));	
				
				// Each Key Is Tab
				// Set first one to active by default
				if($counter == 0) {
					$this->tabs[] = new Tab( $title, $id, $this->page, $section_settings, TRUE );
				} else {
					$this->tabs[] = new Tab( $title, $id, $this->page, $section_settings );
				}

				$counter++;
				
			}
		}
		
	}

	public function render()
	{

		$active_tab_id = (isset($_GET['tab'])) ? $_GET['tab'] : $this->tabs[0]->id;

		do_action('pcs_render_option_page');

		echo $this->page->markup_top;
        
       //  echo 'xxxxxxxxxxx';

		echo '<form method="post" action="options.php">';

		settings_errors();

		// Output all tab headings
		echo '<h2 class="nav-tab-wrapper">';
		foreach($this->tabs as $tab) {
			
			// Outbut Tabs
			if( $tab->active ) {
				
				echo $tab->get_anchor(true);
				
				// Cache active tab to reneder sections later
				$active_tab = $tab;
				
			} else {

				echo $tab->get_anchor();	
			}

		}
		echo '</h2>';

		settings_fields( $this->page->slug );
		do_settings_sections( $this->page->slug );

		submit_button();

		echo '</form>';

	// $this->render_reset_form( $active_tab ); 

		echo $this->page->markup_bottom;

	}

}