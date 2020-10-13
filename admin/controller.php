<?php
defined('JPATH_PLATFORM') or die;

jimport('joomla.application.component.controller');

class Form2ContentKmlFeedsController extends JControllerLegacy
{
	protected $default_view = 'kmlfeeds';

	public function display($cachable = false, $urlparams = false)
	{		
		parent::display();
		return $this;
	}
}