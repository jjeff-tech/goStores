DROP TABLE IF EXISTS `dummy`;
DROP TABLE IF EXISTS `sptbl_actionlog`;
DROP TABLE IF EXISTS `sptbl_attachments`;
DROP TABLE IF EXISTS `sptbl_categories`;
DROP TABLE IF EXISTS `sptbl_companies`;
DROP TABLE IF EXISTS `sptbl_css`;
DROP TABLE IF EXISTS `sptbl_depts`;
DROP TABLE IF EXISTS `sptbl_downloads`;
DROP TABLE IF EXISTS `sptbl_feedback`;
DROP TABLE IF EXISTS `sptbl_fields`;
DROP TABLE IF EXISTS `sptbl_kb`;
DROP TABLE IF EXISTS `sptbl_lang`;
DROP TABLE IF EXISTS `sptbl_labels`;
DROP TABLE IF EXISTS `sptbl_lookup`;
DROP TABLE IF EXISTS `sptbl_news`;
DROP TABLE IF EXISTS `sptbl_personalnotes`;
DROP TABLE IF EXISTS `sptbl_pop3settings`;
DROP TABLE IF EXISTS `sptbl_priorities`;
DROP TABLE IF EXISTS `sptbl_pvtmessages`;
DROP TABLE IF EXISTS `sptbl_reminders`;
DROP TABLE IF EXISTS `sptbl_replies`;
DROP TABLE IF EXISTS `sptbl_rules`;
DROP TABLE IF EXISTS `sptbl_spam_categories`;
DROP TABLE IF EXISTS `sptbl_spam_references`;
DROP TABLE IF EXISTS `sptbl_spam_tickets`;
DROP TABLE IF EXISTS `sptbl_spam_wordfreqs`;
DROP TABLE IF EXISTS `sptbl_staffdept`;
DROP TABLE IF EXISTS `sptbl_stafffields`;
DROP TABLE IF EXISTS `sptbl_staffratings`;
DROP TABLE IF EXISTS `sptbl_staffs`;
DROP TABLE IF EXISTS `sptbl_temp_tickets`;
DROP TABLE IF EXISTS `sptbl_templates`;
DROP TABLE IF EXISTS `sptbl_tickets`;
DROP TABLE IF EXISTS `sptbl_users`;
DROP TABLE IF EXISTS `sptbl_xtrastatus`;
DROP TABLE IF EXISTS `sptbl_chat`;
DROP TABLE IF EXISTS `sptbl_operatorchat`;
DROP TABLE IF EXISTS `sptbl_cannedmessages`;
DROP TABLE IF EXISTS `sptbl_chattransfer`;
DROP TABLE IF EXISTS `sptbl_visitors`;

CREATE TABLE dummy(num int(11) NOT NULL default '0',PRIMARY KEY (num),KEY dnumindex (num)) ENGINE=MyISAM;

CREATE TABLE sptbl_actionlog(nALId bigint(20) NOT NULL auto_increment,nStaffId bigint(20) default '0',nUserId bigint(20) default '0',vAction varchar(100) NOT NULL default '',vArea varchar(100) NOT NULL default '',nRespId varchar(100) NOT NULL default '0',dDate datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY (nALId)) ENGINE=MyISAM;
CREATE TABLE sptbl_attachments(nAttachId bigint(20) NOT NULL auto_increment,nTicketId bigint(20) default NULL,nReplyId bigint(20) default NULL,vAttachReference varchar(100) NOT NULL default '',vAttachUrl text NOT NULL,PRIMARY KEY (nAttachId)) ENGINE=MyISAM;
CREATE TABLE sptbl_categories(nCatId bigint(20) NOT NULL auto_increment,nDeptId bigint(20) NOT NULL default '0',vCatDesc varchar(100) NOT NULL default '',nParentId bigint(20) NOT NULL default '0',vRoute varchar(100) NOT NULL default '',nCount bigint(20) NOT NULL default '0',PRIMARY KEY (nCatId)) ENGINE=MyISAM;
CREATE TABLE sptbl_companies(nCompId bigint(20) NOT NULL auto_increment,vCompName varchar(100) default NULL,vCompAddress1 varchar(100) default NULL,vCompAddress2 varchar(100) default NULL,vCompCity varchar(100) default NULL,vCompState varchar(100) default NULL,nCompZip varchar(25) default NULL,vCompCountry varchar(100) default NULL,vCompPhone varchar(20) default NULL,vCompFax varchar(20) default NULL,vCompMail varchar(100) default NULL,vCompContact varchar(100) NOT NULL default '',vDelStatus char(1) default '0',PRIMARY KEY  (nCompId)) ENGINE=MyISAM;
CREATE TABLE sptbl_css(nCSSId bigint(20) NOT NULL auto_increment,vCSSName varchar(50) default NULL,vCSSURL text NOT NULL,dDate datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY  (nCSSId)) ENGINE=MyISAM;
CREATE TABLE sptbl_depts(nDeptId bigint(20) NOT NULL auto_increment,nCompId bigint(20) NOT NULL default '0',vDeptDesc varchar(100) NOT NULL default '',nDeptParent bigint(20) NOT NULL default '0',vDeptMail varchar(100) default NULL,vDeptCode varchar(6) NOT NULL default '',nResponseTime int(11) default '0',PRIMARY KEY  (nDeptId)) ENGINE=MyISAM;
CREATE TABLE sptbl_downloads(nDLId bigint(20) NOT NULL auto_increment,vDescription varchar(100) NOT NULL default '',vURL text  NOT NULL,dPostdate datetime NOT NULL default '0000-00-00 00:00:00',vType char(1) NOT NULL default '2',PRIMARY KEY  (nDLId)) ENGINE=MyISAM;

CREATE TABLE sptbl_feedback(nFBId bigint(20) NOT NULL auto_increment,nTicketId bigint(20) NOT NULL default '0',vFBTitle varchar(100) NOT NULL default '',tFBDesc text NOT NULL,dDate datetime NOT NULL default '0000-00-00 00:00:00', PRIMARY KEY (nFBId), KEY fddateindex (dDate), KEY fdticketidindex (nTicketId)) ENGINE=MyISAM;

CREATE TABLE sptbl_fields(nFieldId bigint(20) NOT NULL auto_increment,vFieldName varchar(100) NOT NULL default '',vFieldDesc varchar(100) NOT NULL default '',PRIMARY KEY  (nFieldId)) ENGINE=MyISAM;
CREATE TABLE sptbl_kb(nKBID bigint(20) NOT NULL auto_increment,nCatId bigint(20) NOT NULL default '0',nStaffId bigint(20) NOT NULL default '0',vKBTitle varchar(250) NOT NULL default '',tKBDesc mediumtext NOT NULL,dDate datetime NOT NULL default '0000-00-00 00:00:00',vStatus char(1) NOT NULL default 'A',PRIMARY KEY  (nKBID),FULLTEXT KEY vKBTitle(vKBTitle,tKBDesc)) ENGINE=MyISAM;

CREATE TABLE sptbl_labels(nLabelId bigint(20) NOT NULL auto_increment,vLabelname varchar(100) default NULL,nStaffId bigint(20) default NULL,PRIMARY KEY  (nLabelId)) ENGINE=MyISAM;

CREATE TABLE sptbl_lang(vLangCode varchar(10) NOT NULL default '',VLangDesc varchar(50) NOT NULL default '',PRIMARY KEY  (vLangCode)) ENGINE=MyISAM;
CREATE TABLE sptbl_lookup(nLookUpId bigint(20) NOT NULL auto_increment,vLookUpName varchar(100) NOT NULL default '',vLookUpValue text NOT NULL default '',PRIMARY KEY  (nLookUpId)) ENGINE=MyISAM;
CREATE TABLE sptbl_news(nNewsId bigint(20) NOT NULL auto_increment,vTitle varchar(100) NOT NULL default '',tNews text NOT NULL,dPostdate datetime NOT NULL default '0000-00-00 00:00:00',dVaildDate datetime NOT NULL default '0000-00-00 00:00:00',vType char(1) NOT NULL default 'S',PRIMARY KEY  (nNewsId)) ENGINE=MyISAM;
CREATE TABLE sptbl_personalnotes(nPNId bigint(20) NOT NULL auto_increment,nStaffId bigint(20) NOT NULL default '0',nTicketId bigint(20) NOT NULL default '0',vStaffLogin varchar(100) default NULL,vPNTitle varchar(100) NOT NULL default '',tPNDesc text NOT NULL,dDate datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY  (nPNId)) ENGINE=MyISAM;

CREATE TABLE sptbl_pop3settings(nPop3Id bigint(20) NOT NULL auto_increment,nDeptId bigint(20) NOT NULL default '0',vDeptEmail varchar(100) NOT NULL default '',vServerName varchar(200) NOT NULL default '',vUserName varchar(100) NOT NULL default '',vPassword varchar(100) NOT NULL default '',nPortNo bigint(10) NOT NULL default '0', PRIMARY KEY  (nPop3Id)) ENGINE=MyISAM;

CREATE TABLE sptbl_priorities(nPriorityId bigint(20) NOT NULL auto_increment,nPriorityValue bigint(20) NOT NULL default '0',vPriorityDesc varchar(100) NOT NULL default '0',vTicketColor varchar(10) NOT NULL default '#FFFFFF',PRIMARY KEY  (nPriorityId)) ENGINE=MyISAM;
CREATE TABLE sptbl_pvtmessages(nPMId bigint(20) NOT NULL auto_increment,vPMTitle varchar(100) NOT NULL default '',tPMDesc text NOT NULL,nFrmStaffId bigint(20) NOT NULL default '0',nToStaffId bigint(20) NOT NULL default '0',dDate datetime NOT NULL default '0000-00-00 00:00:00',vStatus char(1) NOT NULL default 'o',PRIMARY KEY  (nPMId)) ENGINE=MyISAM;
CREATE TABLE sptbl_reminders(nRemId bigint(20) NOT NULL auto_increment,nStaffId bigint(20) default '0',vRemTitle varchar(100) NOT NULL default '',tRemDesc text NOT NULL,dRemAlert datetime NOT NULL default '0000-00-00 00:00:00',dRemPost datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY  (nRemId)) ENGINE=MyISAM;

CREATE TABLE sptbl_replies(nReplyId bigint(20) NOT NULL auto_increment,nTicketId bigint(20) NOT NULL default '0',nStaffId bigint(20) default NULL,nUserId bigint(20) default NULL,vStaffLogin varchar(100) default NULL,dDate datetime NOT NULL default '0000-00-00 00:00:00',tReply mediumtext NOT NULL,tPvtMessage text,vReplyTime bigint(20) NOT NULL default '0',vDelStatus char(1) NOT NULL default '0',vMachineIP varchar(20) default '0.0.0.0',PRIMARY KEY  (nReplyId), KEY ddateindex (dDate), KEY rnuserindex (nUserId), KEY rnstaffindex (nStaffId)) ENGINE=MyISAM;
CREATE TABLE sptbl_rules(nRuleId bigint(20) NOT NULL auto_increment,vRuleName varchar(100) NOT NULL default '',nSearchTitle tinyint(4) NOT NULL default '0', nSearchBody tinyint(4) NOT NULL default '0',vSearchWords text NOT NULL,nStaffId bigint(20) NOT NULL default '0',nDeptId bigint(20) NOT NULL default '0',dDateCreated date NOT NULL default '0000-00-00',PRIMARY KEY  (nRuleId)) ENGINE=MyISAM;

CREATE TABLE sptbl_spam_categories(category_id varchar(250) NOT NULL default '',probability double NOT NULL default '0',word_count bigint(20) NOT NULL default '0',PRIMARY KEY  (category_id)) ENGINE=MyISAM;
CREATE TABLE sptbl_spam_references(id varchar(250) NOT NULL default '',category_id varchar(250) NOT NULL default '', content text NOT NULL, PRIMARY KEY  (id),KEY category_id (category_id)) ENGINE=MyISAM;
CREATE TABLE sptbl_spam_tickets(nSpamTicketId bigint(20) NOT NULL auto_increment,vuseremail varchar(100) default NULL,nDeptId bigint(20) NOT NULL default '0', vTitle varchar(100) NOT NULL default '0', tQuestion mediumtext NOT NULL,dPostDate datetime NOT NULL default '0000-00-00 00:00:00',vMachineIP varchar(20) default '0.0.0.0',tcontent text,PRIMARY KEY  (nSpamTicketId)) ENGINE=MyISAM;
CREATE TABLE sptbl_spam_wordfreqs(word varchar(250) NOT NULL default '',category_id varchar(250) NOT NULL default '',count bigint(20) NOT NULL default '0',PRIMARY KEY  (word,category_id),KEY categoryindex (category_id)) ENGINE=MyISAM;

CREATE TABLE sptbl_staffdept(nStaffId bigint(20) NOT NULL default '0',nDeptId bigint(20) NOT NULL default '0') ENGINE=MyISAM;
CREATE TABLE sptbl_stafffields(nStaffId bigint(20) NOT NULL default '0',nFieldId bigint(20) NOT NULL default '0',PRIMARY KEY(nStaffId,nFieldId)) ENGINE=MyISAM;

CREATE TABLE sptbl_staffratings(nSRId bigint(20) NOT NULL auto_increment,nUserId bigint(20) NOT NULL default '0',nStaffId bigint(20) NOT NULL default '0',nTicketId bigint(20) default '0',tComments text NOT NULL,nMarks tinyint(4) NOT NULL default '0',PRIMARY KEY  (nSRId), KEY suseridindex (nUserId)) ENGINE=MyISAM;

CREATE TABLE sptbl_staffs(nStaffId bigint(20) NOT NULL auto_increment,vStaffname varchar(100) NOT NULL default '',vLogin varchar(100) NOT NULL default '',vPassword varchar(100) NOT NULL default '',vOnline char(1) NOT NULL default '0',vMail varchar(100) NOT NULL default '',vYIM varchar(100) default NULL,vSMSMail varchar(100) default NULL,vMobileNo varchar(20) default NULL,nCSSId tinyint(4) NOT NULL default '1',nRefreshRate tinyint(4) NOT NULL default '1',nNotifyAssign tinyint(4) NOT NULL default '0',nNotifyPvtMsg tinyint(4) NOT NULL default '0',nNotifyKB tinyint(4) NOT NULL default '0',nNotifyArrival tinyint(4)default '0',vType char(1) NOT NULL default 'U',vDelStatus char(1) NOT NULL default '0',tSignature tinytext,nWatcher tinyint(4) NOT NULL default '0',PRIMARY KEY  (nStaffId)) ENGINE=MyISAM;

REPLACE INTO sptbl_staffs VALUES (1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','0','admin@yoursite.com','adminsms@yoursite.com','adminaol@yoursite.com','23453453',1,1,1,1,0,1,'A','0','Thank You\r\nAdministrator\r\nSupportdesk\r\n',0);

CREATE TABLE sptbl_temp_tickets(nTpTicketId bigint(20) NOT NULL auto_increment,nTpUserId bigint(20) NOT NULL default '0',nTDeptId bigint(20) NOT NULL default '0',vTpTitle varchar(100) NOT NULL default '0',tTpQuestion mediumtext NOT NULL,vTpPriority bigint(20) NOT NULL default '0',dTpPostDate datetime NOT NULL default '0000-00-00 00:00:00',vAtt text,vStatus char(2) default NULL,PRIMARY KEY  (nTpTicketId)) ENGINE=MyISAM;
CREATE TABLE sptbl_templates(nTemplateId bigint(20) NOT NULL auto_increment,dDate datetime NOT NULL default '0000-00-00 00:00:00',vTemplateTitle varchar(100) NOT NULL default '',tTemplateDesc text NOT NULL,nStaffId bigint(20) NOT NULL default '0',vStatus char(1) NOT NULL default '0',PRIMARY KEY  (nTemplateId)) ENGINE=MyISAM;

CREATE TABLE sptbl_tickets(nTicketId bigint(20) NOT NULL auto_increment,nDeptId bigint(20) NOT NULL default '0',vRefNo varchar(50) NOT NULL default '',nUserId bigint(20) NOT NULL default '0',vUserName varchar(100) default NULL,vStaffLogin varchar(100) default NULL,vTitle varchar(100) NOT NULL default '0',tQuestion mediumtext NOT NULL,vPriority bigint(20) NOT NULL default '0',dPostDate datetime NOT NULL default '0000-00-00 00:00:00',vStatus varchar(50) default 'open',nOwner bigint(20) NOT NULL default '0',nLockStatus bigint(20) default '0',vDelStatus char(1) NOT NULL default '0',vMachineIP varchar(20) default '0.0.0.0',dLastAttempted datetime default NULL,nLabelId bigint(20) NOT NULL default '0',PRIMARY KEY  (nTicketId), KEY tdateindex (dLastAttempted),KEY tdelindex (vDelStatus),KEY tnuserindex (nUserId)) ENGINE=MyISAM;

CREATE TABLE sptbl_users(nUserId bigint(20) NOT NULL auto_increment,nCompId bigint(20) NOT NULL default '0',vUserName varchar(100) NOT NULL default '',vEmail varchar(100) NOT NULL default '',vLogin varchar(100) NOT NULL default '',vPassword varchar(100) NOT NULL default '',dDate datetime NOT NULL default '0000-00-00 00:00:00',vOnline char(1) NOT NULL default '0',vCodeForPass varchar(10) default NULL,vBanned char(1) NOT NULL default '0',vDelStatus char(1) NOT NULL default '0',nCSSId tinyint(4) NOT NULL default '1',PRIMARY KEY  (nUserId)) ENGINE=MyISAM;

CREATE TABLE sptbl_xtrastatus(nXtraId bigint(20) NOT NULL auto_increment,vXtraDesc varchar(100) NOT NULL default '',vXtraLang bigint(20) NOT NULL default '0',  PRIMARY KEY  (nXtraId)) ENGINE=MyISAM;

CREATE TABLE sptbl_chat(nChatId bigint(20) NOT NULL auto_increment, dTimeStart datetime default '0000-00-00 00:00:00',nUserId bigint(20) default '0', vUserName varchar(100),nStaffId bigint(20) NOT NULL default '0',tMatter text default '',dTimeEnd datetime NOT NULL default '0000-00-00 00:00:00',vStatus varchar(10), vNewMsg char(1)  NOT NULL default '1',  nDeptId bigint(20), PRIMARY KEY (nChatId))  ENGINE=MyISAM;

CREATE TABLE sptbl_operatorchat(nChatId bigint(20) NOT NULL auto_increment, dTimeStart datetime default '0000-00-00 00:00:00',nFirstStaffId bigint(20) default '0',nSecondStaffId bigint(20) NOT NULL default '0',tMatter text default '',dTimeEnd datetime NOT NULL default '0000-00-00 00:00:00',vStatus varchar(10), vNewMsg char(1)  NOT NULL default '1',  vChatSts char(1), PRIMARY KEY (nChatId)) ENGINE=MyISAM;

CREATE TABLE sptbl_cannedmessages(nMsgId int(11) NOT NULL auto_increment, dDate date default '0000-00-00', vTitle varchar(100), vDescription varchar(250), nStaffId bigint(20), vStatus char(1) default '0', PRIMARY KEY (nMsgId)) ENGINE=MyISAM;

CREATE TABLE sptbl_chattransfer(nTransferId bigint(20) NOT NULL auto_increment, nChatId bigint(20) NOT NULL default '0', nFirstStaff bigint(20) NOT NULL default '0', nSecondStaff bigint(20) NOT NULL default '0', vStatus varchar(10), PRIMARY KEY (nTransferId)) ENGINE=MyISAM;

CREATE TABLE sptbl_visitors (nVisitingId BIGINT( 20 ) NOT NULL auto_increment, nCompId BIGINT( 20 ) , vIpAddr  VARCHAR( 25 ) , vPage  VARCHAR( 128 ) , vStatus  VARCHAR( 10 ), dVisitTime  datetime, dLastUpdTime  datetime ,  PRIMARY KEY (nVisitingId)) ENGINE = MYISAM;

CREATE TABLE sptbl_desktop_share (nShareId  BIGINT( 20 ) NOT NULL auto_increment, nChatId BIGINT( 20 ), vClientIp varchar(25), vStatus  varchar(15),  PRIMARY KEY (nShareId)) ENGINE = MYISAM;


Replace INTO dummy VALUES (0);
Replace INTO dummy VALUES (1);
Replace INTO dummy VALUES (2);
Replace INTO dummy VALUES (3);
Replace INTO sptbl_fields VALUES (1,'vRefNo','TEXT_REF_NO');
Replace INTO sptbl_fields VALUES (2,'vTitle','TEXT_TITLE');
Replace INTO sptbl_fields VALUES (3,'dPostDate','TEXT_POST_DATE');
Replace INTO sptbl_fields VALUES (4,'vStatus','TEXT_STATUS');
Replace INTO sptbl_fields VALUES (5,'vUserName','TEXT_USER_NAME');
Replace INTO sptbl_fields VALUES (6,'vPriority','TEXT_PRIORITY');
Replace INTO sptbl_fields VALUES (7,'vStaffLogin','TEXT_STAFF');
Replace INTO sptbl_fields VALUES (8,'nLockStatus','TEXT_LOCK_STATUS');
Replace INTO sptbl_lang VALUES ('en','English');
Replace INTO sptbl_lookup VALUES (1,'MailAdmin','adminmail@yoursite.com');
Replace INTO sptbl_lookup VALUES (2,'MailTechnical','techmail@yoursite.com');
Replace INTO sptbl_lookup VALUES (3,'MailEscalation','escalationmail@yoursite.com');
Replace INTO sptbl_lookup VALUES (4,'MailFromName','SD');
Replace INTO sptbl_lookup VALUES (5,'MailFromMail','SD@yoursite.com');
Replace INTO sptbl_lookup VALUES (6,'MailReplyName','SD-Reply');
Replace INTO sptbl_lookup VALUES (7,'MailReplyMail','reply@yoursite.com');
Replace INTO sptbl_lookup VALUES (8,'SiteURL','www.supportpro.net');
Replace INTO sptbl_lookup VALUES (9,'HelpDeskURL','supportdesk.com/helpdesk');
Replace INTO sptbl_lookup VALUES (10,'LangChoice','0');
Replace INTO sptbl_lookup VALUES (11,'DefaultLang','en');
Replace INTO sptbl_lookup VALUES (12,'Post2PostGap','0');
Replace INTO sptbl_lookup VALUES (13,'AutoLock','0');
Replace INTO sptbl_lookup VALUES (14,'VerifyTemplate','0');
Replace INTO sptbl_lookup VALUES (15,'VerifyKB','0');
Replace INTO sptbl_lookup VALUES (16,'EmailURL','supportdesk.com/helpdesk');
Replace INTO sptbl_lookup VALUES (37,'MaxfileSize','2097150');
Replace INTO sptbl_lookup VALUES (38,'HelpdeskTitle','SupportPRO.net');
Replace INTO sptbl_lookup VALUES (39,'Logourl','images/logo.png');
Replace INTO sptbl_lookup VALUES (40,'Emailfooter','<table  width=\'100%\'><tr><td bgcolor=\'red\'>&nbsp;</td></tr></table>');
Replace INTO sptbl_lookup VALUES (41,'Emailheader','<table width=\'100%\'><tr><td bgcolor=\'green\'>&nbsp; </td></tr></table>');
Replace INTO sptbl_lookup VALUES (42,'vLicenceKey','128COL230100004000001250709TSO');

Replace INTO sptbl_lookup VALUES (87,'ExtraStatus','InProcess');
Replace INTO sptbl_lookup VALUES (90,'ExtraStatus','New');

Replace INTO sptbl_lookup VALUES (88,'Attachments','bmp|image/bmp');
Replace INTO sptbl_lookup VALUES (89,'Attachments','jpg|image/jpeg');
Replace INTO sptbl_lookup VALUES (96,'Attachments','mid|audio/mid');
Replace INTO sptbl_lookup VALUES (97,'Attachments','mp3|audio/mp3');
Replace INTO sptbl_lookup VALUES (98,'Attachments','wma|audio/x-ms-wma');

Replace INTO sptbl_lookup VALUES (92,'Attachments','gif|image/gif');
Replace INTO sptbl_lookup VALUES (93,'RunLevel','0');
Replace INTO sptbl_lookup VALUES (94,'logactivity','0');
Replace INTO sptbl_lookup VALUES (95,'LoginURL','');

Replace INTO sptbl_lookup VALUES (99,'EmailPiping','1');
Replace INTO sptbl_lookup VALUES (100,'MaxPostsPerPage','30');
Replace INTO sptbl_lookup VALUES (101,'OldestMessageFirst','1');

Replace INTO sptbl_lookup VALUES (102,'UserAuthenticate', '1');
Replace INTO sptbl_lookup VALUES (103,'PostTicketBeforeLogin', '1');
Replace INTO sptbl_lookup VALUES (104,'spamfiltertype', 'OFF');
Replace INTO sptbl_lookup VALUES (105,'MessageRule', '0');
Replace INTO sptbl_lookup VALUES (106, 'SMTPSettings', '0');
Replace INTO sptbl_lookup VALUES (107, 'SMTPServer', '');
Replace INTO sptbl_lookup VALUES (108, 'SMTPPort', '');

Replace INTO sptbl_lookup VALUES (109,'Attachments','txt|text/plain');
Replace INTO sptbl_lookup VALUES (110,'Attachments','zip|application/x-zip');
Replace INTO sptbl_lookup VALUES (111,'Attachments','html|text/html');
Replace INTO sptbl_lookup VALUES (112,'Attachments','htm|text/html');
Replace INTO sptbl_lookup VALUES (113,'Attachments','doc|application/msword');
Replace INTO sptbl_lookup VALUES (114,'Attachments','crt|application/x-x509-ca-cert');
Replace INTO sptbl_lookup VALUES (115,'Attachments','key|key|application/octet-stream');
Replace INTO sptbl_lookup VALUES (116,'Attachments','csv|text/plain');
Replace INTO sptbl_lookup VALUES (117,'Attachments','csv|application/octet-stream');
Replace INTO sptbl_lookup VALUES (118,'LiveChat', '1');
Replace INTO `sptbl_lookup` VALUES (119, 'AD_AUTHENTICATION', 'N');
Replace INTO `sptbl_lookup` VALUES (120, 'AD_USER', '');
Replace INTO `sptbl_lookup` VALUES (121, 'AD_PASS', '');
Replace INTO `sptbl_lookup` VALUES (122, 'AD_DOMAIN', '');
Replace INTO `sptbl_lookup` VALUES (123, 'AD_HOST', '');
Replace INTO `sptbl_lookup` VALUES (124, 'AD_USER_DIR', 'CN=USER');
Replace INTO sptbl_priorities VALUES (1,3,'High','#FFFFFF');
Replace INTO sptbl_priorities VALUES (2,2,'Medium','#FFFFFF');
Replace INTO sptbl_priorities VALUES (8,1,'Normal','#00FF00');
Replace INTO sptbl_priorities VALUES (3,4,'Urgent','#56dfdf');
Replace INTO sptbl_priorities VALUES (9,0,'Low','#e8dc54');

Replace INTO sptbl_spam_categories VALUES ('spam', 0.887777777778, 799);
Replace INTO sptbl_spam_categories VALUES ('notspam', 0.112222222222, 101);

Replace INTO sptbl_stafffields VALUES (1,1);
Replace INTO sptbl_stafffields VALUES (1,2);
Replace INTO sptbl_stafffields VALUES (1,3);
Replace INTO sptbl_stafffields VALUES (1,4);

ALTER TABLE `sptbl_staffs` ADD `vStaffImg`  VARCHAR( 128 );

ALTER TABLE `sptbl_companies` ADD `vChatWelcomeMessage`  VARCHAR( 128 ) default 'Welcome';
ALTER TABLE `sptbl_companies` ADD `vChatIcon`  CHAR( 1 ) default '1' ;
ALTER TABLE `sptbl_companies` ADD `vChatOperatorRating`  CHAR( 1 ) default '0';

ALTER TABLE `sptbl_staffratings`  ADD `vType`  CHAR( 1 ) default 'T';
ALTER TABLE `sptbl_kb` ADD `vMetaTage` TEXT NOT NULL;
ALTER TABLE `sptbl_kb` CHANGE `vMetaTage` `vMetaTage_keyword` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `sptbl_kb` ADD `vMetaTage_desc` TEXT NOT NULL;
ALTER TABLE `sptbl_priorities` ADD `prioritie_icon` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `sptbl_priorities` CHANGE `prioritie_icon` `vPrioritie_icon` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `sptbl_users` CHANGE `nCSSId` `nCSSId` TINYINT( 4 ) NOT NULL DEFAULT '3';
CREATE TABLE `sptbl_escalationrules` (`nERId` INT NOT NULL AUTO_INCREMENT, `vRuleName` VARCHAR(256) NOT NULL, `nCompId` INT NOT NULL, `nDeptId` INT NOT NULL, `eRespTimeSetting` ENUM('Y','N') NOT NULL DEFAULT 'N', `eRespCountSetting` ENUM('Y','N') NOT NULL DEFAULT 'N', `nResponseTime` INT NOT NULL, `nResponseCount` INT NOT NULL, `nStaffId` INT NOT NULL, PRIMARY KEY (`nERId`)) ENGINE = MyISAM;
ALTER TABLE `sptbl_escalationrules`  ADD `nStatus` INT NOT NULL COMMENT '0=>Active, 1=>Inactive';
CREATE TABLE `sptbl_follow_tickets` (`nFollowId` INT NOT NULL AUTO_INCREMENT ,`nTicketId` INT NOT NULL ,`nStaffId` INT NOT NULL ,`vStaffType` CHAR( 1 ) NOT NULL DEFAULT 'U',PRIMARY KEY ( `nFollowId` )) ENGINE = MYISAM;
ALTER TABLE `sptbl_tickets` ADD `vViewers` VARCHAR( 256 ) NOT NULL;
CREATE TABLE `sptbl_kb_rating` (`sKBRId` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`nKBID` BIGINT NOT NULL ,`nUserId` BIGINT NOT NULL ,`nMarks` BIGINT NOT NULL) ENGINE = MYISAM;
CREATE TABLE `sptbl_useremail` (`nUseremailId` INT NOT NULL AUTO_INCREMENT ,`nUserId` BIGINT NOT NULL ,`vEmail` VARCHAR( 255 ) NOT NULL ,`vStatus` CHAR( 1 ) NOT NULL DEFAULT 'Y',PRIMARY KEY ( `nUseremailId` )) ENGINE = MYISAM;
ALTER TABLE  `sptbl_replies` ADD  `nHold` TINYINT NULL DEFAULT  '0' AFTER  `vMachineIP` ,ADD  `nReplyStatus` VARCHAR( 50 ) NULL AFTER  `nHold`;
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(1, 'Copper Penny', 'styles/CopperPenny/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(2, 'Aqua Blue', 'styles/AquaBlue/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(3, 'African Violet', 'styles/AfricanViolet/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(4, 'Coffee Brown', 'styles/CoffeeBrown/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(5, 'Escastic Blue', 'styles/EscasticBlue/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(6, 'Feminine Pink', 'styles/FemininePink/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(7, 'Pale Green', 'styles/PaleGreen/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(8, 'Pantone Skin', 'styles/PantoneSkin/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(9, 'Parrot Green', 'styles/ParrotGreen/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(10, 'Tan Brick', 'styles/TanBrick/style.css', '2012-10-08 10:50:03');
INSERT INTO `sptbl_lookup` (`nLookUpId` ,`vLookUpName` ,`vLookUpValue`) VALUES (NULL , 'NewTicketAutoReturnMail', '1');
INSERT INTO `sptbl_lookup` (`nLookUpId` ,`vLookUpName` ,`vLookUpValue`) VALUES (NULL , 'Theme', '2');
INSERT INTO `sptbl_lookup` (`nLookUpId` ,`vLookUpName` ,`vLookUpValue`) VALUES (NULL , 'Version', '4.3');
INSERT INTO sptbl_lang (`vLangCode` ,`VLangDesc`) VALUES ('de','German');
INSERT INTO sptbl_lang (`vLangCode` ,`VLangDesc`) VALUES ('fr','French');
INSERT INTO sptbl_lang (`vLangCode` ,`VLangDesc`) VALUES ('es','Spanish');
ALTER TABLE `sptbl_desktop_share` ADD `Screenshot` TEXT;
CREATE TABLE IF NOT EXISTS `sptbl_ticket_mail_replies` (`replyId` int(10) NOT NULL auto_increment,`toMail` varchar(250) collate latin1_general_ci NOT NULL,`fromMail` varchar(250) collate latin1_general_ci NOT NULL,`mailHeader` varchar(250) collate latin1_general_ci NOT NULL,`subject` text collate latin1_general_ci NOT NULL,`mailBody` longtext collate latin1_general_ci NOT NULL,`sendAt` timestamp NULL default NULL,`addedDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,`actionLogId` int(11) NOT NULL,`personalNotesSubj` text collate latin1_general_ci NOT NULL,`personalNotesMatter` text collate latin1_general_ci NOT NULL,`attachment` text collate latin1_general_ci NOT NULL,`replyPId` int(10) default NULL,PRIMARY KEY  (`replyId`));
CREATE TABLE IF NOT EXISTS `sptbl_ticketlog` (`nTLId` bigint(20) NOT NULL auto_increment,`nStaffId` bigint(20) default '0',`vAction` varchar(100) collate latin1_general_ci NOT NULL default '',`nTLSId` bigint(20) NOT NULL default '0',`nTicketId` bigint(20) NOT NULL default '0',`vRefNo` bigint(20) NOT NULL default '0',`dDate` datetime NOT NULL default '0000-00-00 00:00:00',`nCurDeptId` int(20) NOT NULL,`nPrevDeptId` int(11) NOT NULL,PRIMARY KEY  (`nTLId`));
CREATE TABLE IF NOT EXISTS `sptbl_ticketlogstatus` (`nTLSId` bigint(20) NOT NULL auto_increment,`vLogName` varchar(100) collate latin1_general_ci NOT NULL default '',PRIMARY KEY  (`nTLSId`));
ALTER TABLE `sptbl_actionlog` ADD  `logStatus` ENUM(  'Y',  'N' ) NULL DEFAULT NULL;
ALTER TABLE `sptbl_replies` ADD  `eReplySentstatus` ENUM(  'Y',  'N' ) NOT NULL DEFAULT  'Y';
ALTER TABLE `sptbl_tickets` ADD  `nClosedStaff` INT( 11 ) NOT NULL DEFAULT  '0';
ALTER TABLE `sptbl_kb_rating` ADD COLUMN `vIP` VARCHAR(50) NOT NULL AFTER `nMarks`;
ALTER TABLE `sptbl_depts` ADD COLUMN `nDeptVisibility` INT(11) NULL DEFAULT '1' COMMENT '0=>Not visible to user,1=> Visible to user' AFTER `nResponseTime`;
INSERT INTO `sptbl_lookup` (`vLookUpName`,`vLookUpValue`) VALUES ('HomeFooterContent','How can we help you?');;
Update sptbl_lookup set vLookUpValue='en' where vLookUpName='DefaultLang';;
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='LangChoice';;
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='AutoLock';;
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='VerifyTemplate';;
Update sptbl_lookup set vLookUpValue='1' where vLookUpName='VerifyKB';;
INSERT INTO `sptbl_templates` (`nTemplateId`, `dDate`, `vTemplateTitle`, `tTemplateDesc`, `nStaffId`, `vStatus`) VALUES ('1', '2013-07-26 00:00:00', 'installer_mail', '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<table width="600" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #ccc; background-color:#FDFDFD; ">
    <tbody>
        <tr>
            <td height="5" colspan="3"></td>
        </tr>
        <tr>
            <td width="5"></td>
            <td width="586"><table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td height="56" style="background-color:#324148; ">;
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td width="2%" height="80">&#160;</td>
                            <td width="37%">{SITE_LOGO}</td>
                            <td width="61%"><p style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#CCCCCC; text-align:right; padding:0 15px 0 0; ">{Date}</p></td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>
        <tr>
            <td height="10">&#160;</td>
        </tr>
        <tr>
            <td>&#160;</td>
        </tr>
        <tr>
            <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td width="2%">&#160;</td>
                            <td width="28%">{MAIL_CONTENT}</td>
                            <td width="2%">&#160;</td>
                        </tr>
                        <tr>
                            <td>&#160;</td>
                            <td>&#160;</td>
                            <td>&#160;</td>
                        </tr>
                        <tr>
                            <td>&#160;</td>
                            <td>&#160;</td>
                            <td>&#160;</td>
                        </tr>
                        <tr>
                            <td>&#160;</td>
                            <td>&#160;</td>
                            <td>&#160;</td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>
        <tr>
            <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td height="30" style="background-color:#324148; "><p style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:0 0 0 15px; ">{COPYRIGHT}</p></td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>
    </tbody>
                </table></td>
                <td width="4">&#160;</td>
        </tr>
        <tr>
            <td height="10" colspan="3">&#160;</td>
        </tr>     </tbody> </table> <p>&#160;</p></meta>', '0', '0');
UPDATE `sptbl_css` SET `vCSSURL`='styles/CoffeeBrown/style.css' WHERE `nCSSId`=4 LIMIT 1;
INSERT INTO `sptbl_lookup` (`vLookUpName`) VALUES ('SMTPUsername');
INSERT INTO `sptbl_lookup` (`vLookUpName`) VALUES ('SMTPPassword');
INSERT INTO `sptbl_lookup` (`vLookUpName`) VALUES ('SMTPEnableSSL'); 
ALTER TABLE `sptbl_tickets` ADD COLUMN `merged_from` VARCHAR(255) NOT NULL DEFAULT '0' AFTER `nClosedStaff`,ADD COLUMN `merged_to` VARCHAR(255) NOT NULL DEFAULT '0' AFTER `merged_from`;
ALTER TABLE `sptbl_tickets`ADD COLUMN `merged_by` BIGINT NOT NULL DEFAULT '0' AFTER `merged_to`;
CREATE TABLE IF NOT EXISTS `sptbl_ticket_statistics` (`statistics_id` INT(10) NOT NULL AUTO_INCREMENT,`ticket_id` INT(10) NULL DEFAULT NULL,`reply_time` FLOAT NULL DEFAULT '0',`closing_time` FLOAT NULL DEFAULT '0',`posted_date` DATETIME NULL DEFAULT NULL,PRIMARY KEY (`statistics_id`)) COLLATE='latin1_swedish_ci' ENGINE=MyISAM AUTO_INCREMENT=1;


