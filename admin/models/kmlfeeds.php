<?php
defined('JPATH_PLATFORM') or die();

jimport('joomla.application.component.modellist');

class Form2ContentKmlFeedsModelKmlFeeds extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) 
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'published', 'a.published',
				'created', 'a.created',
				'modified', 'a.modified'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$search = $this->getUserStateFromRequest($this->context.'.kmlfeeds.filter.search', 'kmlfeeds_filter_search');
		$this->setState('contenttypes.filter.search', $search);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('kmlfeeds.filter.search');

		return parent::getStoreId($id);
	}
	
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*');
		$query->from('`#__f2c_kml_feed` AS a');
		
		// Join over the users for the author.
		$query->select('u.name AS username');
		$query->join('LEFT', '`#__users` u ON a.created_by = u.id');

		// Filter by search in title.
		$search = $this->getState('kmlfeeds.filter.search');
		
		if(!empty($search)) 
		{
			$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
			$query->where('(a.title LIKE '.$search.')');
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	
	public function getItems()
	{
		$items = parent::getItems();
		
		if(count($items))
		{
			foreach($items as $item)
			{
				$item->cacheUrl = JURI::root(false) . 'index.php?option=com_form2contentkmlfeeds&task=kmlfeed.display&view=kmlfeed&format=raw&id=' . $item->id;				
				$item->renderUrl = JURI::root(false) . 'index.php?option=com_form2contentkmlfeeds&task=kmlfeed.render&view=kmlfeed&format=raw&id=' . $item->id;				
			}
		}
		
		return $items;
	}
}
?>
