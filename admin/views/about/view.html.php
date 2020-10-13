<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewAbout extends JViewLegacy
{
	function display($tpl = null)
	{
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') 
		{
			Form2ContentKmlFeedsHelperAdmin::addSubmenu('about');
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JHtmlSidebar::setAction('index.php?option=com_form2contentkmlfeeds&view=about');		
		$title = JText::_('COM_FORM2CONTENTKMLFEEDS_FORM2CONTENTKMLFEEDS') . ': ' . JText::_('COM_FORM2CONTENTKMLFEEDS_ABOUT');			
		JToolBarHelper::title($title, 'generic.png');		
	}
}

?>