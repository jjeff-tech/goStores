INSERT IGNORE INTO `goStores_Vista_categories` (`id`, `cname`, `parent_id`, `custom_combination`) VALUES (1, 'Root', 0, '');

UPDATE `goStores_Vista_settings` SET value = 'Garments' WHERE fieldname ='style';

INSERT IGNORE INTO `goStores_Vista_settings` VALUES ('paypal_bn_code', '');

INSERT IGNORE INTO `goStores_Vista_settings` VALUES ('paypaladvanced_bn_code', '');

INSERT IGNORE INTO `goStores_Vista_settings` VALUES ('paypalpro_bn_code', '');

INSERT IGNORE INTO `goStores_Vista_cmspages` (`id`, `title`, `content`, `type`, `status`) VALUES 
(13, 'Registration confirm mail to admin user', '<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><em><span style="font-size: larger;">Hi Dear [User_Name],<br />\r\n</span></em></p>\r\n<p><em><span style="font-size: larger;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You are  Registered as admin user  with&nbsp; [SITE_NAME] is completed......<br />\r\n</span></em></p>\r\n<p><em><span style="font-size: larger;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Your User Name is&nbsp; : [User_Name]</span></em> </p>\r\n<p>&nbsp;&nbsp;<em><span style="font-size: larger;">&nbsp;&nbsp;&nbsp; Your Password is&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; [PASSWORD]</span></em></p>\r\n<p><em><span style="font-size: larger;">&nbsp; [LOGIN_URL]<br />\r\n</span></em></p>\r\n<p><em><span style="font-size: larger;">Thanks and Regards,<br />\r\n</span></em></p>\r\n<p><em><span style="font-size: larger;">Administrator [SITE_NAME]<br />\r\n</span></em></p>', 'email', 'Y'),
(14, 'Contact us mail for admin', '<p>&nbsp;</p>\r\n<p>Dear Administrator,</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A new contact us request has been received at [SITE_NAME]. Please find the details below</p>\r\n<p>Name: [name]</p>\r\n<p>Email: [email]</p>\r\n<p>Message:- [message]</p>', 'email', 'Y');
