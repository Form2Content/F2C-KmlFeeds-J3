<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewKmlFeed extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
		
	function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$model			= $this->getModel();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		$db =JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('id, name')->from('#__f2c_fieldtype');
		
		$db->setQuery($query);
		
		$fields = $db->loadObjectList();
		
		$arrCanContainTitle = array();
		$arrCanContainCoordinates = array();
		
		foreach ($fields as $field) 
		{
			if($field->name == 'Singlelinetext')
			{
				$arrCanContainTitle[] = $field->id;
			}
			
			if($field->name == 'Singlelinetext' || $field->name == 'Geocoder')
			{
				$arrCanContainCoordinates[] = $field->id;
			}
			
		}
		
		// Make sure the fields are filtered for the correct Content Type	
		$this->form->setFieldAttribute('title_field', 'query', sprintf($this->form->getFieldAttribute('title_field', 'query'), join(',', $arrCanContainTitle), $model->contentTypeId)); 
		$this->form->setFieldAttribute('latitude_field', 'query', sprintf($this->form->getFieldAttribute('latitude_field', 'query'), join(',', $arrCanContainCoordinates), $model->contentTypeId)); 
		$this->form->setFieldAttribute('longitude_field', 'query', sprintf($this->form->getFieldAttribute('longitude_field', 'query'), join(',', $arrCanContainCoordinates), $model->contentTypeId)); 		
		
		$this->addToolbar();
		
		JFactory::getDocument()->addStyleSheet(JURI::root(false).'media/com_form2contentkmlfeeds/css/style.css');
		
		parent::display($tpl);		
	}
	
	protected function addToolbar()
	{
		$isNew = ($this->item->id == 0);
	
		JFactory::getApplication()->input->set('hidemainmenu', true);

		JToolBarHelper::title(JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEED_'.($isNew ? 'ADD' : 'EDIT')), 'article-add.png');
		
		// Built the actions for new and existing records.
		if ($isNew)  
		{
			JToolBarHelper::apply('kmlfeed.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('kmlfeed.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('kmlfeed.cancel', 'JTOOLBAR_CANCEL');
		}
		else 
		{
			JToolBarHelper::apply('kmlfeed.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('kmlfeed.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('kmlfeed.cancel', 'JTOOLBAR_CLOSE');
		}		
	}	
}
?>