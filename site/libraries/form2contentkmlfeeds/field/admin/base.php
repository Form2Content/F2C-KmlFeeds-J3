<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

/**
 * Custom field admin base class
 * 
 * This class supports admin functionality for custom fields e.g. for rendering them.
 * All custom fields must implement this class.
 * 
 * @package     Joomla.Site
 * @subpackage  com_form2contentsearch
 * @since       6.2.0
 */
abstract class F2ckmlfeedsFieldAdminBase
{
	abstract public function displayFilterField($form);
	abstract public function prepareFormFilterField($form, $item);
	
	static public function fieldConnections()
	{
		return array();
	}
}