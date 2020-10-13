<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

class Form2ContentKmlFeedsControllerKmlFeed extends JControllerLegacy
{
	function render()
	{
		$view 	= $this->getView('KmlFeed', 'raw');
		$model 	= $this->getModel('KmlFeed');
		
		$view->setModel($model, true);		
		$view->render();
	}
}
?>