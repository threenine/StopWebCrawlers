<?php namespace stopwebcrawlersWPSettings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Settings container entity classes.
 *
 */

/**
 * Options page entity.
 */


class Page {

	protected $slug;
	protected $hook;
	protected $page_title;
	protected $menu_title;
	protected $capability;
	protected $icon;
	protected $markup_top;
	protected $markup_bottom;
	protected $type;
	protected $parent_slug;

	public function __construct ( $menu_title, $settings = array() )
	{
		$this->menu_title = $menu_title;

		$default_settings = array(
				'slug' => (isset($settings['slug'])) ? $settings['slug'] : sanitize_title_with_dashes($menu_title),
				'page_title' => (isset($settings['page_title'])) ? $settings['page_title'] : $menu_title,
				'capability' => (isset($settings['capability'])) ? $settings['capability'] : 'manage_options',
				'icon' => (isset($settings['icon'])) ? $settings['icon'] : 'icon-options-general',
				'type' => 'theme'
		);

		$settings = array_merge($default_settings, $settings);

		// Assign to properties
		foreach($settings as $key => $value) {
			if(property_exists($this, $key)) {
				$this->$key = $value;
			}
		}

		// Initialize Default Top & Bottom Markup
		$this->set_markup_top();
		$this->set_markup_bottom();

	}

	public function __get($key)
	{
		if(property_exists($this, $key)) {
			return $this->$key;
		}
	}

	public function set_hook( $value ) {
		$this->hook = $value . $this->slug;
	}

	public function set_markup_top( $markup = false )
	{
		if(!$markup) {
			$this->markup_top =  '<div class="wrap"><div id="icon-options-general" class="icon32"></div>';
			$this->markup_top .= '<h2>' . $this->menu_title . '</h2>';
		} else {
			$this->markup_top = $markup;
		}
	}

	public function set_markup_bottom( $markup = false )
	{
		if(!$markup) {
			$this->markup_bottom =  '</div>';
		} else {
			$this->markup_bottom = $markup;
		}
	}

}


/**
 * Options tab entity.
 */
class Tab {

	protected $id;
	protected $title;
	protected $page;
	protected $anchor;
	protected $active;

	public function __construct ( $title, $id, $page, $section_option_settings = array(), $active_state = FALSE )
	{
		$this->title = $title;
		$this->page = $page;
		$this->id = $id;
		$this->anchor = '<a href="?page=' . $this->page->slug . '&tab=' . $this->id . '" class="nav-tab">' . $this->title . '</a>';


		// Check the Query string to set the active state
		$this->set_active($active_state);

		// Only create sections for active tab
		if($this->active) {
			new SectionFactory( $page, $section_option_settings, $this );
		}

	}

	public function __get($key)
	{
		if(property_exists($this, $key)) {
			return $this->$key;
		}
	}

	public function set_active( $active_state )
	{
		// Determine if this is the active tab
		if(isset($_GET['tab'])) {
			if($this->id == $_GET['tab']) {
				$this->active = TRUE;
			}
		} else {
			if($active_state) {
				$this->active = TRUE;
			} else {
				$this->active = FALSE;
			}
		}
	}

	public function get_anchor( $active = false )
	{

		if($active) {
			return '<a href="?page=' . $this->page->slug . '&tab=' . $this->id . '" class="nav-tab nav-tab-active">' . $this->title . '</a>';
		}
		return $this->anchor;
	}

}


/**
 * Options section entity.
 */
class Section {

	protected $id;
	protected $title;
	protected $page;
	protected $page_key;
	protected $info;
	protected $settings_factory;

	public function __construct ( $id, $title, $info, $page, $page_key, $field_settings = array() )
	{
		$this->id = $id;
		$this->title = $title;
		$this->info = $info;
		$this->page = $page;
		$this->page_key = $page_key;

		$this->settings_factory = new SettingsFactory( $this, $field_settings );

	}

	public function __get($key)
	{
		if(property_exists($this, $key)) {
			return $this->$key;
		}
	}

	public function render()
	{
		echo '<p>' . $this->info . '</p>';
	}

}