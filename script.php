<?php
defined('JPATH_PLATFORM') or die('Restricted acccess');

/**
 * Script file of Form2ContentKmlFeeds component
 */
class com_Form2ContentKmlFeedsInstallerScript
{
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) 
    {
		$path = JPATH_SITE . '/media/com_form2contentkmlfeeds';

		if(!JFolder::exists($path))
		{
			JFolder::create($path, 0775);
		}		
	}
 
        /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) 
    {
    }
 
        /**
     * method to update the component
     *
     * @return void
     */
	function update($parent) 
    {
    	$db = JFactory::getDBO();
    	
		// add new tables (release 6.2.0)		
		$db->setQuery('show tables like \''. $db->replacePrefix('#__f2c_kml_feed_fieldtype') . '\'');
		
		if(!$db->loadResult())
		{
			$db->setQuery('CREATE TABLE `#__f2c_kml_feed_fieldtype` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `description` varchar(45) NOT NULL,
							  `name` varchar(45) NOT NULL,
							  PRIMARY KEY (`id`))');
			$db->query();
			
			// Insert fieldtypes
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (-1, \'Joomla Author\', \'Author\');');
			$db->query();
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (4, \'Checkbox\', \'Checkbox\');');
			$db->query();
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (5, \'Single select list\', \'Singleselectlist\');');
			$db->query();
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (10, \'Multi select list (checkboxes)\', \'Multiselectlist\');');
			$db->query();
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (12, \'Date Picker\', \'Datepicker\');');
			$db->query();
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (15, \'Database Lookup\', \'Databaselookup\');');
			$db->query();
			$db->setQuery('INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (17, \'Database Lookup (Multi select)\', \'Databaselookupmulti\');');		
			$db->query();
			
			// Extend f2c_kml_feed_filter table
			$db->setQuery('ALTER TABLE #__f2c_kml_feed_filter ADD COLUMN `field_type_id` int(10) NOT NULL AFTER `feed_id`');
			$db->execute();
			$db->setQuery('ALTER TABLE #__f2c_kml_feed_filter ADD COLUMN `title` varchar(100) NOT NULL AFTER `id`');
			$db->execute();
			
			// Fill the title and field_type_id fields
			$db->setQuery('UPDATE #__f2c_kml_feed_filter ff 
							LEFT JOIN #__f2c_projectfields pf ON ff.field_id = pf.id 
							SET ff.field_type_id = IFNULL(pf.fieldtypeid, -1), ff.title = IFNULL(pf.title, \'Joomla Author\');');
			$db->execute();
		}
		
		// add new field (release 6.3.0)
		$db->setQuery('INSERT INTO #__f2c_kml_feed_fieldtype (`name`, `description`) SELECT \'Category\',\'Joomla Category\' FROM #__f2c_kml_feed_fieldtype WHERE name = \'Category\' HAVING COUNT(*) = 0');
		$db->execute();
    }
 
    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
	function preflight($type, $parent) 
    {
    }
 
        /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
    }	
}
?>