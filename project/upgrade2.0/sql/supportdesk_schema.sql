DROP TABLE IF EXISTS `sptbl_cannedmessages`;
CREATE TABLE IF NOT EXISTS `sptbl_cannedmessages` (
  `nMsgId` int(11) NOT NULL auto_increment,
  `dDate` date default '0000-00-00',
  `vTitle` varchar(100) default NULL,
  `vDescription` varchar(250) default NULL,
  `nStaffId` bigint(20) default NULL,
  `vStatus` char(1) default '0',
  PRIMARY KEY  (`nMsgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_chat`;
CREATE TABLE IF NOT EXISTS `sptbl_chat` (
  `nChatId` bigint(20) NOT NULL auto_increment,
  `dTimeStart` datetime default '0000-00-00 00:00:00',
  `nUserId` bigint(20) default '0',
  `vUserName` varchar(100) default NULL,
  `nStaffId` bigint(20) NOT NULL default '0',
  `tMatter` text,
  `dTimeEnd` datetime NOT NULL default '0000-00-00 00:00:00',
  `vStatus` varchar(10) default NULL,
  `vNewMsg` char(1) NOT NULL default '1',
  `nDeptId` bigint(20) default NULL,
  PRIMARY KEY  (`nChatId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_chattransfer`;
CREATE TABLE IF NOT EXISTS `sptbl_chattransfer` (
  `nTransferId` bigint(20) NOT NULL auto_increment,
  `nChatId` bigint(20) NOT NULL default '0',
  `nFirstStaff` bigint(20) NOT NULL default '0',
  `nSecondStaff` bigint(20) NOT NULL default '0',
  `vStatus` varchar(10) default NULL,
  PRIMARY KEY  (`nTransferId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_css`;
CREATE TABLE IF NOT EXISTS `sptbl_css` (
  `nCSSId` bigint(20) NOT NULL auto_increment,
  `vCSSName` varchar(50) default NULL,
  `vCSSURL` text NOT NULL,
  `dDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nCSSId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

DROP TABLE IF EXISTS `sptbl_desktop_share`;
CREATE TABLE IF NOT EXISTS `sptbl_desktop_share` (
  `nShareId` bigint(20) NOT NULL auto_increment,
  `nChatId` bigint(20) default NULL,
  `vClientIp` varchar(25) default NULL,
  `vStatus` varchar(15) default NULL,
  `Screenshot` text,
  PRIMARY KEY  (`nShareId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_escalationrules`;
CREATE TABLE IF NOT EXISTS `sptbl_escalationrules` (
  `nERId` int(11) NOT NULL auto_increment,
  `vRuleName` varchar(256) NOT NULL,
  `nCompId` int(11) NOT NULL,
  `nDeptId` int(11) NOT NULL,
  `eRespTimeSetting` enum('Y','N') NOT NULL default 'N',
  `eRespCountSetting` enum('Y','N') NOT NULL default 'N',
  `nResponseTime` int(11) NOT NULL,
  `nResponseCount` int(11) NOT NULL,
  `nStaffId` int(11) NOT NULL,
  `nStatus` int(11) NOT NULL COMMENT '0=>Active, 1=>Inactive',
  PRIMARY KEY  (`nERId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_follow_tickets`;
CREATE TABLE IF NOT EXISTS `sptbl_follow_tickets` (
  `nFollowId` int(11) NOT NULL auto_increment,
  `nTicketId` int(11) NOT NULL,
  `nStaffId` int(11) NOT NULL,
  `vStaffType` char(1) NOT NULL default 'U',
  PRIMARY KEY  (`nFollowId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_kb_rating`;
CREATE TABLE IF NOT EXISTS `sptbl_kb_rating` (
  `sKBRId` bigint(20) NOT NULL auto_increment,
  `nKBID` bigint(20) NOT NULL,
  `nUserId` bigint(20) NOT NULL,
  `nMarks` bigint(20) NOT NULL,
  PRIMARY KEY  (`sKBRId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_operatorchat`;
CREATE TABLE IF NOT EXISTS `sptbl_operatorchat` (
  `nChatId` bigint(20) NOT NULL auto_increment,
  `dTimeStart` datetime default '0000-00-00 00:00:00',
  `nFirstStaffId` bigint(20) default '0',
  `nSecondStaffId` bigint(20) NOT NULL default '0',
  `tMatter` text,
  `dTimeEnd` datetime NOT NULL default '0000-00-00 00:00:00',
  `vStatus` varchar(10) default NULL,
  `vNewMsg` char(1) NOT NULL default '1',
  `vChatSts` char(1) default NULL,
  PRIMARY KEY  (`nChatId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_templates`;
CREATE TABLE IF NOT EXISTS `sptbl_templates` (
  `nTemplateId` bigint(20) NOT NULL auto_increment,
  `dDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `vTemplateTitle` varchar(100) NOT NULL default '',
  `tTemplateDesc` text NOT NULL,
  `nStaffId` bigint(20) NOT NULL default '0',
  `vStatus` char(1) NOT NULL default '0',
  PRIMARY KEY  (`nTemplateId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_useremail`;
CREATE TABLE IF NOT EXISTS `sptbl_useremail` (
  `nUseremailId` int(11) NOT NULL auto_increment,
  `nUserId` bigint(20) NOT NULL,
  `vEmail` varchar(255) NOT NULL,
  `vStatus` char(1) NOT NULL default 'Y',
  PRIMARY KEY  (`nUseremailId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_visitors`;
CREATE TABLE IF NOT EXISTS `sptbl_visitors` (
  `nVisitingId` bigint(20) NOT NULL auto_increment,
  `nCompId` bigint(20) default NULL,
  `vIpAddr` varchar(25) default NULL,
  `vPage` varchar(128) default NULL,
  `vStatus` varchar(10) default NULL,
  `dVisitTime` datetime default NULL,
  `dLastUpdTime` datetime default NULL,
  PRIMARY KEY  (`nVisitingId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `sptbl_lookup`;
CREATE TABLE IF NOT EXISTS `sptbl_lookup` (
  `nLookUpId` bigint(20) NOT NULL auto_increment,
  `vLookUpName` varchar(100) NOT NULL default '',
  `vLookUpValue` text NOT NULL,
  PRIMARY KEY  (`nLookUpId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=128 ;

ALTER TABLE `sptbl_companies` CHANGE `nCompZip` `nCompZip` VARCHAR( 25 ) NULL DEFAULT NULL;
ALTER TABLE `sptbl_companies` ADD `vChatWelcomeMessage` VARCHAR( 128 ) NOT NULL DEFAULT 'Welcome';
ALTER TABLE `sptbl_companies` ADD `vChatIcon` CHAR( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `sptbl_companies` ADD `vChatOperatorRating` CHAR( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `sptbl_kb` CHANGE `vKBTitle` `vKBTitle` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';
ALTER TABLE `sptbl_kb` ADD `vMetaTage_keyword` TEXT NOT NULL;
ALTER TABLE `sptbl_kb` ADD `vMetaTage_desc` TEXT NOT NULL;
ALTER TABLE `sptbl_priorities` ADD `vPrioritie_icon` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `sptbl_replies` ADD `nHold` TINYINT( 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `sptbl_replies` ADD `nReplyStatus` VARCHAR( 50 ) NOT NULL;
ALTER TABLE `sptbl_staffratings` ADD `vType` CHAR( 1 ) NOT NULL DEFAULT 'T';
ALTER TABLE `sptbl_staffs` ADD `vStaffImg` VARCHAR( 128 ) NOT NULL;
ALTER TABLE `sptbl_tickets` ADD `vViewers` VARCHAR( 255 ) NOT NULL;