<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.model');

class Form2ContentKmlFeedsModelKmlFeed extends JModelLegacy
{
	protected $text_prefix = 'COM_FORM2CONTENTKMLFEEDS';

	public function getItem($pk = null)
	{
		// Initialise variables.
		$table	= $this->getTable();

		if ($pk > 0) {
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError()) {
				$this->setError($table->getError());
				return false;
			}
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

		if (property_exists($item, 'params')) {
			$registry = new JRegistry;
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}

		return $item;
	}
	
	public function getTable($type = 'KmlFeed', $prefix = 'Form2ContentKmlFeedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * 
	 * Return a list of filter field objects for the given KML Feed
	 * 
	 * @param	int 	$id		Id of the KML Feed
	 * 
	 * @return  array 			Array of filter field objects
	 * 
	 * @since   6.2.0
	 * 
	 */
	public function getFilterList($id)
	{
		$fields	= array();
		$db 	= $this->getDbo(); 
		$query 	= $db->getQuery(true);
		
		$query->select('ff.*');
		$query->from('#__f2c_kml_feed_filter ff');
		$query->where('ff.feed_id = ' . (int)$id);
		
		$query->select('ft.name');
		$query->innerJoin('#__f2c_kml_feed_fieldtype ft ON ff.field_type_id = ft.id');
		
		$db->setQuery($query);
		
		$list = $db->loadObjectList();
		
		if(count($list))
		{
			foreach ($list as $listItem)
			{
				$listItem->settings = new JRegistry($listItem->settings);
				
				$fieldClassName = 'F2ckmlfeedsField'.$listItem->name;
				$fields[] = new $fieldClassName($listItem);
			}
		}
		
		return $fields;		
	}
	
	/**
	 * 
	 * Return a list of form ids that match the filters as defined for the KML Feed
	 * 
	 * @param 	object $feed	KML Feed
	 * 
	 * @return	array			Array of formd ids
	 * 
	 * @since   6.2.0
	 * 
	 */
	public function getFormIdList($feed)
	{
		$feedFilterList = $this->getFilterList($feed->id);
		$db 			= $this->getDbo();
		$currentDate 	= JFactory::getDate();
		$nullDate 		= $db->getNullDate();				
		$query 			= $db->getQuery(true);
		
		$query->select('f.id AS formid');
		$query->from('#__f2c_form f');
		$query->where('f.projectid = ' . (int)$feed->project_id);

		// Only select published articles
		$query->join('INNER', '#__content c ON f.reference_id = c.id');
		$query->where('c.state = 1');
		$query->where('(c.publish_up < \''.$currentDate->toSql().'\' OR c.publish_up = \''.$nullDate.'\')');
		$query->where('(\''.$currentDate->toSql().'\' < c.publish_down OR c.publish_down = \''.$nullDate.'\')');
		
		// Apply the filter fields
		if(count($feedFilterList))
		{
			foreach($feedFilterList as $filterField)
			{
				$filterField->filter($query);
			}
		}
		
		$db->setQuery($query);

		return $db->loadColumn();									
	}
	
	private function getFieldValue($key, $array, $attribute = 'VALUE', $default = '')
	{
		if(array_key_exists($key, $array) && array_key_exists($attribute, $array[$key]->attributes))
		{			
			return $array[$key]->attributes[$attribute];
		}	
		else
		{
			return $default;
		}
	}
	
	public function hasExpired($feed)
	{
		// check if the feed's cache has expired.
		$jConfig			= JFactory::getConfig();
		$tzoffset 			= $jConfig->get('config.offset');
		$lastRenderDate 	= JFactory::getDate($feed->last_render_date, $tzoffset);
		$nextRenderDateUnix = $lastRenderDate->toUnix() + $feed->cache_duration;
		
		return (time() > $nextRenderDateUnix);
	}
	
	public function updateRenderDate($feedId)
	{
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		
		$query->update('#__f2c_kml_feed');
		$query->set('last_render_date = \''.date('Y-m-d H:i:s').'\'');
		$query->where('id = '.(int)$feedId);

		$db->setQuery($query);
		$db->query();			
	}
	
	public function getContentTypeFields($feedId)
	{
		$contentTypeFields = array();
		
		// load all Content Type fields	
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		
		$query->select('pfl.*, ft.name, ft.name AS fieldtypename, ft.classification_id');
		$query->from('#__f2c_kml_feed kfd');
		$query->join('INNER','#__f2c_projectfields pfl ON kfd.project_id = pfl.projectid');
		$query->join('INNER', '#__f2c_fieldtype ft on pfl.fieldtypeid = ft.id');
		$query->where('kfd.id = '.(int)$feedId);
		
		$db->setQuery($query);
		
		$fieldList = $db->loadObjectList();
		
		foreach($fieldList as $field)
		{
			// Dynamically create F2C field
			$className 						= 'F2cField'.$field->name;
			$fldData 						= new $className($field);
			$contentTypeFields[$field->id]	= $fldData;			
		}
		
		return $contentTypeFields;		
	}

	/*
	 * Get the data structure with the fields data of the form necessary for parsing the template
	 */
	public function getFormData($fieldsUsedInTemplate, $formIds)
	{
		if(count($formIds) == 0)
		{
			// Empty feed
			return array();	
		}

		// Load the forms we need to render in the feed
		$query = $this->_db->getQuery(true);
		
		$query->select('frm.id, frm.title, frm.alias, frm.created, frm.modified, frm.modified_by, frm.publish_up, frm.publish_down, frm.reference_id');
		$query->select('frm.catid, frm.metakey, frm.metadesc, frm.created_by, frm.created_by_alias, frm.projectid, frm.language');
		$query->select('frm.extended, frm.featured');
		$query->from('#__f2c_form frm');
		$query->select('cat.title AS catTitle, cat.alias AS catAlias');
		$query->join('LEFT', '#__categories cat ON frm.catid = cat.id');
		$query->select('usr.name AS author, usr.username AS authorUsername, usr.email as authorEmail');
		$query->join('LEFT', '#__users usr ON frm.created_by = usr.id');
		$query->where('frm.id IN (' . implode(',', $formIds) . ')');

		$this->_db->setQuery($query);
		$formList = $this->_db->loadObjectList('id');
		
		$query = $this->_db->getQuery(true);

		$query->select('pf.*, fc.id AS fieldcontentid, frm.id as formid, pf.id as fieldid, fc.attribute, fc.content, ft.name, ft.name AS fieldtypename, ft.classification_id');
		$query->from('#__f2c_form frm');
		$query->innerJoin('#__f2c_projectfields pf ON frm.projectid = pf.projectid and pf.id IN (' . implode(',', $fieldsUsedInTemplate) . ')');
		$query->innerJoin('#__f2c_fieldtype ft on pf.fieldtypeid = ft.id');
		$query->leftJoin('#__f2c_fieldcontent fc ON fc.formid = frm.id AND fc.fieldid = pf.id');
		$query->where('frm.id IN (' . implode(',', $formIds) . ')');
		$query->order('pf.ordering, pf.fieldtypeid');
		
		$this->_db->setQuery($query);
		
		$fieldContentList = $this->_db->loadObjectList();

		$modelForm = new Form2contentModelForm();
		$fieldContentList = $this->createFormDataObjects($fieldContentList);
		
		if(count($formList))
		{
			foreach($formList as $form)
			{
				if(array_key_exists($form->id, $fieldContentList))
				{
					$form->fields = $fieldContentList[$form->id];
				}
				else
				{
					$form->fields = array();
				}
				
				$form->tags = array();
				
				// Load item tags
              	$form->extended = new JRegistry($form->extended);
              	$tagList = $form->extended->get('tags');
                	
               	if(!empty($tagList))
               	{
					$form->tags = explode(',', $tagList);
               	} 		               	              											
			}
		}
		
		return $formList; 
	}	
	
	private function createFormDataObjects($fieldContentList)
	{
		$formData  = null;
		$fldData 	= null;
		$forms		= array();
		
		if(count($fieldContentList))
		{
			foreach($fieldContentList as $fieldContent)
			{
				if(!array_key_exists($fieldContent->formid, $forms))
				{
					$forms[$fieldContent->formid] = array();
				}
				
				$formData =& $forms[$fieldContent->formid];
				
				if(!array_key_exists($fieldContent->fieldname, $formData))
				{
					// Dynamically create F2C field
					$className 							= 'F2cField'.$fieldContent->name;
					$fldData 							= new $className($fieldContent);
					$formData[$fieldContent->fieldname] = $fldData;
				}
				
				$fldData->setData($fieldContent);
			}
		}
		
		return $forms;
	}
}
?>