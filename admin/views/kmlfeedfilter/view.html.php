<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'kmlfeed.php');

JForm::addFieldPath(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'fields');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewKmlFeedFilter extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	protected $mslValues;
	protected $field;
		
	function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$model			= $this->getModel();
		$this->field	= $model->getField($this->item->field_type_id);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}

		$this->field->prepareFormFilterField($this->form, $this->item);
		
		$this->addToolbar();
		
		parent::display($tpl);		
	}
	
	protected function addToolbar()
	{
		$isNew = ($this->item->id == 0);
	
		JFactory::getApplication()->input->setVar('hidemainmenu', true);

		$feedModel = new Form2ContentKmlFeedsModelKmlFeed();
		$feed = $feedModel->getItem($this->item->feed_id);
		$title = JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEEDFILTER_'.($isNew ? 'ADD' : 'EDIT'));
		$title .= ' (' . $feed->title . ')';
		
		JToolBarHelper::title($title, 'article-add.png');
		
		// Build the actions for new and existing records.
		if ($isNew)  
		{
			JToolBarHelper::apply('kmlfeedfilter.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('kmlfeedfilter.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('kmlfeedfilter.cancel', 'JTOOLBAR_CANCEL');
		}
		else 
		{
			JToolBarHelper::apply('kmlfeedfilter.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('kmlfeedfilter.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('kmlfeedfilter.cancel', 'JTOOLBAR_CLOSE');
		}		
	}	
}
?>