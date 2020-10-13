<?php
// No direct access.
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.controlleradmin');

class Form2ContentKmlFeedsControllerKmlFeedfilters extends JControllerAdmin
{
	protected $default_view = 'kmlfeedfilters';

	public function __construct($config = array())
	{
		// Access check.
		if (!JFactory::getUser()->authorise('core.admin')) 
		{
			return JError::raiseError(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		parent::__construct($config);
	}

	public function &getModel($name = 'KmlFeedfilter', $prefix = 'Form2ContentKmlFeedsModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
	
	public function delete()
	{
		parent::delete();
		$this->redirect .= '&feed_id='.$this->input->getInt('feed_id');
	}
}
?>