INSERT IGNORE INTO `goStores_Vista_categories` (`id`, `cname`, `parent_id`, `custom_combination`) VALUES (1, 'Root', 0, '');

UPDATE `goStores_Vista_settings` SET value = 'Garments' WHERE fieldname ='style';

INSERT IGNORE INTO `goStores_Vista_settings` VALUES ('paypal_bn_code', '');

INSERT IGNORE INTO `goStores_Vista_settings` VALUES ('paypaladvanced_bn_code', '');

INSERT IGNORE INTO `goStores_Vista_settings` VALUES ('paypalpro_bn_code', '');