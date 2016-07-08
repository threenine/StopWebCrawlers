<?php namespace stopwebcrawlersWPSettings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Base field class.
 */
class Field {
	
	protected $name;
	protected $original_name;
	protected $id;
	protected $title;
	protected $value;
	protected $reset_value;
	protected $markup;
	protected $args;

	public function __construct ( $settings = array() )
	{


		// Default settings
		$default_settings = array(
			'id' => (isset($settings['id'])) ? $settings['id'] : $settings['name'],
			'title' => (isset($settings['label'])) ? $settings['label'] : '',
			'args' => (isset($settings['args'])) ? $settings['args'] : array('label_for'=>$settings['name']),
			'reset_value' => (isset($settings['value'])) ? $settings['value'] : $this->get_default_reset_value()
			);

		$settings = array_merge($default_settings, $settings);

		// Assign to properties
		foreach($settings as $key => $value) {
			if(property_exists($this, $key)) {
				$this->$key = $value;
			}
		}	

		// Get option saved value
		$value = get_option($this->name);

		if( $value === false ) {	

			// Set to reset value
			$this->value = $this->reset_value;	
		} else {

			// Set to saved value
			$this->value = $value;
		}

		// Set markup property
		$this->set_markup();
	}

	public function __get($key)
	{
		if(property_exists($this, $key)) {
			return $this->$key;
		}
	}

	public function set_value( $value ) 
	{
		$this->value = $value;
	}

	public function set_name( $value ) 
	{
		$this->name = $value;
	}

	public function set_id( $value ) 
	{
		$this->id = $value;
	}

	public function set_markup( $markup = '' ) 
	{
		$this->markup = $markup;
	}

	public function get_default_reset_value()
	{
		return '';
	}

	public function render( $args )
	{
		echo $this->markup;
	}

	public function sanitize( $dirty )
	{
		// Sanitizes during save
		if(is_array($dirty)) {
			$clean = array();
			foreach($dirty as $key=>$value) {

				if(!empty($dirty[$key])) {
					$clean[$key] = sanitize_text_field($dirty[$key]);
				}
				
			}
		} else {
			$clean = sanitize_text_field($dirty);
		}
	
		return $clean;
	}	
}


/**
 * Field with input type of text.
 */
class TextField extends Field {

	public function set_markup( $markup = '' ) 
	{
		if($markup == '') {
			$this->markup  = '<input type="text" name="' . $this->name . '" ';
			$this->markup .= 'id="' . $this->id . '" ';
			$this->markup .= 'data-reset="' . $this->reset_value . '" ';
			$this->markup .= 'value="' . $this->value . '" class="regular-text" />';
		} else {
			$this->markup = $markup;
		}		
	}
}

/**
 * Field with input type of text.
 */
class ColorField extends Field {

	public function __construct ( $settings = array() )
	{
		parent::__construct($settings);

		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
	}

	public function set_markup( $markup = '' ) 
	{
		if($markup == '') {
			$this->markup  = '<input type="text" name="' . $this->name . '" ';
			$this->markup .= 'id="' . $this->id . '" ';
			$this->markup .= 'data-reset="' . $this->reset_value . '" ';
			$this->markup .= 'value="' . $this->value . '" class="color-picker" />';
		} else {
			$this->markup = $markup;
		}		
	}

	public function admin_enqueue_scripts( $hook_suffix )
	{
		global $pcs_settings_config;
		if(!wp_script_is('pcs-color-picker', 'enqueued')) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_register_script('pcs-color-picker', $pcs_settings_config['base_uri'] . 'scripts/color-picker.js', array('wp-color-picker'));
			wp_enqueue_script('pcs-color-picker');
		}
	}
}

/**
 * Field with input type of textarea.
 */
class TextArea extends Field {

	public function set_markup( $markup = '' ) 
	{
		if($markup == '') {
			$this->markup  = '<textarea name="' . $this->name . '" ';
			$this->markup .= 'data-reset="' . $this->reset_value . '" ';
			$this->markup .= 'id="' . $this->id . '" cols="30" rows="5" >';
			$this->markup .= $this->value . '</textarea>';
		} else {
			$this->markup = $markup;
		}	
	}

	public function sanitize( $dirty )
	{
		// Sanitizes during save
		if(is_array($dirty)) {
			$clean = array();
			foreach($dirty as $key=>$value) {

				if(!empty($dirty[$key])) {
					$clean[$key] = wp_kses_post($dirty[$key]);
				}
				
			}
		} else {
			$clean = wp_kses_post($dirty);
		}
	
		return $clean;
	}	
}

/**
 * Field with input type of checkbox.
 */
class Checkbox extends Field {

	public function set_markup( $markup = '' ) 
	{
		if( $markup == '' ) {
						
			// Display setting checked when 1
			if($this->value == 1) {
				$checked = 'checked=checked';
			} else {
				$checked = '';
			}

			$this->markup  = '<input type="checkbox" name="' . $this->name . '" ';
			$this->markup .= 'id="' . $this->id . '" ';
			$this->markup .= 'data-reset="' . $this->reset_value . '" ';
			$this->markup .= 'value="1" ' . $checked . ' />';
		} else {
			$this->markup = $markup;
		}
	}

	public function get_default_reset_value()
	{
		return 0;
	}

	public function sanitize( $dirty )
	{

		// Sanitizes during save
		if(is_array($dirty)) {
			$clean = array();
			foreach($dirty as $key=>$value) {

				if(!empty($dirty[$key])) {
					$clean[$key] = 1;
				} else {
					$clean[$key] = 0;
				}
				
			}
		} else {
			if(!empty($dirty)) {
				$clean = 1;
			} else {
				$clean = 0;
			}
			
		}
	
		return $clean; 
	}	
}

/**
 * Field that provides media upload.
 */
class UploadField extends Field {

	public function __construct ( $settings = array() )
	{
		parent::__construct($settings);

		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
	}

	public function set_markup( $markup = '' ) 
	{
		if($markup == '') {
			$this->markup  = '<input type="text" name="' . $this->name . '" ';
			$this->markup .= 'id="' . $this->id . '" ';
			$this->markup .= 'data-reset="' . $this->reset_value . '" ';
			$this->markup .= 'value="' . $this->value . '" class="regular-text" />';
			$this->markup .= '<input type="button" value="Upload" class="button button-upload" data-field="' . $this->id .  '" />';
		} else {
			$this->markup = $markup;
		}
	}

	public function admin_enqueue_scripts()
	{
		global $pcs_settings_config;
		if(!wp_script_is('pcs-upload', 'enqueued')) {
			wp_enqueue_media();
			wp_register_script('pcs-upload', $pcs_settings_config['base_uri'] . 'scripts/upload.js', array('jquery'));
			wp_enqueue_script('pcs-upload');
		}
	}
}

/**
 * Field that utilizes the wp_editor.
 */
class EditorField extends Field {

	protected $editor_settings;

	public function __construct ( $settings = array() )
	{
		parent::__construct($settings);

		$this->editor_settings = (isset($settings['editor_settings'])) ? $settings['editor_settings'] : array();
	}
	
	public function render( $args )
	{
		wp_editor( $this->value, $this->name, $this->editor_settings );
	}

	public function sanitize( $dirty )
	{
		// Sanitizes during save
		if(is_array($dirty)) {
			$clean = array();
			foreach($dirty as $key=>$value) {

				if(!empty($dirty[$key])) {
					$clean[$key] = wp_kses_post($dirty[$key]);
				}
				
			}
		} else {
			$clean = wp_kses_post($dirty);
		}
	
		return $clean;
	}	
}

/**
 * Field that utilizes a select drop down.
 */
class SelectField extends Field {

	protected $select_options;

	public function __construct ( $settings = array() )
	{
		$this->select_options = (isset($settings['select_options'])) ? $settings['select_options'] : array();
		
		parent::__construct($settings);
	}

	public function set_markup( $markup = '' ) 
	{
		if($markup == '') {

			$this->markup  = '<select name="' . $this->name . '" id="' . $this->id  . '" data-reset="' . $this->reset_value . '">';
			foreach($this->select_options as $option) {
				
				// Check if selected
				if($this->value == $option['value']) {
					$this->markup .= '<option selected="selected" value="' . $option['value'] . '">' . $option['label'] . '</option>';
				} else {
					$this->markup .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
				}
			}
			$this->markup .= '</select>';
		} else {
			$this->markup = $markup;
		}
	}
}

/**
 * Field with input type of radio.
 */
class RadioField extends Field {

	protected $radio_options;

	public function __construct ( $settings = array() )
	{

		$this->radio_options = (isset($settings['radio_options'])) ? $settings['radio_options'] : array();

		parent::__construct($settings);

		if(isset($this->args['label_for'])) unset($this->args['label_for']);
	}

	public function set_markup( $markup = '' ) 
	{
		if($markup == '') {

			$counter = 1;

			$this->markup = '<div class="radio-options" data-reset="' . $this->reset_value . '">';

			foreach($this->radio_options as $option) {
				
				$this->markup .= '<label for="' . $this->id  . '_' . $counter . '">' . $option['label'] . '<input type="radio" name="' . $this->name . '" ';
				$this->markup .= 'id="' . $this->id  . '_' . $counter . '" ';
				if($this->value == $option['value']) {
					$this->markup .= 'checked="checked" ';
				}
				$this->markup .= 'value="' . $option['value'] . '" /></label>';
				$counter++;
			}

			$this->markup .= '</div>';
		} else {
			$this->markup = $markup;
		}
	}
}

class MultiField extends Field {

	protected $fields;
	protected $limit;

	public function __construct ($settings = array(), $field_objs = array() )
	{
		parent::__construct($settings);

		// 0 is unlimited
		$this->limit = (isset($settings['limit'])) ? (int) $settings['limit'] : 0;

		$this->fields = $field_objs;

		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
	}

	public function __get($key)
	{
		if(property_exists($this, $key)) {
			return $this->$key;
		}
	}

	public function admin_enqueue_scripts ()
	{
		global $pcs_settings_config;
		if(!wp_script_is('pcs-multi-field', 'enqueued')) {
			wp_enqueue_media();
			wp_register_script('pcs-multi-field', $pcs_settings_config['base_uri'] . 'scripts/multi-field.js', array('jquery-ui-sortable'));
			wp_enqueue_script('pcs-multi-field');
		}
	}



	public function render( $args )
	{

		$list_length = count($this->value);

		// Render dynamic multi fields.
		if($this->limit != 1) {
			if($list_length >= 1) {
				$this->renderMultipleFieldGroups($list_length);
			} else {

				// Could be rendering the firstgroup
				$this->renderSingleFieldGroup();
			}
		} else {

			$this->renderSingleFieldGroup();
		}
	}

	public function renderSingleFieldGroup() {

		if($this->limit != 1) {
			echo '<ul id="' . $this->id . '" class="multi-field-wrapper" data-limit="' . $this->limit . '">';
			echo '<li class="fields">';
			echo '<span class="handle"></span>';
			echo '<span class="remove-button disabled">X</span>';
		} else {
			echo '<ul id="' . $this->id . '" class="single-fieldgroup">';
			echo '<li class="fields">';
		}

		// Output default field group
		foreach($this->fields as $field) {

			echo '<div class="field">';
				echo '<label>' . $field->title . '</label><br>';
				$field->render($field->args);
			echo '</div>';
		}

		echo '</li>';
		echo '</ul>';
	}

	public function renderMultipleFieldGroups($list_length) {

		echo '<ul id="' . $this->id . '" class="multi-field-wrapper" data-limit="' . $this->limit . '">';

			for($counter = 0; $counter < $list_length; $counter++) {

				echo '<li class="fields">';

				echo '<span class="handle"></span>';

				if($counter == 0) {
					echo '<span class="remove-button disabled">X</span>';
				} else {
					echo '<span class="remove-button">X</span>';
				}
				
				foreach($this->fields as $field) {

					// Modify the name
					$name = $this->name . '['. $counter .'][' . $field->original_name . ']';
					$field->set_name($name);

					// Modify the id
					$id = $field->original_name . '_' . $counter;
					$field->set_id($id);

					// Modify the value
					if(isset($this->value[$counter][$field->original_name])) {
						$field->set_value($this->value[$counter][$field->original_name]);
					} else {
						$field->set_value($field->reset_value);
					}

					$field->set_markup();
					
					echo '<div class="field">';
						echo '<label>' . $field->title . '</label><br>';
						$field->render($field->args);
					echo '</div>';

				}

				echo '</li>';
			}

		echo '</ul>';
	}

	public function get_default_reset_value()
	{
		return array();
	}

	public function sanitize( $dirty )
	{
		// Sanitizes during save
		if(is_array($dirty)) {
			$clean = array();
			foreach($dirty as $groupKey => $fieldgroup) {

				foreach($fieldgroup as $key => $fieldval) {
					
					if(!empty($fieldgroup[$key])) {
						$clean[$groupKey][$key] = sanitize_text_field($fieldgroup[$key]);
					}
				}
			}
		} else {
			$clean = sanitize_text_field($dirty);
		}
	
		return $clean; 
	}	

}