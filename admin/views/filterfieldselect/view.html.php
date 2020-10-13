<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'kmlfeed.php');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewFilterFieldSelect extends JViewLegacy
{
	protected $filterFieldList;
	protected $feedId;

	function display($tpl = null)
	{
		$this->feedId = JFactory::getApplication()->input->getInt('feed_id');
		$this->addToolbar();

		$model = $this->getModel();
		
		$this->filterFieldList = $model->getFilterFieldSelectList(false);		

		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$feedModel = new Form2ContentKmlFeedsModelKmlFeed();
		$feed = $feedModel->getItem($this->feedId);
		$title = JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDSFILTER_MANAGER').': '. JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDFILTER_ADD');
		$title .= ' (' . $feed->title . ')';
		
		JToolBarHelper::title($title);
		JToolBarHelper::custom('kmlfeedfilter.add','forward','forward',JText::_('COM_FORM2CONTENTKMLFEEDS_NEXT'), false);
		JToolBarHelper::cancel('kmlfeedfilter.cancel', 'JTOOLBAR_CANCEL');	
	}
}

?>