<?php
// No direct access.
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.controlleradmin');

class Form2ContentKmlFeedsControllerKmlFeeds extends JControllerAdmin
{
	protected $default_view = 'kmlfeeds';

	public function __construct($config = array())
	{
		// Access check.
		if (!JFactory::getUser()->authorise('core.admin')) 
		{
			return JError::raiseError(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		parent::__construct($config);
	}

	public function &getModel($name = 'KmlFeed', $prefix = 'Form2ContentKmlFeedsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}	
}
?>