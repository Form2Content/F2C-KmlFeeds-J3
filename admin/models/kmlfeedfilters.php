<?php
defined('JPATH_PLATFORM') or die();

jimport('joomla.application.component.modellist');

class Form2ContentKmlFeedsModelKmlFeedfilters extends JModelList
{
	protected $feedId;
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) 
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'description', 'ft.description',
			);
		}
		
		parent::__construct($config);
		
		$this->feedId = JFactory::getApplication()->input->getInt('feed_id', 0);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*');
		$query->from('`#__f2c_kml_feed_filter` a');
		$query->where('a.feed_id = ' . (int)$this->feedId);
		
		$query->select('ft.description');
		$query->join('INNER', '#__f2c_kml_feed_fieldtype ft ON a.field_type_id = ft.id');
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
		return $query;
	}
}
?>
