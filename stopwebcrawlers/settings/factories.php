<?php namespace stopwebcrawlersWPSettings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Factory classes that register wordpress settings entities.
 *  */

/**
 * Creates and registers wordpress section entities.
 */
class SectionFactory {

	protected $sections;

	public function __construct ( $page, $section_settings, $tab = NULL ) 
	{
		
		$this->sections = array();

		// Create sections for a page
		foreach($section_settings as $title => $section) {

			$info = (isset($section['info'])) ? $section['info'] : '';
			$fields = (isset($section['fields'])) ? $section['fields'] : array();

			// If this is section is on a tabbed page
			// add the tab id to the section id to keep it unique
			if(!is_null($tab)){
				$id = $page->slug . $tab->id . '_' . str_replace('-', '_', sanitize_title_with_dashes($title));
			} else {
				$id = $page->slug . str_replace('-', '_', sanitize_title_with_dashes($title));
			}
	
			$this->sections[] = new Section( $id, $title, $info, $page, $page->slug, $fields );
		}

		add_action('admin_init', array($this, 'add_settings_sections')); 
	}

	public function add_settings_sections() 
	{

		foreach($this->sections as $section) {

			add_settings_section($section->id, $section->title, array($section, 'render'), $section->page_key );
		}
	}
}

/**
 * Creates and registers wordpress field entities.
 */
class SettingsFactory {

	protected $fields;
	protected $section;

	public function __construct ( $section, $field_settings ) 
	{

		$this->fields = array();
		$this->section = $section;

		foreach($field_settings as $field) {	
			$this->fields[] = $this->generate_field($field);
		}

		add_action('admin_init', array($this, 'add_settings_fields'));

		add_action('pcs_render_option_page', array($this, 'process_reset_options'));
	}

	public function generate_field($field)
	{
			
		if(isset($field['type'])) {
			switch($field['type']) {
				case 'text':
					return new TextField($field);
				case 'color':
					return new ColorField($field);
				case 'textarea':
					return new TextArea($field);
				case 'editor':
					return new EditorField($field);
				case 'upload':
					return new UploadField($field);
				case 'multi':
					
					if(isset($field['fields']) && is_array($field['fields'])) {
						
						// Recursively generate all the fields for the group
						$input_group = array();
						foreach($field['fields'] as $input) {	

							// Cache the original name
							// modify the id and the name of the field
							// make it an array iot store multiple values
							$input['original_name'] = $input['name'];
							$input['id'] = (isset($input['id'])) ? $input['id'] . '_0' : $input['name'] . '_0';
							$input['name'] = $field['name'] . '[0][' . $input['name'] . ']';
							$input_group[] = $this->generate_field($input);
						}

						// Create a MultiField and pass in all the fields
						return new MultiField($field, $input_group);
					
					} else {

						// Wrong array format submit default field
						return new Field($field);
					}
				case 'select':
					return new SelectField($field);
				case 'checkbox':
					return new CheckBox($field);
				case 'radio':
					return new RadioField($field);
				default:

					if(is_array($field)) {
						return new Field($field);
					} 					
			}
		}
	}

	public function add_settings_fields()
	{
		foreach($this->fields as $field) {
				add_settings_field( $field->name, $field->title, array($field, 'render'), $this->section->page_key, $this->section->id, $field->args);
				register_setting($this->section->page_key, $field->name, array($field, 'sanitize'));
		}
	}

	public function process_reset_options( $args )
	{

		if(isset($_POST['action']) && $_POST['action'] == 'reset') {

			// Check the nonce value
			if(!isset($_POST['pcs_reset_options_nonce']) || !wp_verify_nonce($_POST['pcs_reset_options_nonce'],'pcs_reset_options')) {
				 
				 die( 'Sorry, your nonce did not verify.' );
			
			} else {

				foreach($this->fields as $field) {

					$value = get_option($field->name);

					if($value != $field->reset_value) {

						if(!empty($field->reset_value)) {
													
							// Update to reset value if there is one
							update_option($field->name, $field->reset_value);
						} else {

							delete_option($field->name);
						}

						// Update screen values
						$field->set_value($field->reset_value);
						$field->set_markup();
					}
				}
			}
		}
	}
}