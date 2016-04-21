TRUNCATE `sptbl_css`
ALTER TABLE `sptbl_staffratings`  ADD `vType`  CHAR( 1 ) default 'T'
ALTER TABLE `sptbl_kb` ADD `vMetaTage` TEXT NOT NULL
ALTER TABLE `sptbl_kb` CHANGE `vMetaTage` `vMetaTage_keyword` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
ALTER TABLE `sptbl_kb` ADD `vMetaTage_desc` TEXT NOT NULL
ALTER TABLE `sptbl_priorities` ADD `prioritie_icon` VARCHAR( 255 ) NOT NULL
ALTER TABLE `sptbl_priorities` CHANGE `prioritie_icon` `vPrioritie_icon` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
ALTER TABLE `sptbl_users` CHANGE `nCSSId` `nCSSId` TINYINT( 4 ) NOT NULL DEFAULT '3'
CREATE TABLE `sptbl_escalationrules` (`nERId` INT NOT NULL AUTO_INCREMENT, `vRuleName` VARCHAR(256) NOT NULL, `nCompId` INT NOT NULL, `nDeptId` INT NOT NULL, `eRespTimeSetting` ENUM('Y','N') NOT NULL DEFAULT 'N', `eRespCountSetting` ENUM('Y','N') NOT NULL DEFAULT 'N', `nResponseTime` INT NOT NULL, `nResponseCount` INT NOT NULL, `nStaffId` INT NOT NULL, PRIMARY KEY (`nERId`)) ENGINE = MyISAM
ALTER TABLE `sptbl_escalationrules`  ADD `nStatus` INT NOT NULL COMMENT '0=>Active, 1=>Inactive'
CREATE TABLE `sptbl_follow_tickets` (`nFollowId` INT NOT NULL AUTO_INCREMENT ,`nTicketId` INT NOT NULL ,`nStaffId` INT NOT NULL ,`vStaffType` CHAR( 1 ) NOT NULL DEFAULT 'U',PRIMARY KEY ( `nFollowId` )) ENGINE = MYISAM
ALTER TABLE `sptbl_tickets` ADD `vViewers` VARCHAR( 256 ) NOT NULL
CREATE TABLE `sptbl_kb_rating` (`sKBRId` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`nKBID` BIGINT NOT NULL ,`nUserId` BIGINT NOT NULL ,`nMarks` BIGINT NOT NULL) ENGINE = MYISAM

CREATE TABLE `sptbl_useremail` (`nUseremailId` INT NOT NULL AUTO_INCREMENT ,`nUserId` BIGINT NOT NULL ,`vEmail` VARCHAR( 255 ) NOT NULL ,`vStatus` CHAR( 1 ) NOT NULL DEFAULT 'Y',PRIMARY KEY ( `nUseremailId` )) ENGINE = MYISAM

ALTER TABLE  `sptbl_replies` ADD  `nHold` TINYINT NULL DEFAULT  '0' AFTER  `vMachineIP` ,ADD  `nReplyStatus` VARCHAR( 50 ) NULL AFTER  `nHold`
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(1, 'Copper Penny', 'styles/CopperPenny/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(2, 'Aqua Blue', 'styles/AquaBlue/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(3, 'African Violet', 'styles/AfricanViolet/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(4, 'Coffee Brown', 'styles/CoffeBrown/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(5, 'Escastic Blue', 'styles/EscasticBlue/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(6, 'Feminine Pink', 'styles/FemininePink/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(7, 'Pale Green', 'styles/PaleGreen/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(8, 'Pantone Skin', 'styles/PantoneSkin/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(9, 'Parrot Green', 'styles/ParrotGreen/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_css` (`nCSSId`, `vCSSName`, `vCSSURL`, `dDate`) VALUES(10, 'Tan Brick', 'styles/TanBrick/style.css', '2012-10-08 10:50:03')
INSERT INTO `sptbl_lookup` (`nLookUpId` ,`vLookUpName` ,`vLookUpValue`) VALUES (NULL , 'NewTicketAutoReturnMail', '1')
INSERT INTO `sptbl_lookup` (`nLookUpId` ,`vLookUpName` ,`vLookUpValue`) VALUES (NULL , 'Theme', '1')
INSERT INTO `sptbl_lookup` (`nLookUpId` ,`vLookUpName` ,`vLookUpValue`) VALUES (NULL , 'Version', '4.2')
INSERT INTO sptbl_lang (`vLangCode` ,`VLangDesc`) VALUES ('de','German')
INSERT INTO sptbl_lang (`vLangCode` ,`VLangDesc`) VALUES ('fr','French')
INSERT INTO sptbl_lang (`vLangCode` ,`VLangDesc`) VALUES ('es','Spanish')
ALTER TABLE `sptbl_desktop_share` ADD `Screenshot` TEXT
