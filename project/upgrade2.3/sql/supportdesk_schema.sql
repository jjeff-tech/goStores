CREATE TABLE IF NOT EXISTS `sptbl_ticket_mail_replies` (`replyId` int(10) NOT NULL auto_increment,`toMail` varchar(250) collate latin1_general_ci NOT NULL,`fromMail` varchar(250) collate latin1_general_ci NOT NULL,`mailHeader` varchar(250) collate latin1_general_ci NOT NULL,`subject` text collate latin1_general_ci NOT NULL,`mailBody` longtext collate latin1_general_ci NOT NULL,`sendAt` timestamp NULL default NULL,`addedDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,`actionLogId` int(11) NOT NULL,`personalNotesSubj` text collate latin1_general_ci NOT NULL,`personalNotesMatter` text collate latin1_general_ci NOT NULL,`attachment` text collate latin1_general_ci NOT NULL,`replyPId` int(10) default NULL,PRIMARY KEY  (`replyId`));
CREATE TABLE IF NOT EXISTS `sptbl_ticketlog` (`nTLId` bigint(20) NOT NULL auto_increment,`nStaffId` bigint(20) default '0',`vAction` varchar(100) collate latin1_general_ci NOT NULL default '',`nTLSId` bigint(20) NOT NULL default '0',`nTicketId` bigint(20) NOT NULL default '0',`vRefNo` bigint(20) NOT NULL default '0',`dDate` datetime NOT NULL default '0000-00-00 00:00:00',`nCurDeptId` int(20) NOT NULL,`nPrevDeptId` int(11) NOT NULL,PRIMARY KEY  (`nTLId`));
CREATE TABLE IF NOT EXISTS `sptbl_ticketlogstatus` (`nTLSId` bigint(20) NOT NULL auto_increment,`vLogName` varchar(100) collate latin1_general_ci NOT NULL default '',PRIMARY KEY  (`nTLSId`));
ALTER TABLE `sptbl_actionlog` ADD  `logStatus` ENUM(  'Y',  'N' ) NULL DEFAULT NULL;
ALTER TABLE `sptbl_replies` ADD  `eReplySentstatus` ENUM(  'Y',  'N' ) NOT NULL DEFAULT  'Y';
ALTER TABLE `sptbl_tickets` ADD  `nClosedStaff` INT( 11 ) NOT NULL DEFAULT  '0';
ALTER TABLE `sptbl_kb_rating` ADD COLUMN `vIP` VARCHAR(50) NOT NULL AFTER `nMarks`;
ALTER TABLE `sptbl_depts` ADD COLUMN `nDeptVisibility` INT(11) NULL DEFAULT '1' COMMENT '0=>Not visible to user,1=> Visible to user' AFTER `nResponseTime`;
ALTER TABLE `sptbl_tickets` ADD COLUMN `merged_from` VARCHAR(255) NOT NULL DEFAULT '0' AFTER `nClosedStaff`,ADD COLUMN `merged_to` VARCHAR(255) NOT NULL DEFAULT '0' AFTER `merged_from`;
ALTER TABLE `sptbl_tickets`ADD COLUMN `merged_by` BIGINT NOT NULL DEFAULT '0' AFTER `merged_to`;
CREATE TABLE IF NOT EXISTS `sptbl_ticket_statistics` (`statistics_id` INT(10) NOT NULL AUTO_INCREMENT,`ticket_id` INT(10) NULL DEFAULT NULL,`reply_time` FLOAT NULL DEFAULT '0',`closing_time` FLOAT NULL DEFAULT '0',`posted_date` DATETIME NULL DEFAULT NULL,PRIMARY KEY (`statistics_id`)) COLLATE='latin1_swedish_ci' ENGINE=MyISAM AUTO_INCREMENT=1;

