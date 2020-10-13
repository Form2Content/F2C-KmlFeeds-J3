<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.controllerform');

class Form2ContentKmlFeedsControllerKmlFeedFilter extends JControllerForm
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
	
	function filterfieldselect()
	{
		$view = $this->getView('filterfieldselect', 'html');		
		$view->setModel( $this->getModel('kmlfeedfilter'), true );
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
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend().'&feed_id='.$app->input->getInt('feed_id').'&filterfieldselect='.$app->input->getString('filterfieldselect'), false));

		return true;
	}
	
	protected function getRedirectToListAppend()
	{
		$input		= JFactory::getApplication()->input; 
		$jForm		= $input->get('jform', array(), 'ARRAY');
		$tmpl		= $input->getString('tmpl');
		$append		= '';

		// Setup redirect info.
		if ($tmpl) 
		{
			$append .= '&tmpl='.$tmpl;
		}
		
		$append .= '&feed_id='.(int)$jForm['feed_id'];
		
		return $append;
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$redirect = parent::getRedirectToItemAppend($recordId, $urlVar);

		$input	= JFactory::getApplication()->input; 
		$jForm	= $input->get('jform', array(), 'ARRAY');
		
		if($feedId = $jForm['feed_id'])
		{
			$redirect .= '&feed_id='.$feedId;
		}
		
		return $redirect;
	}
}
?>