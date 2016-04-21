ALTER IGNORE TABLE `goStores_ServerInfo` ADD `whm_port` VARCHAR( 250 ) NULL , ADD `cpanel_port` VARCHAR( 250 ) NULL;
ALTER IGNORE TABLE `cms_users` ADD  `role_id` INT( 11 ) NULL AFTER  `password`;

DROP TABLE IF EXISTS `fw_metadata`;
CREATE TABLE IF NOT EXISTS `fw_metadata` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `goStores_DemoScreenshots`;
CREATE TABLE IF NOT EXISTS `goStores_DemoScreenshots` (
  `nScreenId` int(11) NOT NULL auto_increment,
  `vScreenImageId` int(11) NOT NULL,
  `vActive` enum('1','0') NOT NULL default '1',
  `eType` enum('Admin','User') NOT NULL default 'User',
  PRIMARY KEY  (`nScreenId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `goStores_DomainRenewalLog`;
CREATE TABLE `goStores_DomainRenewalLog` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`nPLId` BIGINT(20) NULL DEFAULT NULL,
	`nUId` BIGINT(20) NULL DEFAULT NULL,
	`vDomain` VARCHAR(250) NULL DEFAULT NULL,
	`status` INT(1) NULL DEFAULT NULL COMMENT '1=> Domain renewed, 0 => Domain Renewal Failed',
	`comments` VARCHAR(255) NULL DEFAULT NULL,
	`expireOn` DATE NULL,
	`createdOn` DATE NULL,
	`lastModified` DATE NULL,
	`cronAttempt` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `goStores_tld_godaddy`;
CREATE TABLE IF NOT EXISTS `goStores_tld_godaddy` (
  `nId` int(11) NOT NULL auto_increment,
  `nDuration` smallint(2) NOT NULL,
  `eType` enum('Registration','Renewal','Transfer') NOT NULL,
  `vProductid` varchar(50) NOT NULL,
  `vTld` varchar(10) NOT NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=latin1;


ALTER TABLE `goStores_Banners` CHANGE `clickcount` `clickcount` INT( 11 ) NULL DEFAULT NULL ;
ALTER IGNORE TABLE `goStores_InvoiceDomain` ADD  `nRate` DOUBLE( 10, 2 ) NULL AFTER `vDescription`;
ALTER TABLE `goStores_BillingMain` CHANGE `nServiceId` `nServiceId` INT( 11 ) NULL;
ALTER IGNORE TABLE `goStores_ProductLookup` ADD COLUMN `nTransactionSession` BIGINT NULL DEFAULT NULL AFTER `vAccountDetails`;
ALTER IGNORE TABLE `goStores_Banners` ADD COLUMN `displayOrder` INT(11) NULL DEFAULT NULL AFTER `eType`;
ALTER IGNORE TABLE `goStores_BillingMain` ADD COLUMN `vPaymentMethod` VARCHAR(200) NULL DEFAULT NULL AFTER `dDatePurchase`;
ALTER IGNORE TABLE `goStores_PaidTemplatePurchase` ADD COLUMN `paidOn` DATE NULL AFTER `comments`;
ALTER TABLE `goStores_PaidTemplatePurchase` CHANGE COLUMN `paidOn` `paidOn` TIMESTAMP NULL DEFAULT NULL AFTER `comments`;
ALTER IGNORE TABLE `goStores_PaidTemplatePurchase` ADD COLUMN `nPLId` BIGINT(20) NULL DEFAULT NULL AFTER `nUId`;
ALTER TABLE  `goStores_ProductLookup` CHANGE  `vSubDomain`  `vSubDomain` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE  `goStores_ProductLookup` CHANGE  `vDomain`  `vDomain` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE  `goStores_ProductLookup` CHANGE  `vAccountDetails`  `vAccountDetails` TEXT CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE `goStores_ServerInfo` CHANGE COLUMN `whmpass` `whmpass` TEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `whmuser`;
