<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'factory.form2content.php';

jimport('joomla.application.component.controller');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_form2contentkmlfeeds')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}

JLoader::register('Form2ContentKmlFeedsHelperAdmin', __DIR__ . '/helpers/form2contentkmlfeeds.php');
JLoader::registerPrefix('F2ckmlfeeds', JPATH_SITE.'/components/com_form2contentkmlfeeds/libraries/form2contentkmlfeeds');

$controller = JControllerLegacy::getInstance('Form2ContentKmlFeeds');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
?>