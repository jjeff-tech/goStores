INSERT IGNORE INTO `goStores_Banners` (`nBannerId`, `vBannerText`, `vBannerUrl`, `vBannerImageId`, `vActive`, `showcount`, `clickcount`, `eType`, `displayOrder`) VALUES
	(24, '', '', 1013, '1', 0, 2, 'Home Page Sliding Banner', 4),
	(25, '', '', 1012, '1', 0, 6, 'Home Page Sliding Banner', 3),
	(9, '', '', 885, '0', 981, 27, 'Home Page Sliding Banner', 3),
	(10, '', '', 1015, '1', 3624, 40, 'Home Page Sliding Banner', 2),
	(11, '', '', 1014, '1', 4217, 35, 'Home Page Sliding Banner', 1),
	(27, 'test', 'www.google.com', 1017, '1', 0, 0, 'Home Page Sliding Banner', 5); 

INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('enabletranslator', 'Enable Google Translator', 'Y', 'General', 'checkbox', 0, 'Would you like your site display Google translator');
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('bluedog_enable', 'Enable BlueDog ', 'Y', 'Payment', '', 0, 'Note : BlueDog will support only US dollars.');
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('bluedog_live_apikey', 'BlueDog  Login Id', '', 'Payment', '', 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('bluedog_sandbox_apikey', 'BlueDog Transaction Key', '', 'Payment', '', 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('bluedog_test_mode', 'BlueDog Test Mode', 'N', 'Payment', 'checkbox', 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('smtp_username', 'SMTP Username', 'Iscripts Gostores', 'SMTP', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('smtp_password', 'SMTP Password', '', 'SMTP', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('smtp_host', 'SMTP Host', '', 'SMTP', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('smtp_port', 'SMTP Port', '', 'SMTP', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('smtp_protocol', 'SMTP Protocol', 'tls', 'SMTP', 'radio', 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('company_address', 'Company Address', '4268 Sumner', 'General', 'textarea', 0, 'Enter your company address');
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('inventory_source_amount', 'Inventory Source Amount', '0.01', 'General', 'textbox', 0, 'Inventory Source Plugin Amount');
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('inventory_source_plan_duration', 'Inventory Source Plan Duration', '', 'General', 'textbox', 0, 'Inventory Source Plugin Plan Duration');
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('siteUrl', 'Site URL', '', 'General', '', 0, '');
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('enom_uiseripd', 'Enom Server IP', '', 'Domain Registrar', '', 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_enable', 'Enable Stripe', 'N', 'Payment', 'checkbox', 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_live_publishkey', 'Stripe Live Publish Key', '', 'payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_live_secretkey', 'Stripe Live Secret Key', '', 'payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_live_webhook_secret_key', 'Stripe Live webhook Secret Key', '', 'payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_sandbox_publishkey', 'Stripe Test Publish Key', '', 'Payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_sandbox_secretkey', 'Stripe Test Secret Key', '', 'Payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_test_mode', 'Stripe Test mode', 'Y', 'payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_webhook_secret_key', 'Strip webhook secret key', '', 'Payment', NULL, 0, NULL);
INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_webhook_url', 'Stripe Webhook Url', 'http://yourdomain.com/index/stripepayment/', 'Payment', NULL, 0, NULL);


INSERT IGNORE INTO `goStores_PaidTemplates` (`nTemplateId`, `vTemplateName`, `vDescription`, `nCost`, `vTemplateZipId`, `vHomeScreenshotId`, `vInnerScreenshot1Id`, `vInnerScreenshot2Id`, `vActive`) VALUES
	(4, 'Template 4', 'This design is optimized for maximum sales performance. Sporting a modern look, traveling navigation bar, and many more additional features.', 0.00, 806, 1029, 1058, 1059, '1'),
	(5, 'Template 5', 'This templates responsive and clean design will catch your customers interest. Easy to navigate and loads fast. Perfect for drop shipping', 0.00, 810, 1049, 1050, 1051, '1'),
	(1, 'Template 1', 'This minimalist design is easy and intuitive for your customers. Numerous color options. Grid design style.', 0.00, 814, 1026, 1052, 1053, '1'),
	(2, 'Template 2', 'This template is designed to highlight products as well as makes use of space for ad space, additional banners, etc.', 0.00, 818, 1027, 1054, 1055, '1'),
	(6, 'Template 6', 'This templates responsive and clean design will catch your customers interest. Easy to navigate and loads fast. Perfect for drop shipping', 0.00, 858, 1031, 1060, 1061, '1'),
	(3, 'Template 3', 'This templates responsive and clean design will catch your customers interest. Easy to navigate and loads fast. Perfect for drop shipping\r\n', 0.00, 929, 1028, 1056, 1057, '1');


INSERT INTO `goStores_Settings` (`settingfield`, `settinglabel`, `value`, `groupLabel`, `type`, `fieldOrder`, `helpText`) VALUES ('stripe_test_mode', 'Stripe Test mode', 'Y', 'payment', NULL, 0, NULL)
