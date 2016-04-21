DROP TABLE IF EXISTS `cms_groups`;
CREATE TABLE IF NOT EXISTS `cms_groups` (
  `id` int(11) NOT NULL auto_increment,
  `group_name` varchar(50) default NULL,
  `position` int(3) default '0',
  `published` tinyint(1) default '1',
  `user_privilege` varchar(256) NOT NULL default 'all',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `dummy`;
CREATE TABLE IF NOT EXISTS `dummy` (
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`num`),
  KEY `dnumindex` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cms_sections`;
CREATE TABLE IF NOT EXISTS `cms_sections` (
  `id` int(8) NOT NULL auto_increment,
  `group_id` int(8) default NULL,
  `section_name` varchar(200) default NULL,
  `section_alias` varchar(200) default NULL,
  `table_name` varchar(100) default NULL,
  `section_config` text,
  `visibilty` enum('0','1') NOT NULL,
  `display_order` int(11) NOT NULL,
  `user_privilege` varchar(256) NOT NULL default 'all',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `cms_users`;
CREATE TABLE IF NOT EXISTS `cms_users` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('sadmin','admin') NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_Banners`;
CREATE TABLE IF NOT EXISTS `goStores_Banners` (
  `nBannerId` int(11) NOT NULL auto_increment,
  `vBannerText` varchar(255) NOT NULL,
  `vBannerUrl` varchar(255) NOT NULL,
  `vBannerImageId` int(11) NOT NULL,
  `vActive` enum('1','0') NOT NULL default '1',
  `showcount` int(11) NOT NULL default '0',
  `clickcount` int(11) NOT NULL default '0',
  PRIMARY KEY  (`nBannerId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_BillingMain`;
CREATE TABLE IF NOT EXISTS `goStores_BillingMain` (
  `nBmId` bigint(20) NOT NULL auto_increment,
  `nUId` bigint(20) NOT NULL,
  `nServiceId` int(11) NOT NULL,
  `vInvNo` int(11) NOT NULL,
  `nSCatId` int(11) NOT NULL,
  `vDomain` varchar(250) default NULL,
  `nDiscount` double(10,2) default NULL,
  `nAmount` double(10,2) default NULL,
  `nSpecialCost` double(10,2) default NULL,
  `vSpecials` text,
  `vType` varchar(250) default NULL,
  `vBillingInterval` varchar(250) default NULL,
  `nBillingDuration` int(11) default NULL,
  `dDateStart` date default NULL,
  `dDateStop` date default NULL,
  `dDateNextBill` date default NULL,
  `dDatePurchase` date default NULL,
  `cronAttempt` int(1) default NULL,
  `vDelStatus` int(1) default NULL,
  PRIMARY KEY  (`nBmId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_BillingSettlement`;
CREATE TABLE IF NOT EXISTS `goStores_BillingSettlement` (
  `nId` int(11) NOT NULL auto_increment,
  `nUId` int(11) NOT NULL,
  `nRequestedAmount` double NOT NULL,
  `tUserComments` text NOT NULL,
  `nSettledAmount` double NOT NULL,
  `tAdminComments` text NOT NULL,
  `dCreatedOn` date NOT NULL,
  `eStatus` enum('Pending','Approved','Rejected') NOT NULL default 'Pending',
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_BillingSettlementHistory`;
CREATE TABLE IF NOT EXISTS `goStores_BillingSettlementHistory` (
  `nId` int(11) NOT NULL auto_increment,
  `nBillingSettlementId` int(11) NOT NULL,
  `nUId` int(11) NOT NULL,
  `nAmountWithdrawn` int(11) NOT NULL,
  `dCreatedOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_Cms`;
CREATE TABLE IF NOT EXISTS `goStores_Cms` (
  `cms_id` int(5) NOT NULL auto_increment,
  `cms_name` varchar(255) collate latin1_general_ci NOT NULL,
  `cms_ref_title` varchar(255) collate latin1_general_ci NOT NULL,
  `cms_type` varchar(255) collate latin1_general_ci NOT NULL,
  `cms_title` varchar(255) collate latin1_general_ci NOT NULL,
  `cms_desc` text collate latin1_general_ci NOT NULL,
  `cms_shortdesc` text collate latin1_general_ci NOT NULL,
  `cms_status` smallint(1) NOT NULL,
  PRIMARY KEY  (`cms_id`),
  KEY `test` (`cms_id`),
  FULLTEXT KEY `cms_title` (`cms_title`,`cms_desc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=25 ;


DROP TABLE IF EXISTS `goStores_contacts`;
CREATE TABLE IF NOT EXISTS `goStores_contacts` (
  `id` bigint(20) NOT NULL auto_increment,
  `cname` varchar(50) collate latin1_general_ci NOT NULL default '',
  `cemail` varchar(200) collate latin1_general_ci NOT NULL default '',
  `cdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cdescr` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_Coupon`;
CREATE TABLE IF NOT EXISTS `goStores_Coupon` (
  `nCouponId` bigint(20) NOT NULL auto_increment,
  `vCouponCode` varchar(255) default NULL,
  `vCouponDescription` text,
  `vPricingMode` enum('percentage','rate') default NULL COMMENT 'Percentage or Rate',
  `nCouponValue` double(10,2) default NULL,
  `dCreatedOn` date default NULL,
  `dExpireOn` date default NULL,
  `nCouponCount` int(11) NOT NULL default '0',
  `nCouponUsed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`nCouponId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_EmailTemplates`;
CREATE TABLE IF NOT EXISTS `goStores_EmailTemplates` (
  `nETId` bigint(20) NOT NULL auto_increment,
  `vemailTemplateName` varchar(255) NOT NULL,
  `temailTemplate` text NOT NULL,
  `estatus` enum('Active','Inactive') NOT NULL default 'Active',
  PRIMARY KEY  (`nETId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_EmailTemplatesMails`;
CREATE TABLE IF NOT EXISTS `goStores_EmailTemplatesMails` (
  `nETMId` bigint(20) NOT NULL auto_increment,
  `vMailName` varchar(400) NOT NULL,
  `nETID` bigint(20) NOT NULL,
  `tScheduleTime` datetime NOT NULL,
  `eStatus` enum('Active','Deactive','Delete','Send') NOT NULL default 'Active',
  `nMailMode` enum('Mail','Streamsend') NOT NULL default 'Streamsend' COMMENT '1->Phpmail , 2-> Streamsend',
  PRIMARY KEY  (`nETMId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `tbl_files`;
CREATE TABLE IF NOT EXISTS `tbl_files` (
  `file_id` int(11) NOT NULL auto_increment,
  `file_orig_name` varchar(255) default NULL,
  `file_extension` varchar(10) default NULL,
  `file_mime_type` varchar(50) default NULL,
  `file_type` varchar(50) default NULL COMMENT 'video/photo',
  `file_width` int(4) default NULL,
  `file_height` int(4) default NULL,
  `file_play_time` int(11) default NULL,
  `file_size` int(11) default NULL,
  `file_path` text,
  `file_status` int(11) default NULL,
  `file_title` varchar(255) default NULL,
  `file_caption` text,
  `file_tmp_name` varchar(255) default NULL,
  `created_on` datetime default NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_general`;
CREATE TABLE IF NOT EXISTS `goStores_general` (
  `nGId` int(11) NOT NULL auto_increment,
  `nUserId` int(11) default '0',
  `vFirstName` varchar(255) NOT NULL,
  `vLastName` varchar(255) NOT NULL,
  `vNumber` varchar(100) default NULL,
  `vCode` varchar(100) default NULL,
  `vMonth` varchar(100) default NULL,
  `vYear` varchar(100) default NULL,
  `vAddress` varchar(255) NOT NULL,
  `vCity` varchar(255) NOT NULL,
  `vState` varchar(255) NOT NULL,
  `vZipcode` varchar(100) NOT NULL,
  `vCountry` varchar(255) NOT NULL,
  `vEmail` varchar(255) NOT NULL,
  `vUserIp` varchar(15) default NULL,
  `dUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nGId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_Help`;
CREATE TABLE IF NOT EXISTS `goStores_Help` (
  `nId` int(11) NOT NULL auto_increment,
  `vTitle` varchar(250) NOT NULL,
  `tDescription` text NOT NULL,
  `eType` enum('Admin','User') NOT NULL default 'User',
  `eStatus` enum('Active','Disabled') NOT NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;


DROP TABLE IF EXISTS `goStores_Invoice`;
CREATE TABLE IF NOT EXISTS `goStores_Invoice` (
  `nInvId` int(11) NOT NULL auto_increment,
  `vInvNo` varchar(250) default NULL,
  `nUId` int(11) NOT NULL,
  `nPLId` bigint(20) default NULL,
  `dGeneratedDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `dDueDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `nAmount` double NOT NULL,
  `nDiscount` int(11) NOT NULL,
  `nTotal` double NOT NULL,
  `vCouponNumber` varchar(100) default NULL,
  `vTerms` text NOT NULL,
  `vNotes` text NOT NULL,
  `vMethod` varchar(250) default NULL,
  `vSubscriptionType` enum('FREE','PAID') NOT NULL,
  `upgraded` tinyint(1) NOT NULL default '0' COMMENT '1 in the case where user changes from free to paid/lower plan to higher plan',
  `previousDomain` text COMMENT 'In case of upgrade, fill up previous domain here ',
  `vTxnId` varchar(250) default NULL,
  `dPayment` timestamp NULL default NULL,
  PRIMARY KEY  (`nInvId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_InvoiceDomain`;
CREATE TABLE IF NOT EXISTS `goStores_InvoiceDomain` (
  `nIDId` int(11) NOT NULL auto_increment,
  `nUId` int(11) NOT NULL,
  `nInvId` int(11) NOT NULL,
  `nSCatId` int(11) default NULL,
  `nPLId` bigint(20) default NULL,
  `vDescription` text,
  `nAmount` double NOT NULL,
  `nAmtNext` varchar(250) default NULL,
  `vType` enum('one time','recurring') NOT NULL,
  `vBillingInterval` varchar(11) default NULL,
  `nBillingDuration` int(11) default NULL,
  `nDiscount` double NOT NULL,
  `dDateStart` date default NULL,
  `dDateStop` date default NULL,
  `dDateNextBill` date default NULL,
  `dCreatedOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nPlanStatus` int(11) default '1',
  PRIMARY KEY  (`nIDId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_InvoicePlan`;
CREATE TABLE IF NOT EXISTS `goStores_InvoicePlan` (
  `nIPId` int(11) NOT NULL auto_increment,
  `nUId` int(11) NOT NULL,
  `nInvId` int(11) NOT NULL,
  `nServiceId` int(11) default NULL,
  `nSCatId` int(11) default NULL,
  `nSpecialCost` double(10,2) default NULL,
  `vSpecials` text,
  `nAmount` double NOT NULL,
  `nAmtNext` varchar(250) default NULL,
  `vType` enum('one time','recurring') NOT NULL,
  `vBillingInterval` varchar(11) default NULL,
  `nBillingDuration` int(11) default NULL,
  `nDiscount` double NOT NULL,
  `dDateStart` date default NULL,
  `dDateStop` date default NULL,
  `dDateNextBill` date default NULL,
  `dCreatedOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nPlanStatus` int(11) default '1',
  PRIMARY KEY  (`nIPId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_NewsLetters`;
CREATE TABLE IF NOT EXISTS `goStores_NewsLetters` (
  `nNLId` int(11) NOT NULL auto_increment,
  `vName` varchar(100) NOT NULL,
  `vEmail` varchar(255) NOT NULL,
  `dDateofSubscription` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `dLastSent` timestamp NOT NULL default '0000-00-00 00:00:00',
  `nSubscriptionStatus` int(11) NOT NULL,
  PRIMARY KEY  (`nNLId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_PaidTemplatePurchase`;
CREATE TABLE IF NOT EXISTS `goStores_PaidTemplatePurchase` (
  `id` bigint(20) NOT NULL auto_increment,
  `nTemplateId` bigint(20) default NULL,
  `nUId` bigint(20) default NULL,
  `amount` double(10,2) default NULL,
  `paymentMethod` varchar(255) default NULL,
  `transactionId` varchar(255) default NULL,
  `comments` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_PaidTemplates`;
CREATE TABLE IF NOT EXISTS `goStores_PaidTemplates` (
  `nTemplateId` int(11) NOT NULL auto_increment,
  `vTemplateName` varchar(255) NOT NULL,
  `vDescription` text,
  `nCost` double(10,2) default NULL,
  `vTemplateZipId` int(11) default NULL,
  `vHomeScreenshotId` int(11) default NULL,
  `vInnerScreenshot1Id` int(11) default NULL,
  `vInnerScreenshot2Id` int(11) default NULL,
  `vActive` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`nTemplateId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_Payments`;
CREATE TABLE IF NOT EXISTS `goStores_Payments` (
  `nPaymentId` int(11) NOT NULL auto_increment,
  `nPPId` int(11) NOT NULL,
  `nPId` int(11) NOT NULL,
  `nUId` int(11) NOT NULL,
  `nAmount` double NOT NULL,
  `vPlanDescription` text NOT NULL,
  `dPaymentDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `vPaymentMethod` varchar(255) NOT NULL,
  `vTransactionId` varchar(50) NOT NULL,
  PRIMARY KEY  (`nPaymentId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `goStores_Permission`;
CREATE TABLE IF NOT EXISTS `goStores_Permission` (
  `nId` int(11) NOT NULL auto_increment,
  `nRid` int(11) default NULL,
  `vPermission` varchar(255) NOT NULL,
  `dCreatedOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `dLastUpdated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_PlanPackages`;
CREATE TABLE IF NOT EXISTS `goStores_PlanPackages` (
  `nPPId` int(11) NOT NULL auto_increment,
  `nPlanId` int(11) NOT NULL,
  `vDescription` text NOT NULL,
  `nPlanAmount` double NOT NULL,
  `dCreatedOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nStatus` int(11) NOT NULL,
  `nDeleteStatus` int(1) default '0',
  PRIMARY KEY  (`nPPId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;



DROP TABLE IF EXISTS `goStores_PlanPurchaseCategories`;
CREATE TABLE IF NOT EXISTS `goStores_PlanPurchaseCategories` (
  `nId` int(11) NOT NULL auto_increment,
  `vCategory` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `vOrderofDisplay` int(11) default NULL,
  `dCreatedOn` date default NULL,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_PlanPurchaseCategoryDetails`;
CREATE TABLE IF NOT EXISTS `goStores_PlanPurchaseCategoryDetails` (
  `nId` int(11) NOT NULL auto_increment,
  `nPlanPurchaseCategoryId` int(11) NOT NULL,
  `vDescription` text NOT NULL,
  `nAmount` double NOT NULL,
  `nIsMandatory` int(11) NOT NULL,
  `dCreatedOn` date NOT NULL,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_Plans`;
CREATE TABLE IF NOT EXISTS `goStores_Plans` (
  `nPlanId` int(11) NOT NULL auto_increment,
  `vPlanName` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `dCreatedOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nStatus` int(11) NOT NULL,
  `nDeleteStatus` int(1) default '0',
  PRIMARY KEY  (`nPlanId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_ProductLookup`;
CREATE TABLE IF NOT EXISTS `goStores_ProductLookup` (
  `nPLId` int(11) NOT NULL auto_increment,
  `nUId` int(11) NOT NULL,
  `nPPId` int(11) NOT NULL,
  `nPId` int(11) NOT NULL,
  `nPRId` int(11) NOT NULL,
  `vSubDomain` varchar(255) NOT NULL,
  `nSubDomainStatus` int(11) NOT NULL,
  `vDomain` varchar(255) NOT NULL,
  `nDomainStatus` int(11) NOT NULL,
  `dLastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nCustomized` int(11) NOT NULL,
  `nStatus` int(11) NOT NULL,
  `dStatusUpdatedOn` timestamp NOT NULL default '0000-00-00 00:00:00',
  `dPlanExpiryDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `vAccountDetails` text NOT NULL,
  PRIMARY KEY  (`nPLId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_ProductPermission`;
CREATE TABLE IF NOT EXISTS `goStores_ProductPermission` (
  `nId` int(11) NOT NULL auto_increment,
  `nPId` int(11) NOT NULL,
  `vPermissions` text NOT NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;



DROP TABLE IF EXISTS `goStores_ProductReleases`;
CREATE TABLE IF NOT EXISTS `goStores_ProductReleases` (
  `nPRId` int(11) NOT NULL auto_increment,
  `nPId` int(11) NOT NULL,
  `vVersion` varchar(255) NOT NULL,
  `dLastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nPRId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_Products`;
CREATE TABLE IF NOT EXISTS `goStores_Products` (
  `nPId` int(11) NOT NULL auto_increment,
  `vPName` varchar(255) NOT NULL,
  `vProductCaption` text,
  `vProductPack` varchar(250) default NULL,
  `vProductlogoSmall` varchar(250) default NULL,
  `vProductlogo` varchar(250) default NULL,
  `vProductDescription` text,
  `vProductScreens` varchar(250) default NULL,
  `dLastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nPRId` int(5) default NULL,
  `nStatus` int(1) NOT NULL,
  PRIMARY KEY  (`nPId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;



DROP TABLE IF EXISTS `goStores_ProductServiceFeatures`;
CREATE TABLE IF NOT EXISTS `goStores_ProductServiceFeatures` (
  `nProductServiceId` int(11) NOT NULL,
  `nServiceFeatureId` int(11) NOT NULL,
  `vFeatureValue` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `goStores_ProductServices`;
CREATE TABLE IF NOT EXISTS `goStores_ProductServices` (
  `nServiceId` int(11) NOT NULL auto_increment,
  `vServiceName` varchar(255) NOT NULL,
  `vServiceDescription` text NOT NULL,
  `nSCatId` int(11) NOT NULL,
  `nPId` int(11) NOT NULL,
  `price` double(10,2) default NULL,
  `nQty` int(11) NOT NULL,
  `vBillingInterval` varchar(3) default NULL,
  `nBillingDuration` int(11) default NULL,
  `nStatus` int(11) NOT NULL,
  `dLastUpdated` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nServiceId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_serverHistory`;
CREATE TABLE IF NOT EXISTS `goStores_serverHistory` (
  `id` bigint(20) NOT NULL auto_increment,
  `nPLId` bigint(20) default NULL COMMENT 'product lookup id',
  `nserver_id` bigint(20) NOT NULL COMMENT 'server mapped with productlookup for a store',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_ServiceCategories`;
CREATE TABLE IF NOT EXISTS `goStores_ServiceCategories` (
  `nSCatId` int(11) NOT NULL auto_increment,
  `vCategory` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `vInputType` varchar(1) default 'C',
  `vOrderofDisplay` int(11) default NULL,
  `dCreatedOn` date default NULL,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY  (`nSCatId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;



DROP TABLE IF EXISTS `goStores_ServiceFeatures`;
CREATE TABLE IF NOT EXISTS `goStores_ServiceFeatures` (
  `nFeatureId` int(11) NOT NULL auto_increment,
  `tFeatureName` varchar(250) NOT NULL,
  `tValue` text NOT NULL,
  `eStatus` enum('Active','Disabled') NOT NULL default 'Active',
  PRIMARY KEY  (`nFeatureId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_Settings`;
CREATE TABLE IF NOT EXISTS `goStores_Settings` (
  `settingfield` varchar(250) NOT NULL,
  `settinglabel` varchar(250) NOT NULL,
  `value` text,
  `groupLabel` varchar(50) NOT NULL,
  `type` varchar(256) NOT NULL,
  `fieldOrder` int(11) NOT NULL,
  KEY `settingfield` (`settingfield`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `goStores_SiteAnalytics`;
CREATE TABLE IF NOT EXISTS `goStores_SiteAnalytics` (
  `nId` int(11) NOT NULL auto_increment,
  `nCount` text,
  `dTrackingDate` timestamp NULL default NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_Templates`;
CREATE TABLE IF NOT EXISTS `goStores_Templates` (
  `nId` int(11) NOT NULL auto_increment,
  `vName` varchar(100) NOT NULL,
  `vDisplayName` varchar(100) NOT NULL,
  `tImagePath` text NOT NULL,
  PRIMARY KEY  (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_themes`;
CREATE TABLE IF NOT EXISTS `goStores_themes` (
  `theme_id` int(5) NOT NULL auto_increment,
  `theme_title` varchar(100) NOT NULL,
  `theme_name` varchar(100) NOT NULL,
  `theme_status` int(1) NOT NULL COMMENT '1 -> active theme',
  `theme_thumbnail` varchar(255) default NULL,
  PRIMARY KEY  (`theme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;



DROP TABLE IF EXISTS `goStores_tld`;
CREATE TABLE IF NOT EXISTS `goStores_tld` (
  `id` bigint(20) NOT NULL auto_increment,
  `registrar` varchar(50) NOT NULL default '',
  `tld` varchar(25) NOT NULL default '',
  `register_fee` decimal(10,2) NOT NULL default '0.00',
  `renewal_fee` decimal(10,2) NOT NULL default '0.00',
  `transfer_fee` decimal(10,2) NOT NULL default '0.00',
  `tocidreg` varchar(20) default NULL,
  `tocidren` varchar(20) default NULL,
  `tocidtra` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;



DROP TABLE IF EXISTS `goStores_User`;
CREATE TABLE IF NOT EXISTS `goStores_User` (
  `nUId` int(11) NOT NULL auto_increment,
  `vUsername` varchar(250) default NULL,
  `vPassword` varchar(250) default NULL,
  `vFirstName` varchar(255) NOT NULL,
  `vLastName` varchar(255) NOT NULL,
  `dLastLogin` datetime NOT NULL,
  `dLastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `vEmail` varchar(255) NOT NULL,
  `vInvoiceEmail` varchar(255) NOT NULL,
  `vAddress` varchar(255) NOT NULL,
  `vCountry` varchar(255) NOT NULL,
  `vState` varchar(255) NOT NULL,
  `vCity` varchar(255) NOT NULL,
  `vZipcode` varchar(5) NOT NULL,
  `vPhoneNumber` varchar(25) NOT NULL,
  `vFax` varchar(25) NOT NULL,
  `vActivationKey` varchar(255) default NULL,
  `nStatus` enum('0','1','2') NOT NULL COMMENT '0-> pending; 1-> active; 2 -> deactivated',
  `nAffId` int(11) NOT NULL,
  `vAffType` varchar(10) NOT NULL,
  `nRefId` int(11) NOT NULL,
  PRIMARY KEY  (`nUId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `goStores_Wallet`;
CREATE TABLE IF NOT EXISTS `goStores_Wallet` (
  `nWId` int(11) NOT NULL auto_increment,
  `nUId` int(11) NOT NULL,
  `nBalanceAmount` int(11) NOT NULL,
  `vType` enum('1','2') NOT NULL COMMENT '1-> Credit by Admin;2 -> Amount Settlement',
  `dCreatedOn` datetime NOT NULL,
  `dLastUpdated` datetime NOT NULL,
  PRIMARY KEY  (`nWId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(100) NOT NULL default '',
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL default '0',
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `goStores_cms_settings`;
CREATE TABLE IF NOT EXISTS `goStores_cms_settings` (
  `id` int(11) NOT NULL auto_increment,
  `cms_set_name` varchar(100) NOT NULL,
  `cms_set_value` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

ALTER TABLE `goStores_ServerInfo` ADD `whmip` VARCHAR( 25 ) NULL;
ALTER TABLE `goStores_Settings` ADD INDEX ( `settingfield` );
ALTER TABLE `goStores_Banners` ADD `eType` ENUM( 'Header', 'Footer' ) NOT NULL DEFAULT 'Footer';
ALTER TABLE `goStores_ProductServices` ADD `vType` ENUM( 'paid', 'free' ) NULL AFTER `nQty`;
ALTER TABLE `goStores_Invoice` CHANGE `nDiscount` `nDiscount` DOUBLE( 10, 2 ) NULL;
ALTER TABLE `goStores_InvoicePlan` CHANGE `nAmtNext` `nAmtNext` DOUBLE( 10, 2 ) NULL DEFAULT NULL;
ALTER TABLE `goStores_InvoiceDomain` CHANGE `nAmtNext` `nAmtNext` DOUBLE( 10, 2 ) NULL DEFAULT NULL;
ALTER TABLE `goStores_Settings` ADD `helpText` TEXT NULL ;
ALTER TABLE `goStores_ServerInfo` ADD `whm_port` VARCHAR( 250 ) NULL , ADD `cpanel_port` VARCHAR( 250 ) NULL;
ALTER TABLE `cms_users` ADD  `role_id` INT( 11 ) NULL AFTER  `password`;

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
ALTER TABLE `goStores_InvoiceDomain` ADD  `nRate` DOUBLE( 10, 2 ) NULL AFTER `vDescription`;
ALTER TABLE `goStores_BillingMain` CHANGE `nServiceId` `nServiceId` INT( 11 ) NULL;
ALTER TABLE `goStores_ProductLookup` ADD COLUMN `nTransactionSession` BIGINT NULL DEFAULT NULL AFTER `vAccountDetails`;
ALTER TABLE `goStores_Banners` ADD COLUMN `displayOrder` INT(11) NULL DEFAULT NULL AFTER `eType`;
ALTER TABLE `goStores_BillingMain` ADD COLUMN `vPaymentMethod` VARCHAR(200) NULL DEFAULT NULL AFTER `dDatePurchase`;
ALTER TABLE `goStores_PaidTemplatePurchase` ADD COLUMN `paidOn` DATE NULL AFTER `comments`;
ALTER TABLE `goStores_PaidTemplatePurchase` CHANGE COLUMN `paidOn` `paidOn` TIMESTAMP NULL DEFAULT NULL AFTER `comments`;
ALTER TABLE `goStores_PaidTemplatePurchase` ADD COLUMN `nPLId` BIGINT(20) NULL DEFAULT NULL AFTER `nUId`;
ALTER TABLE  `goStores_ProductLookup` CHANGE  `vSubDomain`  `vSubDomain` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE  `goStores_ProductLookup` CHANGE  `vDomain`  `vDomain` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE  `goStores_ProductLookup` CHANGE  `vAccountDetails`  `vAccountDetails` TEXT CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;