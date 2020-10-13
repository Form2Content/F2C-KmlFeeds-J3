<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_form2content'.DIRECTORY_SEPARATOR.'factory.form2content.php';

// Include dependencies
jimport('joomla.application.component.controller');

require_once JPATH_SITE.'/components/com_form2content/utils.form2content.php';
require_once JPATH_SITE.'/components/com_form2content/const.form2content.php';

JLoader::registerPrefix('F2c', JPATH_SITE.'/components/com_form2content/libraries/form2content');
JLoader::registerPrefix('F2ckmlfeeds', JPATH_SITE.'/components/com_form2contentkmlfeeds/libraries/form2contentkmlfeeds');
JLoader::register('Form2contentModelForm', JPATH_SITE.'/components/com_form2content/models/form.php');
JLoader::register('F2cParser', JPATH_SITE.'/components/com_form2content/parser.form2content.php');
JLoader::register('SimpleXMLExtended', JPATH_SITE.'/components/com_form2content/libraries/SimpleXMLExtended.php');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Form2ContentKmlFeeds');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
?>