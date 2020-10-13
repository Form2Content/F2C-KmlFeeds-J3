<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'renderer.form2contentkmlfeeds.php');

jimport('joomla.application.component.view');

class Form2ContentKmlFeedsViewKmlFeed extends JViewLegacy
{
	public function display($tpl = null)
	{
		// Only render the feed when the cached version hasn't expired
		$this->_render(false);
	}

	public function render()
	{
		// Render the feed
		$this->_render(true);
	}
	
	private function _render($render = true)
	{
		$model 		= $this->getModel();
		$item 		= $model->getItem(JFactory::getApplication()->input->getInt('id'));
		$renderer 	= new Form2ContentKmlFeedsRenderer($item, $model);
		
		if(!$item->published)
		{
			// Do not render unpublished feeds
			die('unpublished');
		}
		
		if($render || $model->hasExpired($item))
		{
			// Generate the KML file			
			$renderer->writeKmlFile();
		}
		
		header("Content-Type: application/vnd.google-earth.kml+xml; charset=utf-8");
		
		// Deliver the feed from the cache		
		echo $renderer->getFeedFromCache();							
	}
}