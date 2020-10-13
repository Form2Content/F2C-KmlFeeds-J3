<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.view');
;
class Form2ContentKmlFeedsViewKmlFeeds extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $f2cConfig;
	protected $nullDate;
	
	function display($tpl = null)
	{
		if (!JFactory::getUser()->authorise('core.admin')) 
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		}
		
		$db					= $this->get('Dbo');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->f2cConfig 	= F2cFactory::getConfig();
		$this->nullDate		= $db->getNullDate();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
				
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') 
		{
			Form2ContentKmLFeedsHelperAdmin::addSubmenu('kmlfeeds');
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
				
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{	
		JToolBarHelper::title(JText::_('COM_FORM2CONTENTKMLFEEDS_FORM2CONTENTKMLFEEDS') . ': ' . JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDS'), 'article.png');

		JToolBarHelper::addNew('kmlfeed.contenttypeselect','JTOOLBAR_NEW');
		JToolBarHelper::editList('kmlfeed.edit','JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		JToolBarHelper::custom('kmlfeeds.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('kmlfeeds.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::trash('kmlfeeds.delete','JTOOLBAR_TRASH');
	}
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   6.0.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.created' => JText::_('COM_FORM2CONTENTKMLFEEDS_DATE_CREATED'),
			'a.modified' => JText::_('COM_FORM2CONTENTKMLFEEDS_DATE_MODIFIED'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
?>