<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.controllerform');

class Form2ContentKmlFeedsControllerKmlFeed extends JControllerForm
{
	public function __construct($config = array())
	{
		// Access check.
		if (!JFactory::getUser()->authorise('core.admin')) 
		{
			return JError::raiseError(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		parent::__construct($config);
	}
	
	function contenttypeselect()
	{
		$view = $this->getView('contenttypeselect', 'html');
		$view->setModel( $this->getModel('kmlfeed'), true );
		$view->display();			
	}
	
	function add()
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$context	= "$this->option.edit.$this->context";

		// Clear the record edit information from the session.
		$app->setUserState($context.'.data', null);

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend().'&project_id='.$app->input->getInt('projectid'), false));

		return true;
	}
}
?>