<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

/**
 * Custom field base class
 * 
 * This class supports functionality for custom fields e.g. for rendering them.
 * All custom fields must implement this class.
 * 
 * @package     Joomla.Site
 * @subpackage  com_form2contentkmlfeeds
 * @since       6.2.0
 */
abstract class F2ckmlfeedsFieldBase
{
	protected $id;
	protected $fieldId;
	protected $settings;

	/**
	 * Constructor. This method will initialize the basic field parameters
	 *
	 * @param	object		$field		Field object as created from the database information
	 * 
	 * @return  void
	 * 
	 * @since   6.2.0
	 */
	function __construct($field)
	{
		$this->id			= $field->id;
		$this->fieldId		= $field->field_id;
		$this->settings 	= $field->settings;
	}
	
	abstract function filter($query);	
}
?>