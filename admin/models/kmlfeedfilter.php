<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');

class Form2ContentKmlFeedsModelKmlFeedFilter extends JModelAdmin
{
	protected $text_prefix 		= 'COM_FORM2CONTENTKMLFEEDS';
	public 	  $feedId			= 0;
	
	function __construct($config = array())
	{
		parent::__construct($config);
	
		$input = JFactory::getApplication()->input;
		
		// try to load the contentType
		$this->feedId = $input->getInt('feed_id', $input->getInt('feedid'));
		
		if($this->feedId == 0)
		{
			if(array_key_exists('jform', $_POST))
			{
				$this->feedId = (int)$_POST['jform']['feed_id'];
			}
		}
	}
	
	public function getTable($type = 'KmlFeedFilter', $prefix = 'Form2ContentKmlFeedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{		
		$item = parent::getItem($pk); 
		
		if(!$item->id)
		{
			$input = JFactory::getApplication()->input;
			
			// Initialize some values
			$item->feed_id 	= $this->feedId;
			
			$arrFilterFieldSelect 	= explode('_', $input->getString('filterfieldselect'));
			$item->field_type_id	= $arrFilterFieldSelect[0];
			$item->field_id			= count($arrFilterFieldSelect == 2) ? $arrFilterFieldSelect[1] : null;
		}
		else 
		{
			$this->feedId = $item->feed_id;			
		}
		
		// Convert the settings field to an array.
		$registry = new JRegistry;
		$registry->loadString($item->settings);			
		$item->settings = $registry->toArray();			
	
		if($item->field_id > 0)
		{
			// Add the fieldname to the object
			$db		= JFactory::getDbo();
			$query 	= $db->getQuery(true);
			
			$query->select('fieldname, settings')->from('#__f2c_projectfields')->where('id = '.(int)$item->field_id);
	
			$db->setQuery($query);
			$fieldInfo = $db->loadObject();
			
			$item->fieldname 		= $fieldInfo->fieldname;
			$item->fieldsettings	= new JRegistry();
			
			$item->fieldsettings->loadString($fieldInfo->settings);
		}
		
		return $item;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// get the field name
		$input 	= JFactory::getApplication()->input;
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$id		= $input->getInt('id');
		
		// Is this a new field?
		if(empty($id))
		{
			// new field
			if($input->getString('filterfieldselect'))
			{
				// Coming from the field select screen
				$arrFilterFieldSelect 	= explode('_', $input->getString('filterfieldselect'));
				$kmlFieldId 			= $arrFilterFieldSelect[0];
				
				$query->select('name')->from('#__f2c_kml_feed_fieldtype')->where('id='.$kmlFieldId);	
			}	
			else
			{
				// Coming from field form
				$jForm = $input->get('jform', array(), 'ARRAY');
				
				$query->select('name')->from('#__f2c_kml_feed_fieldtype')->where('id='.(int)$jForm['field_type_id']);
			}		
		}
		else 
		{
			// existing field
			$query->select('t.name');
			$query->from('#__f2c_kml_feed_filter f');
			$query->join('INNER', '#__f2c_kml_feed_fieldtype t ON f.field_type_id = t.id');
			$query->where('f.id='.$input->getInt('id'));
		}

		$db->setQuery($query);
		$fieldname = strtolower($db->loadResult());

		// Get the form.
		$form = $this->loadForm('com_form2contentkmlfeeds.kmlfeedfilter', JPATH_COMPONENT_SITE.'/libraries/form2contentkmlfeeds/field/admin/forms/'.$fieldname.'.xml', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) 
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_form2contentkmlfeeds.edit.kmlfeedfilter.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}

		return $data;
	}

	public function getFilterFieldSelectList()
	{
		$db 				= JFactory::getDBO();
		$arrGroupedFields	= array();
		$arrSelectOptions	= array();
		$query 				= $db->getQuery(true);
		
		// Get all Content Type Fields
		$query->select('pf.id, pf.fieldname, ft.name');
		$query->from('#__f2c_projectfields pf');
		$query->innerJoin('#__f2c_fieldtype ft ON pf.fieldtypeid = ft.id');
		$query->innerJoin('#__f2c_kml_feed fd ON pf.projectid = fd.project_id');
		$query->where('fd.id = '.(int)$this->feedId);
		$query->order('pf.fieldname');
		
		$db->setQuery($query);
		
		$contentTypeFieldList = $db->loadObjectList();
		
		// Group the fields by type
		foreach($contentTypeFieldList as $contentTypeField)
		{
			if(!array_key_exists($contentTypeField->name, $arrGroupedFields))
			{
				$arrGroupedFields[$contentTypeField->name] = array();
			}
			
			$arrGroupedFields[$contentTypeField->name][] = $contentTypeField;
		}
		
		// Get the field connections
		$query = $db->getQuery(true);
		
		$query->select('id, name, description')->from('#__f2c_kml_feed_fieldtype')->order('description');
		
		$db->setQuery($query);
		
		$kmlFieldList = $db->loadObjectList();
		
		foreach ($kmlFieldList as $kmlField) 
		{
			$fieldClassName = 'F2ckmlfeedsFieldAdmin'.$kmlField->name;
			$arrConnectedFields = call_user_func($fieldClassName.'::fieldConnections');
			
			if(count($arrConnectedFields))
			{
				foreach($arrConnectedFields as $connectedField)
				{
					if(array_key_exists($kmlField->name, $arrGroupedFields))
					{
						foreach($arrGroupedFields[$kmlField->name] as $contentTypeField)
						{
							$arrSelectOptions[$kmlField->id.'_'.$contentTypeField->id] = $contentTypeField->fieldname . ' ('.$kmlField->description.')';
						}
					}
				}
			}
			else
			{
				$arrSelectOptions[$kmlField->id.'_'] = $kmlField->description;
			}
			
		}
		
		return $arrSelectOptions;			
	}
	
	/**
	 * Generate and return a field object based on the field type id
	 *
	 * @param	int			$fieldtypeid	Id of the field type
	 * 
	 * @return  object
	 * 
	 * @since   6.2.0
	 */
	public function getField($fieldtypeid)
	{
		$db		= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		
		$query->select('name')->from('#__f2c_kml_feed_fieldtype')->where('id = '. $fieldtypeid);
		$db->setQuery($query);
		
		$fieldClassName = 'F2ckmlfeedsFieldAdmin'.$db->loadResult();
		return new $fieldClassName();
	}

	/**
	 * Load the default form with mandatory fields and add them to the user defined form
	 *
	 * @param	JForm	$form	Form object
	 * @param	object	$data	array of submitted data
	 * @param	string	$group	group name
	 * 
	 * @return  void
	 * 
	 * @since   6.2.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		// load the default form with the mandatory controls
		$defaultForm = new SimpleXMLElement(JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/models/forms/kmlfeedfilter.xml'));
		
		foreach($defaultForm->fieldset->field as $xmlField)
		{
			// Add the field to the current form
			$form->setField($xmlField);
		}
	}
}
?>