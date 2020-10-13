CREATE TABLE `#__f2c_kml_feed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `alias` varchar(100) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `title_field` int(11) NOT NULL DEFAULT '0',
  `latitude_field` int(11) NOT NULL DEFAULT '0',
  `longitude_field` int(11) NOT NULL DEFAULT '0',
  `template` varchar(100) NOT NULL DEFAULT '',
  `icon_url` varchar(255) NOT NULL,
  `last_render_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cache_duration` int(10) unsigned NOT NULL,
  `extra_hdr_data` mediumtext NOT NULL,
  `extra_plm_data` mediumtext NOT NULL,
  `plm_method` int(10) unsigned NOT NULL DEFAULT '0',
  `plm_template` varchar(100) NOT NULL DEFAULT '',  
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;

CREATE TABLE `#__f2c_kml_feed_filter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(10) unsigned NOT NULL DEFAULT '0',
  `field_id` int(10) NOT NULL DEFAULT '0',
  `settings` mediumtext NOT NULL,
  `field_type_id` int(10) NOT NULL DEFAULT '0', 
  `title` varchar(100) NOT NULL DEFAULT '', 
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;

CREATE TABLE `#__f2c_kml_feed_fieldtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;

INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (-2, 'Joomla Category', 'Category');  
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (-1, 'Joomla Author', 'Author');
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (4, 'Checkbox', 'Checkbox');
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (5, 'Single select list', 'Singleselectlist');
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (10, 'Multi select list (checkboxes)', 'Multiselectlist');
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (12, 'Date Picker', 'Datepicker');
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (15, 'Database Lookup', 'Databaselookup');
INSERT INTO `#__f2c_kml_feed_fieldtype` (`id`, `description`, `name`) VALUES (17, 'Database Lookup (Multi select)', 'Databaselookupmulti');