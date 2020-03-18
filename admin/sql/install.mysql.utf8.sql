CREATE TABLE IF NOT EXISTS `#__templateshop_presets` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`currency` CHAR(7) NOT NULL DEFAULT '',
	`filter` INT(10) NOT NULL DEFAULT 0,
	`itemlayout` TEXT NOT NULL,
	`keywords` VARCHAR(255) NOT NULL DEFAULT '',
	`lastadded` TINYINT(1) NOT NULL DEFAULT 0,
	`layout` TEXT NOT NULL,
	`templatetype` VARCHAR(100) NOT NULL,
	`sortby` int(11) NOT NULL,
	`orderdirection` INT(1) NOT NULL DEFAULT 0,
	`presetname` VARCHAR(100) NOT NULL DEFAULT '',
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_presetname` (`presetname`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__templateshop_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(255) NOT NULL,
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=152 ;



CREATE TABLE IF NOT EXISTS `#__templateshop_settings` (
  `option` varchar(50) NOT NULL,
  `value` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__templateshop_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `exclusiveprice` decimal(20,2) NOT NULL DEFAULT '0.00',
  `category` int(10) NOT NULL,
  `dateofaddition` date NOT NULL,
  `numberofdownloads` int(11) NOT NULL,
  `ishosting` tinyint(1) NOT NULL,
  `isflash` tinyint(1) NOT NULL,
  `isadult` tinyint(1) NOT NULL,
  `isuniquelogo` tinyint(1) NOT NULL,
  `isnonuniquelogo` tinyint(1) NOT NULL,
  `isuniquecorporate` tinyint(1) NOT NULL,
  `isnonuniquecorporate` tinyint(1) NOT NULL,
  `author` int(11) NOT NULL,
  `isfull` tinyint(1) NOT NULL,
  `numberofpages` smallint(6) NOT NULL,
  `screenshots` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `filter` int(10) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `sources` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `pages` varchar(255) NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68914 ;



--
-- Always insure this column rules is large enough for all the access control values.
--
ALTER TABLE `#__assets` CHANGE `rules` `rules` MEDIUMTEXT NOT NULL COMMENT 'JSON encoded access control.';

--
-- Always insure this column name is large enough for long component and view names.
--
ALTER TABLE `#__assets` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The unique name for the asset.';


