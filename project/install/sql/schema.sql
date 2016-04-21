CREATE TABLE IF NOT EXISTS `cms_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL,
  `position` int(3) DEFAULT '0',
  `published` tinyint(1) DEFAULT '1',
  `user_privilege` varchar(256) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.cms_sections
CREATE TABLE IF NOT EXISTS `cms_sections` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `group_id` int(8) DEFAULT NULL,
  `section_name` varchar(200) DEFAULT NULL,
  `section_alias` varchar(200) DEFAULT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `section_config` text,
  `visibilty` enum('0','1') NOT NULL,
  `display_order` int(11) NOT NULL,
  `user_privilege` varchar(256) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.cms_users
CREATE TABLE IF NOT EXISTS `cms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('sadmin','admin') NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.dummy
CREATE TABLE IF NOT EXISTS `dummy` (
  `num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`num`),
  KEY `dnumindex` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.fw_metadata
CREATE TABLE IF NOT EXISTS `fw_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(100) NOT NULL DEFAULT '',
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_admin
CREATE TABLE IF NOT EXISTS `goStores_admin` (
  `nAId` int(11) NOT NULL AUTO_INCREMENT,
  `nRid` int(11) NOT NULL,
  `vUsername` varchar(250) DEFAULT NULL,
  `vPassword` varchar(250) DEFAULT NULL,
  `vFirstName` varchar(255) NOT NULL,
  `vLastName` varchar(255) NOT NULL,
  `dLastLogin` datetime NOT NULL,
  `dLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vEmail` varchar(255) NOT NULL,
  `nSuperAdmin` int(11) NOT NULL DEFAULT '0',
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY (`nAId`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_affiliatereftxns
CREATE TABLE IF NOT EXISTS `goStores_affiliatereftxns` (
  `nid` int(11) NOT NULL AUTO_INCREMENT,
  `n_aff_id` int(11) DEFAULT NULL,
  `n_ref_id` int(11) DEFAULT NULL,
  `nuser_id` int(11) DEFAULT NULL,
  `ddate` date DEFAULT NULL,
  `namount` float DEFAULT NULL,
  `vsettled_status` varchar(5) COLLATE latin1_general_ci DEFAULT '0',
  `vsettled_type` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `vsettled_ref_no` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `dsettled_date` datetime DEFAULT NULL,
  PRIMARY KEY (`nid`),
  KEY `n_aff_id` (`n_aff_id`),
  KEY `n_ref_id` (`n_ref_id`),
  KEY `nuser_id` (`nuser_id`),
  KEY `vsettled_status` (`vsettled_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_affiliates
CREATE TABLE IF NOT EXISTS `goStores_affiliates` (
  `naff_id` int(11) NOT NULL AUTO_INCREMENT,
  `vaff_name` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_login` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_pass` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_mail` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_address` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_city` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_state` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_country` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_zip` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `vaff_phone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `vdelstatus` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `ddate_reg` date DEFAULT '0000-00-00',
  PRIMARY KEY (`naff_id`),
  KEY `vdelstatus` (`vdelstatus`),
  KEY `vaff_login` (`vaff_login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Banners
CREATE TABLE IF NOT EXISTS `goStores_Banners` (
  `nBannerId` int(11) NOT NULL AUTO_INCREMENT,
  `vBannerText` varchar(255) NOT NULL,
  `vBannerUrl` varchar(255) NOT NULL,
  `vBannerImageId` int(11) NOT NULL,
  `vActive` enum('1','0') NOT NULL DEFAULT '1',
  `showcount` int(11) NOT NULL DEFAULT '0',
  `clickcount` int(11) DEFAULT NULL,
  `eType` enum('Home Page Sliding Banner','Header','Footer') NOT NULL DEFAULT 'Footer',
  `displayOrder` int(11) DEFAULT NULL,
  PRIMARY KEY (`nBannerId`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_BillingMain
CREATE TABLE IF NOT EXISTS `goStores_BillingMain` (
  `nBmId` bigint(20) NOT NULL AUTO_INCREMENT,
  `nUId` bigint(20) NOT NULL,
  `nServiceId` int(11) DEFAULT NULL,
  `vInvNo` int(11) NOT NULL,
  `nSCatId` int(11) NOT NULL,
  `vDomain` varchar(250) DEFAULT NULL,
  `nDiscount` double(10,2) DEFAULT NULL,
  `nAmount` double(10,2) DEFAULT NULL,
  `nSpecialCost` double(10,2) DEFAULT NULL,
  `vSpecials` text,
  `vType` varchar(250) DEFAULT NULL,
  `vBillingInterval` varchar(250) DEFAULT NULL,
  `nBillingDuration` int(11) DEFAULT NULL,
  `dDateStart` date DEFAULT NULL,
  `dDateStop` date DEFAULT NULL,
  `dDateNextBill` date DEFAULT NULL,
  `dDatePurchase` date DEFAULT NULL,
  `vPaymentMethod` varchar(200) DEFAULT NULL,
  `cronAttempt` int(1) DEFAULT NULL,
  `vDelStatus` int(1) DEFAULT NULL,
  `vExistDomainFlag` int(1) DEFAULT '0',
  PRIMARY KEY (`nBmId`)
) ENGINE=MyISAM AUTO_INCREMENT=537 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_billingsettlement
CREATE TABLE IF NOT EXISTS `goStores_billingsettlement` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nUId` int(11) NOT NULL,
  `nRequestedAmount` double NOT NULL,
  `tUserComments` text NOT NULL,
  `nSettledAmount` double NOT NULL,
  `tAdminComments` text NOT NULL,
  `dCreatedOn` date NOT NULL,
  `eStatus` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_billingsettlementhistory
CREATE TABLE IF NOT EXISTS `goStores_billingsettlementhistory` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nBillingSettlementId` int(11) NOT NULL,
  `nUId` int(11) NOT NULL,
  `nAmountWithdrawn` int(11) NOT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Cms
CREATE TABLE IF NOT EXISTS `goStores_Cms` (
  `cms_id` int(5) NOT NULL AUTO_INCREMENT,
  `cms_name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cms_ref_title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cms_type` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cms_title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cms_desc` text COLLATE latin1_general_ci NOT NULL,
  `cms_shortdesc` text COLLATE latin1_general_ci NOT NULL,
  `cms_status` smallint(1) NOT NULL,
  PRIMARY KEY (`cms_id`),
  KEY `test` (`cms_id`),
  FULLTEXT KEY `cms_title` (`cms_title`,`cms_desc`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_cms_settings
CREATE TABLE IF NOT EXISTS `goStores_cms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_set_name` varchar(100) NOT NULL,
  `cms_set_value` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_contacts
CREATE TABLE IF NOT EXISTS `goStores_contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cname` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `cemail` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `cdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cdescr` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_coupon
CREATE TABLE IF NOT EXISTS `goStores_coupon` (
  `nCouponId` bigint(20) NOT NULL AUTO_INCREMENT,
  `vCouponCode` varchar(255) DEFAULT NULL,
  `vCouponDescription` text,
  `vPricingMode` enum('percentage','rate') DEFAULT NULL COMMENT 'Percentage or Rate',
  `nCouponValue` double(10,2) DEFAULT NULL,
  `dCreatedOn` date DEFAULT NULL,
  `dExpireOn` date DEFAULT NULL,
  `nCouponCount` int(11) NOT NULL DEFAULT '0',
  `nCouponUsed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nCouponId`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_DemoScreenshots
CREATE TABLE IF NOT EXISTS `goStores_DemoScreenshots` (
  `nScreenId` int(11) NOT NULL AUTO_INCREMENT,
  `vScreenImageId` int(11) NOT NULL,
  `vActive` enum('1','0') NOT NULL DEFAULT '1',
  `eType` enum('Admin','User') NOT NULL DEFAULT 'User',
  PRIMARY KEY (`nScreenId`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_distributors
CREATE TABLE IF NOT EXISTS `goStores_distributors` (
  `nDistibutorId` int(11) NOT NULL AUTO_INCREMENT,
  `vDistributorName` varchar(50) NOT NULL DEFAULT '0',
  `vDistributorImage` int(11) NOT NULL DEFAULT '0',
  `vDistributorDesc` text,
  `vDistributorLink` varchar(50) NOT NULL DEFAULT '0',
  `vDistributorStatus` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`nDistibutorId`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_domainrenewallog
CREATE TABLE IF NOT EXISTS `goStores_domainrenewallog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nPLId` bigint(20) DEFAULT NULL,
  `nUId` bigint(20) DEFAULT NULL,
  `vDomain` varchar(250) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1=> Domain renewed, 0 => Domain Renewal Failed',
  `comments` varchar(255) DEFAULT NULL,
  `expireOn` date DEFAULT NULL,
  `createdOn` date DEFAULT NULL,
  `lastModified` date DEFAULT NULL,
  `cronAttempt` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_EmailTemplates
CREATE TABLE IF NOT EXISTS `goStores_EmailTemplates` (
  `nETId` bigint(20) NOT NULL AUTO_INCREMENT,
  `vemailTemplateName` varchar(255) NOT NULL,
  `temailTemplate` text NOT NULL,
  `estatus` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`nETId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_EmailTemplatesMails
CREATE TABLE IF NOT EXISTS `goStores_EmailTemplatesMails` (
  `nETMId` bigint(20) NOT NULL AUTO_INCREMENT,
  `vMailName` varchar(400) NOT NULL,
  `nETID` bigint(20) NOT NULL,
  `tScheduleTime` datetime NOT NULL,
  `eStatus` enum('Active','Deactive','Delete','Send') NOT NULL DEFAULT 'Active',
  `nMailMode` enum('Mail','Streamsend') NOT NULL DEFAULT 'Streamsend' COMMENT '1->Phpmail , 2-> Streamsend',
  PRIMARY KEY (`nETMId`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_files
CREATE TABLE IF NOT EXISTS `goStores_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_orig_name` varchar(255) DEFAULT NULL,
  `file_extension` varchar(10) DEFAULT NULL,
  `file_mime_type` varchar(50) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL COMMENT 'video/photo',
  `file_width` int(4) DEFAULT NULL,
  `file_height` int(4) DEFAULT NULL,
  `file_play_time` int(11) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_path` text,
  `file_status` int(11) DEFAULT NULL,
  `file_title` varchar(255) DEFAULT NULL,
  `file_caption` text,
  `file_tmp_name` varchar(255) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1022 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_general
CREATE TABLE IF NOT EXISTS `goStores_general` (
  `nGId` int(11) NOT NULL AUTO_INCREMENT,
  `nUserId` int(11) DEFAULT '0',
  `vFirstName` varchar(255) NOT NULL,
  `vLastName` varchar(255) NOT NULL,
  `vNumber` varchar(100) DEFAULT NULL,
  `vCode` varchar(100) DEFAULT NULL,
  `vMonth` varchar(100) DEFAULT NULL,
  `vYear` varchar(100) DEFAULT NULL,
  `vAddress` varchar(255) NOT NULL,
  `vCity` varchar(255) NOT NULL,
  `vState` varchar(255) NOT NULL,
  `vZipcode` varchar(100) NOT NULL,
  `vCountry` varchar(255) NOT NULL,
  `vEmail` varchar(255) NOT NULL,
  `vUserIp` varchar(15) DEFAULT NULL,
  `dUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nGId`)
) ENGINE=MyISAM AUTO_INCREMENT=326 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_help
CREATE TABLE IF NOT EXISTS `goStores_help` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `vTitle` varchar(250) NOT NULL,
  `tDescription` text NOT NULL,
  `eType` enum('Admin','User') NOT NULL DEFAULT 'User',
  `eStatus` enum('Active','Disabled') NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_inventorysource_plan
CREATE TABLE IF NOT EXISTS `goStores_inventorysource_plan` (
  `nInvsPid` int(11) NOT NULL AUTO_INCREMENT,
  `nPId` int(11) NOT NULL,
  `nUserId` int(11) NOT NULL,
  `dateStart` date NOT NULL,
  `dateEnd` date NOT NULL,
  `createdDate` datetime NOT NULL,
  `updatedDate` datetime NOT NULL,
  `cronAttempt` tinyint(4) DEFAULT '0',
  `lastCronDate` date NOT NULL,
  `nStatus` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`nInvsPid`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_inventorysource_plan_invoice
CREATE TABLE IF NOT EXISTS `goStores_inventorysource_plan_invoice` (
  `nInvsInvId` int(11) NOT NULL AUTO_INCREMENT,
  `nInvsPid` int(11) NOT NULL,
  `invDateStart` date NOT NULL,
  `invDateEnd` date NOT NULL,
  `InvCreatedDate` datetime NOT NULL,
  `nAmount` float NOT NULL,
  `nDuration` int(11) NOT NULL,
  `nTransactionId` varchar(250) NOT NULL,
  PRIMARY KEY (`nInvsInvId`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_inventorysync
CREATE TABLE IF NOT EXISTS `goStores_inventorysync` (
  `inv_sync_id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `b_email` varchar(255) NOT NULL,
  `b_password` varchar(255) NOT NULL,
  `b_current` varchar(255) NOT NULL,
  `b_selling_pdt_via` varchar(255) NOT NULL,
  `b_business_name` varchar(255) NOT NULL,
  `b_website_address` varchar(255) NOT NULL,
  `b_phonenumber` bigint(20) NOT NULL,
  `b_revenue` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`inv_sync_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Invoice
CREATE TABLE IF NOT EXISTS `goStores_Invoice` (
  `nInvId` int(11) NOT NULL AUTO_INCREMENT,
  `vInvNo` varchar(250) DEFAULT NULL,
  `nUId` int(11) NOT NULL,
  `nPLId` bigint(20) DEFAULT NULL,
  `dGeneratedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dDueDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nAmount` double NOT NULL,
  `nDiscount` double(10,2) DEFAULT NULL,
  `nTotal` double NOT NULL,
  `vCouponNumber` varchar(100) DEFAULT NULL,
  `vTerms` text NOT NULL,
  `vNotes` text NOT NULL,
  `vMethod` varchar(250) DEFAULT NULL,
  `vSubscriptionType` enum('FREE','PAID') NOT NULL,
  `upgraded` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 in the case where user changes from free to paid/lower plan to higher plan',
  `previousDomain` text COMMENT 'In case of upgrade, fill up previous domain here ',
  `vTxnId` varchar(250) DEFAULT NULL,
  `dPayment` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nInvId`)
) ENGINE=MyISAM AUTO_INCREMENT=669 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_InvoiceDomain
CREATE TABLE IF NOT EXISTS `goStores_InvoiceDomain` (
  `nIDId` int(11) NOT NULL AUTO_INCREMENT,
  `nUId` int(11) NOT NULL,
  `nInvId` int(11) NOT NULL,
  `nSCatId` int(11) DEFAULT NULL,
  `nPLId` bigint(20) DEFAULT NULL,
  `vDescription` text,
  `nRate` double(10,2) DEFAULT NULL,
  `nAmount` double NOT NULL,
  `nAmtNext` double(10,2) DEFAULT NULL,
  `vType` enum('one time','recurring') NOT NULL,
  `vBillingInterval` varchar(11) DEFAULT NULL,
  `nBillingDuration` int(11) DEFAULT NULL,
  `nDiscount` double NOT NULL,
  `dDateStart` date DEFAULT NULL,
  `dDateStop` date DEFAULT NULL,
  `dDateNextBill` date DEFAULT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nPlanStatus` int(11) DEFAULT '1',
  PRIMARY KEY (`nIDId`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_InvoicePlan
CREATE TABLE IF NOT EXISTS `goStores_InvoicePlan` (
  `nIPId` int(11) NOT NULL AUTO_INCREMENT,
  `nUId` int(11) NOT NULL,
  `nInvId` int(11) NOT NULL,
  `nServiceId` int(11) DEFAULT NULL,
  `nSCatId` int(11) DEFAULT NULL,
  `nSpecialCost` double(10,2) DEFAULT NULL,
  `vSpecials` text,
  `nAmount` double NOT NULL,
  `nAmtNext` double(10,2) DEFAULT NULL,
  `vType` enum('one time','recurring') NOT NULL,
  `vBillingInterval` varchar(11) DEFAULT NULL,
  `nBillingDuration` int(11) DEFAULT NULL,
  `nDiscount` double NOT NULL,
  `dDateStart` date DEFAULT NULL,
  `dDateStop` date DEFAULT NULL,
  `dDateNextBill` date DEFAULT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nPlanStatus` int(11) DEFAULT '1',
  PRIMARY KEY (`nIPId`)
) ENGINE=MyISAM AUTO_INCREMENT=666 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_module
CREATE TABLE IF NOT EXISTS `goStores_module` (
  `nMId` int(11) NOT NULL AUTO_INCREMENT,
  `vModuleName` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `dLastModifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY (`nMId`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_newsletters
CREATE TABLE IF NOT EXISTS `goStores_newsletters` (
  `nNLId` int(11) NOT NULL AUTO_INCREMENT,
  `vName` varchar(100) NOT NULL,
  `vEmail` varchar(255) NOT NULL,
  `dDateofSubscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dLastSent` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nSubscriptionStatus` int(11) NOT NULL,
  PRIMARY KEY (`nNLId`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_paidtemplatepurchase
CREATE TABLE IF NOT EXISTS `goStores_paidtemplatepurchase` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nTemplateId` bigint(20) DEFAULT NULL,
  `nUId` bigint(20) DEFAULT NULL,
  `nPLId` bigint(20) DEFAULT NULL,
  `amount` double(10,2) DEFAULT NULL,
  `paymentMethod` varchar(255) DEFAULT NULL,
  `transactionId` varchar(255) DEFAULT NULL,
  `comments` text NOT NULL,
  `paidOn` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_PaidTemplates
CREATE TABLE IF NOT EXISTS `goStores_PaidTemplates` (
  `nTemplateId` int(11) NOT NULL AUTO_INCREMENT,
  `vTemplateName` varchar(255) DEFAULT NULL,
  `vDescription` text,
  `nCost` double(10,2) DEFAULT NULL,
  `vTemplateZipId` int(11) DEFAULT NULL,
  `vHomeScreenshotId` int(11) DEFAULT NULL,
  `vInnerScreenshot1Id` int(11) DEFAULT NULL,
  `vInnerScreenshot2Id` int(11) DEFAULT NULL,
  `vActive` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`nTemplateId`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_partners
CREATE TABLE IF NOT EXISTS `goStores_partners` (
  `nPartnerId` int(11) NOT NULL AUTO_INCREMENT,
  `vPartnerName` varchar(50) NOT NULL DEFAULT '0',
  `vPartnerImage` int(11) NOT NULL DEFAULT '0',
  `vPartnerDesc` text,
  `vPartnerLink` varchar(50) NOT NULL DEFAULT '0',
  `vPartnerStatus` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`nPartnerId`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_payments
CREATE TABLE IF NOT EXISTS `goStores_payments` (
  `nPaymentId` int(11) NOT NULL AUTO_INCREMENT,
  `nPPId` int(11) NOT NULL,
  `nPId` int(11) NOT NULL,
  `nUId` int(11) NOT NULL,
  `nAmount` double NOT NULL,
  `vPlanDescription` text NOT NULL,
  `dPaymentDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vPaymentMethod` varchar(255) NOT NULL,
  `vTransactionId` varchar(50) NOT NULL,
  PRIMARY KEY (`nPaymentId`)
) ENGINE=MyISAM AUTO_INCREMENT=499 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_permission
CREATE TABLE IF NOT EXISTS `goStores_permission` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nRid` int(11) DEFAULT NULL,
  `vPermission` varchar(255) NOT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dLastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_planpackages
CREATE TABLE IF NOT EXISTS `goStores_planpackages` (
  `nPPId` int(11) NOT NULL AUTO_INCREMENT,
  `nPlanId` int(11) NOT NULL,
  `vDescription` text NOT NULL,
  `nPlanAmount` double NOT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nStatus` int(11) NOT NULL,
  `nDeleteStatus` int(1) DEFAULT '0',
  PRIMARY KEY (`nPPId`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_planpurchasecategories
CREATE TABLE IF NOT EXISTS `goStores_planpurchasecategories` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `vCategory` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `vOrderofDisplay` int(11) DEFAULT NULL,
  `dCreatedOn` date DEFAULT NULL,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_planpurchasecategorydetails
CREATE TABLE IF NOT EXISTS `goStores_planpurchasecategorydetails` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nPlanPurchaseCategoryId` int(11) NOT NULL,
  `vDescription` text NOT NULL,
  `nAmount` double NOT NULL,
  `nIsMandatory` int(11) NOT NULL,
  `dCreatedOn` date NOT NULL,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_plans
CREATE TABLE IF NOT EXISTS `goStores_plans` (
  `nPlanId` int(11) NOT NULL AUTO_INCREMENT,
  `vPlanName` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nStatus` int(11) NOT NULL,
  `nDeleteStatus` int(1) DEFAULT '0',
  PRIMARY KEY (`nPlanId`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_ProductLookup
CREATE TABLE IF NOT EXISTS `goStores_ProductLookup` (
  `nPLId` int(11) NOT NULL AUTO_INCREMENT,
  `nUId` int(11) NOT NULL,
  `nPPId` int(11) NOT NULL,
  `nPId` int(11) NOT NULL,
  `nPRId` int(11) NOT NULL,
  `vSubDomain` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `nSubDomainStatus` int(11) NOT NULL,
  `vDomain` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `nDomainStatus` int(11) NOT NULL,
  `dLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nCustomized` int(11) NOT NULL,
  `nStatus` int(11) NOT NULL,
  `dStatusUpdatedOn` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dPlanExpiryDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vAccountDetails` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `nTransactionSession` bigint(20) DEFAULT NULL,
  `bluedogdetails` text,
  `inventory_source_status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`nPLId`)
) ENGINE=MyISAM AUTO_INCREMENT=984 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_productpermission
CREATE TABLE IF NOT EXISTS `goStores_productpermission` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nPId` int(11) NOT NULL,
  `vPermissions` text NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_productreleases
CREATE TABLE IF NOT EXISTS `goStores_productreleases` (
  `nPRId` int(11) NOT NULL AUTO_INCREMENT,
  `nPId` int(11) NOT NULL,
  `vVersion` varchar(255) NOT NULL,
  `dLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nPRId`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Products
CREATE TABLE IF NOT EXISTS `goStores_Products` (
  `nPId` int(11) NOT NULL AUTO_INCREMENT,
  `vPName` varchar(255) NOT NULL,
  `vProductCaption` text,
  `vProductPack` varchar(250) DEFAULT NULL,
  `vProductlogoSmall` varchar(250) DEFAULT NULL,
  `vProductlogo` varchar(250) DEFAULT NULL,
  `vProductDescription` text,
  `vProductScreens` varchar(250) DEFAULT NULL,
  `dLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nPRId` int(5) DEFAULT NULL,
  `nStatus` int(1) NOT NULL,
  PRIMARY KEY (`nPId`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_productservicefeatures
CREATE TABLE IF NOT EXISTS `goStores_productservicefeatures` (
  `nProductServiceId` int(11) NOT NULL,
  `nServiceFeatureId` int(11) NOT NULL,
  `vFeatureValue` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_ProductServices
CREATE TABLE `goStores_ProductServices` (
	`nServiceId` INT(11) NOT NULL AUTO_INCREMENT,
	`vServiceName` VARCHAR(255) NOT NULL,
	`vServiceDescription` TEXT NOT NULL,
	`nSCatId` INT(11) NULL DEFAULT NULL,
	`nPId` INT(11) NULL DEFAULT NULL,
	`price` DOUBLE(10,2) NULL DEFAULT NULL,
	`nQty` INT(11) NOT NULL,
	`vType` ENUM('paid','free') NULL DEFAULT NULL,
	`vBillingInterval` VARCHAR(3) NULL DEFAULT NULL,
	`nBillingDuration` INT(11) NULL DEFAULT NULL,
	`nStatus` INT(11) NOT NULL,
	`dLastUpdated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`nServiceId`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
AUTO_INCREMENT=8
;


-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_role
CREATE TABLE IF NOT EXISTS `goStores_role` (
  `nRid` int(11) NOT NULL AUTO_INCREMENT,
  `vRoleName` varchar(255) NOT NULL,
  `nStatus` int(11) NOT NULL,
  `dCreatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nRid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_serverHistory
CREATE TABLE IF NOT EXISTS `goStores_serverHistory` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nPLId` bigint(20) DEFAULT NULL COMMENT 'product lookup id',
  `nserver_id` bigint(20) NOT NULL COMMENT 'server mapped with productlookup for a store',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=759 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_ServerInfo
CREATE TABLE IF NOT EXISTS `goStores_ServerInfo` (
  `nserver_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vserver_configfilename` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vserver_configfilepath` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `vserver_name` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `vmakethisserver_default` varchar(10) COLLATE latin1_general_ci DEFAULT 'NO',
  `vserver_type` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'CPANEL',
  `vserver_status` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'M',
  `vserver_hosting_plan` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `vserver_cpanel_skin` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `whmuser` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `whmpass` text CHARACTER SET utf8,
  `whmip` varchar(25) COLLATE latin1_general_ci DEFAULT NULL,
  `whm_port` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `cpanel_port` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`nserver_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_ServiceCategories
CREATE TABLE IF NOT EXISTS `goStores_ServiceCategories` (
  `nSCatId` int(11) NOT NULL AUTO_INCREMENT,
  `vCategory` varchar(255) NOT NULL,
  `vDescription` text NOT NULL,
  `vInputType` varchar(1) DEFAULT 'C',
  `vOrderofDisplay` int(11) DEFAULT NULL,
  `dCreatedOn` date DEFAULT NULL,
  `nStatus` int(11) NOT NULL,
  PRIMARY KEY (`nSCatId`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_ServiceFeatures
CREATE TABLE IF NOT EXISTS `goStores_ServiceFeatures` (
  `nFeatureId` int(11) NOT NULL AUTO_INCREMENT,
  `tFeatureName` varchar(250) NOT NULL,
  `tValue` text NOT NULL,
  `eStatus` enum('Active','Disabled') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`nFeatureId`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Settings
CREATE TABLE IF NOT EXISTS `goStores_Settings` (
  `settingfield` varchar(250) NOT NULL,
  `settinglabel` varchar(250) NOT NULL,
  `value` text,
  `groupLabel` varchar(50) NOT NULL,
  `type` varchar(256) DEFAULT NULL,
  `fieldOrder` int(11) DEFAULT '0',
  `helpText` text,
  KEY `settingfield` (`settingfield`),
  KEY `settingfield_2` (`settingfield`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_SiteAnalytics
CREATE TABLE IF NOT EXISTS `goStores_SiteAnalytics` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nCount` text,
  `dTrackingDate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_templates
CREATE TABLE IF NOT EXISTS `goStores_templates` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `vName` varchar(100) NOT NULL,
  `vDisplayName` varchar(100) NOT NULL,
  `tImagePath` text NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_themes
CREATE TABLE IF NOT EXISTS `goStores_themes` (
  `theme_id` int(5) NOT NULL AUTO_INCREMENT,
  `theme_title` varchar(100) NOT NULL,
  `theme_name` varchar(100) NOT NULL,
  `theme_status` int(1) NOT NULL COMMENT '1 -> active theme',
  `theme_thumbnail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_tld
CREATE TABLE IF NOT EXISTS `goStores_tld` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `registrar` varchar(50) NOT NULL DEFAULT '',
  `tld` varchar(25) NOT NULL DEFAULT '',
  `register_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `renewal_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transfer_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tocidreg` varchar(20) DEFAULT NULL,
  `tocidren` varchar(20) DEFAULT NULL,
  `tocidtra` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_tld_godaddy
CREATE TABLE IF NOT EXISTS `goStores_tld_godaddy` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nDuration` smallint(2) NOT NULL,
  `eType` enum('Registration','Renewal','Transfer') NOT NULL,
  `vProductid` varchar(50) NOT NULL,
  `vTld` varchar(10) NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_User
CREATE TABLE IF NOT EXISTS `goStores_User` (
  `nUId` int(11) NOT NULL AUTO_INCREMENT,
  `vUsername` varchar(250) DEFAULT NULL,
  `vPassword` varchar(250) DEFAULT NULL,
  `vFirstName` varchar(255) NOT NULL,
  `vLastName` varchar(255) NOT NULL,
  `dLastLogin` datetime NOT NULL,
  `dLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vEmail` varchar(255) NOT NULL,
  `vInvoiceEmail` varchar(255) NOT NULL,
  `vAddress` varchar(255) NOT NULL,
  `vCountry` varchar(255) NOT NULL,
  `vState` varchar(255) NOT NULL,
  `vCity` varchar(255) NOT NULL,
  `vZipcode` varchar(5) NOT NULL,
  `vPhoneNumber` varchar(25) NOT NULL,
  `vFax` varchar(25) NOT NULL,
  `vActivationKey` varchar(255) DEFAULT NULL,
  `nStatus` enum('0','1','2') NOT NULL COMMENT '0-> pending; 1-> active; 2 -> deactivated',
  `nAffId` int(11) NOT NULL,
  `vAffType` varchar(10) NOT NULL,
  `nRefId` int(11) NOT NULL,
  PRIMARY KEY (`nUId`)
) ENGINE=MyISAM AUTO_INCREMENT=403 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_wallet
CREATE TABLE IF NOT EXISTS `goStores_wallet` (
  `nWId` int(11) NOT NULL AUTO_INCREMENT,
  `nUId` int(11) NOT NULL,
  `nBalanceAmount` int(11) NOT NULL,
  `vType` enum('1','2') NOT NULL COMMENT '1-> Credit by Admin;2 -> Amount Settlement',
  `dCreatedOn` datetime NOT NULL,
  `dLastUpdated` datetime NOT NULL,
  PRIMARY KEY (`nWId`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_abandoned_carts
CREATE TABLE IF NOT EXISTS `goStores_Vista_abandoned_carts` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `inc_number` int(11) NOT NULL,
  `cart_uniq_id` varchar(50) NOT NULL,
  `cart_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_status` int(1) NOT NULL,
  `recovery_status` int(1) NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ship_fname` varchar(50) NOT NULL,
  `ship_lname` varchar(50) NOT NULL,
  `ship_address` text NOT NULL,
  `ship_city` varchar(50) NOT NULL,
  `ship_state` varchar(50) NOT NULL,
  `ship_country` varchar(50) NOT NULL,
  `ship_zip` varchar(50) NOT NULL,
  `ship_phone` varchar(50) NOT NULL,
  `ship_mobile` varchar(50) NOT NULL,
  `ship_fax` varchar(50) NOT NULL,
  `bill_fname` varchar(50) NOT NULL,
  `bill_lname` varchar(50) NOT NULL,
  `bill_address` text NOT NULL,
  `bill_city` varchar(50) NOT NULL,
  `bill_state` varchar(50) NOT NULL,
  `bill_country` varchar(50) NOT NULL,
  `bill_zip` varchar(50) NOT NULL,
  `bill_phone` varchar(50) NOT NULL,
  `bill_mobile` varchar(50) NOT NULL,
  `bill_email` varchar(250) NOT NULL,
  `bill_fax` varchar(50) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_method` varchar(100) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `uemail` varchar(50) NOT NULL,
  `utype` varchar(50) NOT NULL,
  `reg_status` varchar(10) NOT NULL,
  `isActive` int(1) NOT NULL,
  `payment_intent` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COMMENT='Abandoned Cart Details';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_abandoned_cart_details
CREATE TABLE IF NOT EXISTS `goStores_Vista_abandoned_cart_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aban_cart_id` int(11) NOT NULL,
  `sess_id` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL DEFAULT '0',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prodtype` varchar(20) NOT NULL,
  `extra_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(5) DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discounted_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `customvalue` text NOT NULL,
  `customfields_id` int(11) NOT NULL,
  `option_id` bigint(20) NOT NULL,
  `type` varchar(150) NOT NULL,
  `price_type` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COMMENT='Abandoned Cart - Cart Details';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_admins
CREATE TABLE IF NOT EXISTS `goStores_Vista_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) NOT NULL DEFAULT '',
  `admin_pword` varchar(100) NOT NULL DEFAULT '',
  `profile_img` varchar(250) NOT NULL,
  `settings` char(1) DEFAULT NULL,
  `custom` char(1) DEFAULT NULL,
  `category` char(1) DEFAULT NULL,
  `products` char(1) DEFAULT NULL,
  `feedback` char(1) DEFAULT NULL,
  `users` char(1) DEFAULT NULL,
  `orders` char(1) DEFAULT NULL,
  `refund` char(1) DEFAULT NULL,
  `promocodes` char(1) DEFAULT NULL,
  `websitecontent` char(1) DEFAULT NULL,
  `reports` char(1) DEFAULT NULL,
  `homepage` char(1) DEFAULT NULL,
  `help` char(1) DEFAULT NULL,
  `adminusers` char(1) DEFAULT NULL,
  `newsletter` char(1) NOT NULL DEFAULT '',
  `newsletterusers` char(1) NOT NULL DEFAULT '',
  `sentnewsletters` char(1) NOT NULL DEFAULT '',
  `giftcards` char(1) NOT NULL DEFAULT '',
  `banners` char(1) NOT NULL,
  `giftcardusers` char(1) NOT NULL DEFAULT '',
  `offers` char(1) NOT NULL,
  `customer_groups` char(1) NOT NULL,
  `tags` char(1) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `blog_pword` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_advertisements
CREATE TABLE IF NOT EXISTS `goStores_Vista_advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adfile` varchar(255) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_banners
CREATE TABLE IF NOT EXISTS `goStores_Vista_banners` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `bannerfile` varchar(250) NOT NULL,
  `template_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '1',
  `bannertitle` varchar(255) NOT NULL,
  `bannerdata` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_carts
CREATE TABLE IF NOT EXISTS `goStores_Vista_carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sess_id` varchar(100) NOT NULL DEFAULT '',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `variant_id` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prodtype` varchar(20) NOT NULL DEFAULT '',
  `extra_price` decimal(10,2) DEFAULT '0.00',
  `discount_type` varchar(5) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `shipping_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `customvalue` text,
  `customfields_id` int(11) DEFAULT '0',
  `option_id` bigint(20) NOT NULL DEFAULT '0',
  `type` varchar(150) NOT NULL DEFAULT '',
  `price_type` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_catalogs
CREATE TABLE IF NOT EXISTS `goStores_Vista_catalogs` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `catalog_invsrc_id` bigint(11) NOT NULL,
  `catalog_name` varchar(250) NOT NULL,
  `supplier_id` bigint(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_categories
CREATE TABLE IF NOT EXISTS `goStores_Vista_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) NOT NULL DEFAULT '',
  `seo_slug` varchar(250) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `custom_combination` varchar(255) NOT NULL DEFAULT '',
  `is_from_invsrc` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=290 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_categorymarkups
CREATE TABLE IF NOT EXISTS `goStores_Vista_categorymarkups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1-fixed,2-tired',
  `markup_percent` varchar(255) NOT NULL,
  `markup_flat_rate` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_cmspages
CREATE TABLE IF NOT EXISTS `goStores_Vista_cmspages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text,
  `type` varchar(100) NOT NULL DEFAULT 'email',
  `status` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_contacts
CREATE TABLE IF NOT EXISTS `goStores_Vista_contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cname` varchar(50) NOT NULL DEFAULT '',
  `cemail` varchar(200) NOT NULL DEFAULT '',
  `cdate` varchar(25) NOT NULL DEFAULT '',
  `cdescr` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_countries
CREATE TABLE IF NOT EXISTS `goStores_Vista_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(64) NOT NULL DEFAULT '',
  `country_iso_code_2` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`country_id`),
  KEY `IDX_COUNTRY_NAME` (`country_name`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_coupons
CREATE TABLE IF NOT EXISTS `goStores_Vista_coupons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL DEFAULT '',
  `type` enum('Perc','Amt') NOT NULL DEFAULT 'Perc',
  `giftvalue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `from_date` date NOT NULL DEFAULT '0000-00-00',
  `to_date` date NOT NULL DEFAULT '0000-00-00',
  `apply_type` enum('allorders','ordersover','category','product','productvariant','customer') NOT NULL,
  `apply_frequency` int(1) NOT NULL COMMENT '1- only once,2 - Every applicable item',
  `status` char(1) NOT NULL DEFAULT 'A',
  `descr` text,
  `minamt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `variant_id` int(11) NOT NULL DEFAULT '0',
  `customer_group_id` int(11) NOT NULL DEFAULT '0',
  `usage_limit` int(1) NOT NULL,
  `not_expire` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_coupon_orders
CREATE TABLE IF NOT EXISTS `goStores_Vista_coupon_orders` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `used_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_currencies
CREATE TABLE IF NOT EXISTS `goStores_Vista_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) DEFAULT NULL,
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_customcombinations
CREATE TABLE IF NOT EXISTS `goStores_Vista_customcombinations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) NOT NULL DEFAULT '0',
  `customfield_id` bigint(20) NOT NULL DEFAULT '0',
  `customvalue_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_customer_groups
CREATE TABLE IF NOT EXISTS `goStores_Vista_customer_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(250) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Customer groups';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_customer_group_details
CREATE TABLE IF NOT EXISTS `goStores_Vista_customer_group_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_group_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Customer group members';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_customfields
CREATE TABLE IF NOT EXISTS `goStores_Vista_customfields` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `custom_name` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `custom_id` (`custom_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_customfieldvalues
CREATE TABLE IF NOT EXISTS `goStores_Vista_customfieldvalues` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customfield_id` bigint(20) NOT NULL DEFAULT '0',
  `custom_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_feedbacks
CREATE TABLE IF NOT EXISTS `goStores_Vista_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `date` varchar(25) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_giftcards
CREATE TABLE IF NOT EXISTS `goStores_Vista_giftcards` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gift_code` varchar(255) NOT NULL DEFAULT '',
  `gift_amnt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `from` date DEFAULT '0000-00-00',
  `to` date DEFAULT '0000-00-00',
  `stock` bigint(20) NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'A',
  `descr` text,
  `gift_img` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`gift_code`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_giftcardusers
CREATE TABLE IF NOT EXISTS `goStores_Vista_giftcardusers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `card_id` bigint(20) NOT NULL DEFAULT '0',
  `usercard_id` bigint(20) NOT NULL DEFAULT '0',
  `date` varchar(20) NOT NULL DEFAULT '',
  `amnt_used` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_giftcard_details
CREATE TABLE IF NOT EXISTS `goStores_Vista_giftcard_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `card_id` bigint(20) NOT NULL DEFAULT '0',
  `card_code` text NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_helps
CREATE TABLE IF NOT EXISTS `goStores_Vista_helps` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `type` enum('A','U') NOT NULL DEFAULT 'A',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_homelayouts
CREATE TABLE IF NOT EXISTS `goStores_Vista_homelayouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_type` char(50) DEFAULT NULL,
  `section` char(15) DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_invsrc_products
CREATE TABLE IF NOT EXISTS `goStores_Vista_invsrc_products` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `invsrc_product_id` bigint(11) NOT NULL,
  `catalog_id` bigint(11) NOT NULL,
  `invsrc_product_name` text NOT NULL,
  `invsrc_upc` varchar(250) NOT NULL,
  `sync_date` datetime NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_newsletters
CREATE TABLE IF NOT EXISTS `goStores_Vista_newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `news_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_newsletterusers
CREATE TABLE IF NOT EXISTS `goStores_Vista_newsletterusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `sub_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('N','Y') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_offers
CREATE TABLE IF NOT EXISTS `goStores_Vista_offers` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_title` varchar(250) NOT NULL,
  `offer_details` longtext NOT NULL,
  `is_show_stock` int(1) NOT NULL,
  `not_allow_target` int(1) NOT NULL,
  `allow_product_qnty` int(1) NOT NULL,
  `offer_type` enum('email','popup') NOT NULL,
  `min_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `min_quantity` int(11) NOT NULL,
  `max_quantity` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `target_prod_count` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offer Details';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_offer_target_products
CREATE TABLE IF NOT EXISTS `goStores_Vista_offer_target_products` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` bigint(11) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offer Target Products';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_offer_target_products_temps
CREATE TABLE IF NOT EXISTS `goStores_Vista_offer_target_products_temps` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` varchar(250) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_offer_upsell_products
CREATE TABLE IF NOT EXISTS `goStores_Vista_offer_upsell_products` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` bigint(11) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_offer_upsell_products_temps
CREATE TABLE IF NOT EXISTS `goStores_Vista_offer_upsell_products_temps` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` varchar(250) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Upsell products temporary table';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_orders
CREATE TABLE IF NOT EXISTS `goStores_Vista_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exp_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `shipping_method` varchar(100) NOT NULL DEFAULT '',
  `shipping_service` varchar(100) NOT NULL DEFAULT '',
  `shipping_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_status_id` int(10) NOT NULL DEFAULT '1',
  `ship_fname` varchar(50) NOT NULL DEFAULT '',
  `ship_lname` varchar(50) NOT NULL DEFAULT '',
  `ship_address` varchar(50) NOT NULL DEFAULT '',
  `ship_city` varchar(50) NOT NULL DEFAULT '',
  `ship_state` varchar(50) NOT NULL DEFAULT '',
  `ship_country` varchar(50) NOT NULL DEFAULT '',
  `ship_zip` varchar(50) NOT NULL DEFAULT '',
  `ship_phone` varchar(50) NOT NULL DEFAULT '',
  `ship_mobile` varchar(50) NOT NULL DEFAULT '',
  `ship_fax` varchar(50) NOT NULL DEFAULT '',
  `transaction_id` varchar(50) DEFAULT NULL,
  `bill_fname` varchar(50) NOT NULL DEFAULT '',
  `bill_lname` varchar(50) NOT NULL DEFAULT '',
  `bill_address` varchar(50) NOT NULL DEFAULT '',
  `bill_city` varchar(50) NOT NULL DEFAULT '',
  `bill_state` varchar(50) NOT NULL DEFAULT '',
  `bill_country` varchar(50) NOT NULL DEFAULT '',
  `bill_zip` varchar(50) NOT NULL DEFAULT '',
  `bill_phone` varchar(50) NOT NULL DEFAULT '',
  `bill_mobile` varchar(50) NOT NULL DEFAULT '',
  `bill_fax` varchar(50) NOT NULL DEFAULT '',
  `payment_method` varchar(100) NOT NULL DEFAULT '',
  `payment_status` int(1) NOT NULL DEFAULT '0',
  `currency_type` varchar(10) NOT NULL DEFAULT 'USD',
  `note` text,
  `comments` longtext NOT NULL,
  `tax_name` varchar(100) DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `coupon_id` int(11) DEFAULT '0',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tracking_no` text,
  `tracking_service` varchar(500) NOT NULL,
  `label_pdf` text,
  `specials` text,
  `special_price` decimal(10,2) DEFAULT NULL,
  `order_type` varchar(2) DEFAULT NULL,
  `order_format` varchar(250) DEFAULT NULL,
  `cc_surcharge_fee` double DEFAULT NULL,
  `firearms_fee_amount` double DEFAULT NULL,
  `in_store_pickup_fee` double DEFAULT NULL,
  `firearms_in_store_pickup_fee` double DEFAULT NULL,
  `invsrc_order_id` bigint(11) DEFAULT NULL,
  `is_cancelled` int(1) DEFAULT NULL,
  `bd_transact_id` varchar(250) DEFAULT NULL,
  `bd_user_id` varchar(250) DEFAULT NULL,
  `fulfillment_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_id` (`user_id`,`order_status_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_order_details
CREATE TABLE IF NOT EXISTS `goStores_Vista_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `variant_id` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prodtype` varchar(20) DEFAULT NULL,
  `discount_type` varchar(5) DEFAULT 'amnt',
  `discount` decimal(10,2) DEFAULT '0.00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `discount_amount` double DEFAULT NULL,
  `extra_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `customfields_id` int(11) DEFAULT NULL,
  `combination_id` bigint(20) NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `price_type` char(1) DEFAULT NULL,
  `order_item_status_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orders_id` (`order_id`,`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_order_statuses
CREATE TABLE IF NOT EXISTS `goStores_Vista_order_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productcustomfields
CREATE TABLE IF NOT EXISTS `goStores_Vista_productcustomfields` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL DEFAULT '0',
  `customfield_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`customfield_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productdetails
CREATE TABLE IF NOT EXISTS `goStores_Vista_productdetails` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) NOT NULL DEFAULT '0',
  `product_id` bigint(20) NOT NULL DEFAULT '0',
  `customvalue_id` varchar(255) NOT NULL DEFAULT '',
  `stock` bigint(20) NOT NULL DEFAULT '0',
  `reorderlevel` bigint(20) NOT NULL DEFAULT '0',
  `skuno` varchar(100) NOT NULL DEFAULT '',
  `extraprice` bigint(20) NOT NULL DEFAULT '0',
  `pdctfile` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productimages
CREATE TABLE IF NOT EXISTS `goStores_Vista_productimages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(100) NOT NULL DEFAULT '0',
  `imagename` varchar(200) NOT NULL DEFAULT '',
  `default_img` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`imagename`)
) ENGINE=MyISAM AUTO_INCREMENT=1685 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productinventoryhistory
CREATE TABLE IF NOT EXISTS `goStores_Vista_productinventoryhistory` (
  `inv_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `variant_detail_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_old` int(11) NOT NULL,
  `stock_new` int(11) NOT NULL,
  `inv_count` int(11) NOT NULL,
  `hist_date` datetime NOT NULL,
  PRIMARY KEY (`inv_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Product Inventory Updated Details';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_products
CREATE TABLE IF NOT EXISTS `goStores_Vista_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(100) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `short_descr` longtext NOT NULL,
  `descr` longtext NOT NULL,
  `seo_slug` varchar(250) DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `wholesale_price` double NOT NULL DEFAULT '0',
  `req_tax` int(1) NOT NULL,
  `discount_type` varchar(5) NOT NULL DEFAULT '',
  `discount` double DEFAULT '0',
  `image1` varchar(100) DEFAULT NULL,
  `image2` varchar(100) DEFAULT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '1',
  `stock` int(11) DEFAULT '0',
  `skuno` varchar(100) DEFAULT NULL,
  `reorderlevel` int(11) DEFAULT '0',
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `req_shipping` int(1) NOT NULL,
  `shipping_price` double NOT NULL DEFAULT '0',
  `status` varchar(10) NOT NULL DEFAULT 'A',
  `publish_date` datetime NOT NULL,
  `pdctfile` varchar(250) NOT NULL DEFAULT '',
  `inv_sku` varchar(100) NOT NULL,
  `inv_barcode` varchar(100) NOT NULL,
  `inv_policy` int(1) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `prodtype` varchar(100) NOT NULL DEFAULT 'Non-Digital',
  `page_title` varchar(250) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `url_handle` varchar(250) NOT NULL,
  `combination` char(1) NOT NULL DEFAULT '',
  `inv_upc` varchar(100) DEFAULT NULL,
  `dimensional_weight` decimal(10,2) DEFAULT '0.00',
  `prod_condition` enum('new','used') DEFAULT NULL,
  `invsrc_id` bigint(11) DEFAULT NULL,
  `invsrc_parent` bigint(11) DEFAULT NULL,
  `invsrc_length` varchar(250) DEFAULT NULL,
  `invsrc_width` varchar(250) DEFAULT NULL,
  `invsrc_height` varchar(250) DEFAULT NULL,
  `invsrc_price` double DEFAULT NULL,
  `invsrc_cost` double DEFAULT NULL,
  `invsrc_dropship_fee` double DEFAULT NULL,
  `invsrc_mpn` varchar(250) DEFAULT NULL,
  `invsrc_asin` varchar(250) DEFAULT NULL,
  `invsrc_isbn` varchar(250) DEFAULT NULL,
  `invsrc_firearm` tinyint(4) DEFAULT '0',
  `invsrc_surcharge_fee` double DEFAULT NULL,
  `invsrc_amazon_allowed` enum('Y','N') DEFAULT NULL,
  `invsrc_walmart_allowed` enum('Y','N') DEFAULT NULL,
  `invsrc_ebay_allowed` enum('Y','N') DEFAULT NULL,
  `invsrc_jet_allowed` enum('Y','N') DEFAULT NULL,
  `invsrc_position` int(11) DEFAULT NULL,
  `invsrc_selling_unit` varchar(250) DEFAULT NULL,
  `invsrc_restricted` varchar(250) DEFAULT NULL,
  `invsrc_inserted_at` datetime DEFAULT NULL,
  `invsrc_updated_at` datetime DEFAULT NULL,
  `invsrc_deactivated_at` datetime DEFAULT NULL,
  `invsrc_reactivated_at` datetime DEFAULT NULL,
  `invsrc_map` double DEFAULT NULL,
  `catalog_id` bigint(11) DEFAULT NULL,
  `supplier_id` bigint(11) DEFAULT NULL,
  `is_from_invsource` int(1) DEFAULT NULL,
  `avg_rating` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=712 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_producttags
CREATE TABLE IF NOT EXISTS `goStores_Vista_producttags` (
  `prod_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT 'Product Id',
  `tag_id` int(11) NOT NULL COMMENT 'Tag Id',
  PRIMARY KEY (`prod_tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='Product tag reference table';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productvariantdeletedetails
CREATE TABLE IF NOT EXISTS `goStores_Vista_productvariantdeletedetails` (
  `variant_delete_id` int(11) NOT NULL AUTO_INCREMENT,
  `variant_value_id1` varchar(100) NOT NULL,
  `variant_value_id2` varchar(100) NOT NULL,
  `variant_value_id3` varchar(100) NOT NULL,
  `variant_name` varchar(250) NOT NULL,
  `product_id` int(11) NOT NULL,
  `wholesale_price` decimal(10,2) NOT NULL,
  `retail_price` decimal(10,2) NOT NULL,
  `inv_sku` varchar(100) NOT NULL,
  `inv_barcode` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `reorderlevel` int(11) NOT NULL DEFAULT '0',
  `delete_date` datetime NOT NULL,
  PRIMARY KEY (`variant_delete_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productvariantdetails
CREATE TABLE IF NOT EXISTS `goStores_Vista_productvariantdetails` (
  `variant_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `variant_id1` bigint(11) DEFAULT NULL,
  `variant_value_id1` varchar(100) NOT NULL,
  `variant_id2` bigint(11) DEFAULT NULL,
  `variant_value_id2` varchar(100) NOT NULL,
  `variant_id3` bigint(11) DEFAULT NULL,
  `variant_value_id3` varchar(100) NOT NULL,
  `variant_name` varchar(250) NOT NULL,
  `product_id` int(11) NOT NULL,
  `wholesale_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `retail_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `inv_sku` varchar(100) NOT NULL,
  `inv_upc` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `reorderlevel` int(11) NOT NULL,
  `prodtype` varchar(100) NOT NULL,
  `pdctfile` varchar(250) NOT NULL,
  `inv_barcode` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`variant_detail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=285 DEFAULT CHARSET=utf8 COMMENT='Product variant combinations';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productvariants
CREATE TABLE IF NOT EXISTS `goStores_Vista_productvariants` (
  `variant_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `variant_name` varchar(100) NOT NULL,
  PRIMARY KEY (`variant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COMMENT='Product Variants';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productvariantvaluedetails
CREATE TABLE IF NOT EXISTS `goStores_Vista_productvariantvaluedetails` (
  `variant_value_detail_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(11) NOT NULL,
  `variant_detail_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  `variant_value` varchar(500) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`variant_value_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=727 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_productvariantvalues
CREATE TABLE IF NOT EXISTS `goStores_Vista_productvariantvalues` (
  `variant_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`variant_value_id`)
) ENGINE=MyISAM AUTO_INCREMENT=190 DEFAULT CHARSET=utf8 COMMENT='Product Variant Values';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_product_attributes
CREATE TABLE IF NOT EXISTS `goStores_Vista_product_attributes` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(11) NOT NULL,
  `attr_name` varchar(250) NOT NULL,
  `attr_value` varchar(250) NOT NULL,
  `is_from_invsrc` int(1) NOT NULL DEFAULT '0',
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1520 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_randoms
CREATE TABLE IF NOT EXISTS `goStores_Vista_randoms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rand_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_ratings
CREATE TABLE IF NOT EXISTS `goStores_Vista_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_refundoptions
CREATE TABLE IF NOT EXISTS `goStores_Vista_refundoptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_refundproducts
CREATE TABLE IF NOT EXISTS `goStores_Vista_refundproducts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `refund_id` bigint(20) NOT NULL DEFAULT '0',
  `product_id` bigint(20) NOT NULL DEFAULT '0',
  `variant_id` bigint(20) NOT NULL DEFAULT '0',
  `quantity` bigint(20) DEFAULT NULL,
  `return_reason` text NOT NULL,
  `extra_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` int(11) DEFAULT '0',
  `discount_type` varchar(5) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `price_type` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_refundreasons
CREATE TABLE IF NOT EXISTS `goStores_Vista_refundreasons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_refunds
CREATE TABLE IF NOT EXISTS `goStores_Vista_refunds` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `customer_feedback` text,
  `status` varchar(255) DEFAULT NULL,
  `additional_info` text,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `refund_mode` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_refundstatuses
CREATE TABLE IF NOT EXISTS `goStores_Vista_refundstatuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_reports
CREATE TABLE IF NOT EXISTS `goStores_Vista_reports` (
  `id` int(11) NOT NULL DEFAULT '0',
  `reports` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_sales_channels
CREATE TABLE IF NOT EXISTS `goStores_Vista_sales_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_channel` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Sales channel master table';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_sent_newsletters
CREATE TABLE IF NOT EXISTS `goStores_Vista_sent_newsletters` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `newsletter_id` bigint(20) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_settings
CREATE TABLE IF NOT EXISTS `goStores_Vista_settings` (
  `fieldname` varchar(100) NOT NULL DEFAULT '',
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_shipping_rates
CREATE TABLE IF NOT EXISTS `goStores_Vista_shipping_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ship_rate_type` varchar(50) NOT NULL,
  `ship_rate_name` varchar(250) NOT NULL,
  `free_ship_type` varchar(50) NOT NULL,
  `min_value` decimal(10,2) NOT NULL,
  `max_value` decimal(10,2) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `weight_type` varchar(50) NOT NULL,
  `ship_rate` decimal(10,2) NOT NULL,
  `ship_other_rate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_statecodes
CREATE TABLE IF NOT EXISTS `goStores_Vista_statecodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `country_id` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_states
CREATE TABLE IF NOT EXISTS `goStores_Vista_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `country_id` varchar(10) NOT NULL DEFAULT '',
  `tax_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_suppliers
CREATE TABLE IF NOT EXISTS `goStores_Vista_suppliers` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(250) NOT NULL,
  `sku_prefix` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Product suppliers';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_tags
CREATE TABLE IF NOT EXISTS `goStores_Vista_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(250) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='Tags table';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_taxdetails
CREATE TABLE IF NOT EXISTS `goStores_Vista_taxdetails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `orderid` int(10) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_templates
CREATE TABLE IF NOT EXISTS `goStores_Vista_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `banner_resolution` varchar(255) NOT NULL,
  `ad_resolution` varchar(255) DEFAULT NULL,
  `ad_count` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_users
CREATE TABLE IF NOT EXISTS `goStores_Vista_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usertype` varchar(20) NOT NULL DEFAULT 'R',
  `reg_status` varchar(50) NOT NULL DEFAULT '',
  `user_credit` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_user_addresses
CREATE TABLE IF NOT EXISTS `goStores_Vista_user_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `addr_type` enum('Billing','Shipping') NOT NULL DEFAULT 'Billing',
  `fname` varchar(50) NOT NULL DEFAULT '',
  `lname` varchar(50) NOT NULL DEFAULT '',
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(20) NOT NULL DEFAULT '',
  `phone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `mobile` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=360 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_vendors
CREATE TABLE IF NOT EXISTS `goStores_Vista_vendors` (
  `vendor_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(250) NOT NULL,
  `vendor_code` varchar(250) DEFAULT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=631 DEFAULT CHARSET=utf8 COMMENT='Product Vendor Details';

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_visitors
CREATE TABLE IF NOT EXISTS `goStores_Vista_visitors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device` char(1) NOT NULL DEFAULT 'C',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table gostores_store.goStores_Vista_wishlists
CREATE TABLE IF NOT EXISTS `goStores_Vista_wishlists` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='User''s wishlist';
ALTER TABLE `goStores_User` ADD COLUMN `nShopperId` VARCHAR(250) NULL DEFAULT NULL AFTER `nRefId`;
ALTER TABLE `goStores_payments` ADD `webhookdata` TEXT NULL AFTER `vTransactionId`;
ALTER TABLE `goStores_Vista_abandoned_carts` ADD `payment_intent` VARCHAR(250) NULL AFTER `isActive`;