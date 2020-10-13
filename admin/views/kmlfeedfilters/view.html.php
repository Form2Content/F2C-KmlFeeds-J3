<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'kmlfeed.php');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewKmlFeedfilters extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $feedId;
	
	function display($tpl = null)
	{
		if (!JFactory::getUser()->authorise('core.admin')) 
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		}
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->feedId		= JFactory::getApplication()->input->getInt('feed_id');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') 
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
				
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		Form2ContentKmLFeedsHelperAdmin::addSubmenu('kmlfeedfilters');
		
		$feedModel = new Form2ContentKmlFeedsModelKmlFeed();
		$feed = $feedModel->getItem($this->feedId);
		$title = JText::_('COM_FORM2CONTENTKMLFEEDS_FORM2CONTENTKMLFEEDS') . ': ' . JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDFILTERS');
		$title .= ' (' . $feed->title . ')';
		
		JToolBarHelper::title($title, 'article.png');
		JToolBarHelper::addNew('kmlfeedfilter.filterfieldselect','JTOOLBAR_NEW');
		JToolBarHelper::editList('kmlfeedfilter.edit','JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		JToolBarHelper::trash('kmlfeedfilters.delete','JTOOLBAR_TRASH');
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
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'ft.description' => JText::_('COM_FORM2CONTENTKMLFEEDS_FIELDTYPE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
?>