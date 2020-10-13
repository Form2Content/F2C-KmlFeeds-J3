<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewContentTypeSelect extends JViewLegacy
{
	protected $contentTypeList;

	function display($tpl = null)
	{
		$this->addToolbar();

		$model = $this->getModel('kmlfeed');
		$this->contentTypeList = $model->getContentTypeSelectList(false);		

		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDS_MANAGER').': '. JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEED_ADD'));
		JToolBarHelper::custom('kmlfeed.add','forward','forward',JText::_('COM_FORM2CONTENTKMLFEEDS_NEXT'), false);
		JToolBarHelper::cancel('kmlfeed.cancel', 'JTOOLBAR_CANCEL');	
	}
}

?>