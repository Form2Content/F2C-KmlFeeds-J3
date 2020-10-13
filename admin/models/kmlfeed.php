<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

jimport('joomla.application.component.modeladmin');

class Form2ContentKmlFeedsModelKmlFeed extends JModelAdmin
{
	protected $text_prefix 		= 'COM_FORM2CONTENTKMLFEEDS';
	public 	  $contentTypeId	= 0;
	
	function __construct($config = array())
	{
		parent::__construct($config);
	
		$jinput = JFactory::getApplication()->input;
		
		// try to load the contentType
		$this->contentTypeId = $jinput->getInt('project_id', $jinput->getInt('contenttypeid'));
		
		if($this->contentTypeId == 0)
		{
			if(array_key_exists('jform', $_POST))
			{
				$this->contentTypeId = (int)$_POST['jform']['project_id'];
			}
		}
	}
	
	public function getTable($type = 'KmlFeed', $prefix = 'Form2ContentKmlFeedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{		
		$item = parent::getItem($pk); 
		
		if(!$item->id)
		{
			$user 		= JFactory::getUser();
			$timestamp	= JFactory::getDate()->toSql();
			
			// Initialize some values
			$item->project_id 	= $this->contentTypeId;				
			$item->created_by	= $user->id;
			$item->created		= $timestamp;				
		}
		else 
		{
			$this->contentTypeId = $item->project_id;
		}
		
		// Add some extra information to the object
		$item->creator = JUser::getInstance($item->created_by)->name;
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('title');
		$query->from('`#__f2c_project`');
		$query->where('id = ' . (int)$this->contentTypeId);

		$db->setQuery($query);
		$contentType = $db->loadObject();
		
		$item->contenttype = $contentType ? $contentType->title : ' ';			
		$item->cacheUrl = JURI::root(false) . 'index.php?option=com_form2contentkmlfeeds&task=kmlfeed.display&view=kmlfeed&format=raw&id=' . $item->id;				
		$item->renderUrl = JURI::root(false) . 'index.php?option=com_form2contentkmlfeeds&task=kmlfeed.render&view=kmlfeed&format=raw&id=' . $item->id;				
		
		return $item;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_form2contentkmlfeeds.kmlfeed', 'kmlfeed', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) 
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_form2contentkmlfeeds.edit.kmlfeed.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}

		return $data;
	}

	public function save($data)
	{
		// Allow XML for some fields
		$data['extra_hdr_data'] = $_POST['jform']['extra_hdr_data'];
		$data['extra_plm_data'] = $_POST['jform']['extra_plm_data']; 
		
		return parent::save($data);
	}
	
	public function delete(&$pks)
	{
		// Initialise variables.
		$pks				= (array)$pks;
		$context 			= $this->option.'.'.$this->name;
		$kmlFeedTable		= $this->getTable();
		
		// Iterate the items to delete each one.
		foreach ($pks as $pk) 
		{
			if($kmlFeedTable->load($pk)) 
			{
				// Delete the Filter Field definitions
				$query = $this->_db->getQuery(true);
				$query->delete('#__f2c_kml_feed_filter');
				$query->where('feed_id = ' . (int)$pk);
				
				$this->_db->setQuery($query);
				
				if(!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}

				// Delete the Content Type			
				if (!$kmlFeedTable->delete($pk)) 
				{
					$this->setError($kmlFeedTable->getError());
					return false;
				}
			}
			else
			{
				$this->setError($kmlFeedTable->getError());
				return false;
			}						
		}

		// Clear the component's cache
		$cache = JFactory::getCache($this->option);
		$cache->clean();

		return true;
	}
	
	public function getContentTypeSelectList($publishedOnly = true)
	{
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$user 	= JFactory::getUser();
		
		$query->select('id AS value, title AS text');
		$query->from('`#__f2c_project`');				
		if($publishedOnly) $query->where('published = 1');
		$query->order('title'); 
		$db->setQuery($query);
		
		$contentTypeList = $db->loadObjectList();
		
		return $contentTypeList;			
	}
}
?>