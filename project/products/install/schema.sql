DROP TABLE IF EXISTS `Vista_shipping_rates`;
CREATE TABLE IF NOT EXISTS `Vista_shipping_rates` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_states`;
CREATE TABLE `Vista_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `country_id` varchar(10) NOT NULL DEFAULT '',
  `tax_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_users`;
CREATE TABLE `Vista_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usertype` varchar(20) NOT NULL DEFAULT 'R',
  `reg_status` varchar(50) NOT NULL DEFAULT '',
  `user_credit` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_offer_target_products`;
CREATE TABLE `Vista_offer_target_products` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` bigint(11) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offer Target Products' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_coupon_orders`;
CREATE TABLE `Vista_coupon_orders` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `used_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Vista_customfields`;
CREATE TABLE `Vista_customfields` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `custom_name` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `custom_id` (`custom_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_productdetails`;
CREATE TABLE `Vista_productdetails` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_refundoptions`;
CREATE TABLE `Vista_refundoptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_customcombinations`;
CREATE TABLE `Vista_customcombinations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) NOT NULL DEFAULT '0',
  `customfield_id` bigint(20) NOT NULL DEFAULT '0',
  `customvalue_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Vista_customer_groups`;
CREATE TABLE `Vista_customer_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(250) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Customer groups' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_giftcards`;
CREATE TABLE `Vista_giftcards` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_customfieldvalues`;
CREATE TABLE `Vista_customfieldvalues` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customfield_id` bigint(20) NOT NULL DEFAULT '0',
  `custom_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Vista_newsletterusers`;
CREATE TABLE `Vista_newsletterusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `sub_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('N','Y') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_helps`;
CREATE TABLE `Vista_helps` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `type` enum('A','U') NOT NULL DEFAULT 'A',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_catalogs`;
CREATE TABLE `Vista_catalogs` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `catalog_invsrc_id` bigint(11) NOT NULL,
  `catalog_name` varchar(250) NOT NULL,
  `supplier_id` bigint(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Vista_coupons`;
CREATE TABLE `Vista_coupons` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_wishlists`;
CREATE TABLE `Vista_wishlists` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='User''s wishlist';

DROP TABLE IF EXISTS `Vista_producttags`;
CREATE TABLE `Vista_producttags` (
  `prod_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT 'Product Id',
  `tag_id` int(11) NOT NULL COMMENT 'Tag Id',
  PRIMARY KEY (`prod_tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Product tag reference table' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_product_attributes`;
CREATE TABLE `Vista_product_attributes` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(11) NOT NULL,
  `attr_name` varchar(250) NOT NULL,
  `attr_value` varchar(250) NOT NULL,
  `is_from_invsrc` int(1) NOT NULL DEFAULT '0',
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Vista_taxdetails`;
CREATE TABLE `Vista_taxdetails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `orderid` int(10) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_order_details`;
CREATE TABLE `Vista_order_details` (
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
  PRIMARY KEY (`id`),
  KEY `orders_id` (`order_id`,`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_visitors`;
CREATE TABLE `Vista_visitors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device` char(1) NOT NULL DEFAULT 'C',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_statecodes`;
CREATE TABLE `Vista_statecodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `country_id` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_vendors`;
CREATE TABLE `Vista_vendors` (
  `vendor_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(250) NOT NULL,
  `vendor_code` varchar(250) DEFAULT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Product Vendor Details';

DROP TABLE IF EXISTS `Vista_refundstatuses`;
CREATE TABLE `Vista_refundstatuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_productvariantdetails`;
CREATE TABLE `Vista_productvariantdetails` (
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
  PRIMARY KEY (`variant_detail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Product variant combinations';

DROP TABLE IF EXISTS `Vista_productinventoryhistory`;
CREATE TABLE `Vista_productinventoryhistory` (
  `inv_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `variant_detail_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_old` int(11) NOT NULL,
  `stock_new` int(11) NOT NULL,
  `inv_count` int(11) NOT NULL,
  `hist_date` datetime NOT NULL,
  PRIMARY KEY (`inv_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Product Inventory Updated Details' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_reports`;
CREATE TABLE `Vista_reports` (
  `id` int(11) NOT NULL DEFAULT '0',
  `reports` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_countries`;
CREATE TABLE `Vista_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(64) NOT NULL DEFAULT '',
  `country_iso_code_2` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`country_id`),
  KEY `IDX_COUNTRY_NAME` (`country_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_productimages`;
CREATE TABLE `Vista_productimages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(100) NOT NULL DEFAULT '0',
  `imagename` varchar(200) NOT NULL DEFAULT '',
  `default_img` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`imagename`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_customer_group_details`;
CREATE TABLE `Vista_customer_group_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_group_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Customer group members';

DROP TABLE IF EXISTS `Vista_offer_upsell_products_temps`;
CREATE TABLE `Vista_offer_upsell_products_temps` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` varchar(250) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Upsell products temporary table' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_categorymarkups`;
CREATE TABLE `Vista_categorymarkups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `type` enum('1','2') NOT NULL COMMENT '1-fixed,2-tired',
  `markup_percent` varchar(255) NOT NULL,
  `markup_flat_rate` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_cmspages`;
CREATE TABLE `Vista_cmspages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text,
  `type` varchar(100) NOT NULL DEFAULT 'email',
  `status` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_offers`;
CREATE TABLE `Vista_offers` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Offer Details' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_refundreasons`;
CREATE TABLE `Vista_refundreasons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_templates`;
CREATE TABLE `Vista_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `banner_resolution` varchar(255) NOT NULL,
  `ad_resolution` varchar(255) DEFAULT NULL,
  `ad_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_offer_target_products_temps`;
CREATE TABLE `Vista_offer_target_products_temps` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` varchar(250) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_newsletters`;
CREATE TABLE `Vista_newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  `news_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_banners`;
CREATE TABLE `Vista_banners` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `bannerfile` varchar(250) NOT NULL,
  `template_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '1',
  `bannertitle` varchar(255) NOT NULL,
  `bannerdata` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Vista_randoms`;
CREATE TABLE `Vista_randoms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rand_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_productcustomfields`;
CREATE TABLE `Vista_productcustomfields` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL DEFAULT '0',
  `customfield_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`customfield_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_advertisements`;
CREATE TABLE `Vista_advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adfile` varchar(255) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_currencies`;
CREATE TABLE `Vista_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) DEFAULT NULL,
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_feedbacks`;
CREATE TABLE `Vista_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `date` varchar(25) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_productvariantdeletedetails`;
CREATE TABLE `Vista_productvariantdeletedetails` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_sales_channels`;
CREATE TABLE `Vista_sales_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_channel` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Sales channel master table' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_abandoned_carts`;
CREATE TABLE `Vista_abandoned_carts` (
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Abandoned Cart Details';

DROP TABLE IF EXISTS `Vista_suppliers`;
CREATE TABLE `Vista_suppliers` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(250) NOT NULL,
  `sku_prefix` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Product suppliers';

DROP TABLE IF EXISTS `Vista_order_statuses`;
CREATE TABLE `Vista_order_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_abandoned_cart_details`;
CREATE TABLE `Vista_abandoned_cart_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aban_cart_id` int(11) NOT NULL,
  `sess_id` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL DEFAULT '0',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prodtype` varchar(20) NOT NULL,
  `extra_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(5) NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Abandoned Cart - Cart Details';

DROP TABLE IF EXISTS `Vista_productvariants`;
CREATE TABLE `Vista_productvariants` (
  `variant_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `variant_name` varchar(100) NOT NULL,
  PRIMARY KEY (`variant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Product Variants';

DROP TABLE IF EXISTS `Vista_products`;
CREATE TABLE `Vista_products` (
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
  `featured` enum('0','1') NOT NULL DEFAULT '1',
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
  `invsrc_firearm` enum('TRUE','FALSE') DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_admins`;
CREATE TABLE `Vista_admins` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_orders`;
CREATE TABLE `Vista_orders` (
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
  `fulfillment_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)  
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_offer_upsell_products`;
CREATE TABLE `Vista_offer_upsell_products` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `offer_id` bigint(11) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_settings`;
CREATE TABLE `Vista_settings` (
  `fieldname` varchar(100) NOT NULL DEFAULT '',
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_tags`;
CREATE TABLE `Vista_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(250) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Tags table';

DROP TABLE IF EXISTS `Vista_giftcard_details`;
CREATE TABLE `Vista_giftcard_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `card_id` bigint(20) NOT NULL DEFAULT '0',
  `card_code` text NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_giftcardusers`;
CREATE TABLE `Vista_giftcardusers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `card_id` bigint(20) NOT NULL DEFAULT '0',
  `usercard_id` bigint(20) NOT NULL DEFAULT '0',
  `date` varchar(20) NOT NULL DEFAULT '',
  `amnt_used` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_ratings`;
CREATE TABLE `Vista_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_sent_newsletters`;
CREATE TABLE `Vista_sent_newsletters` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `newsletter_id` bigint(20) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_productvariantvaluedetails`;
CREATE TABLE `Vista_productvariantvaluedetails` (
  `variant_value_detail_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(11) NOT NULL,
  `variant_detail_id` bigint(11) NOT NULL,
  `variant_id` bigint(11) NOT NULL,
  `variant_value` varchar(500) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`variant_value_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Vista_refunds`;
CREATE TABLE `Vista_refunds` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_homelayouts`;
CREATE TABLE `Vista_homelayouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_type` char(50) DEFAULT NULL,
  `section` char(15) DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_contacts`;
CREATE TABLE `Vista_contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cname` varchar(50) NOT NULL DEFAULT '',
  `cemail` varchar(200) NOT NULL DEFAULT '',
  `cdate` varchar(25) NOT NULL DEFAULT '',
  `cdescr` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_user_addresses`;
CREATE TABLE `Vista_user_addresses` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_refundproducts`;
CREATE TABLE `Vista_refundproducts` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `Vista_categories`;
CREATE TABLE `Vista_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) NOT NULL DEFAULT '',
  `seo_slug` varchar(250) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `custom_combination` varchar(255) NOT NULL DEFAULT '',
  `is_from_invsrc` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Vista_productvariantvalues`;
CREATE TABLE `Vista_productvariantvalues` (
  `variant_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`variant_value_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Product Variant Values';

DROP TABLE IF EXISTS `Vista_carts`;
CREATE TABLE `Vista_carts` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `Vista_orders` ADD `tracking_service` VARCHAR(500) NOT NULL AFTER `tracking_no`;
ALTER TABLE `Vista_templates` ADD `status` INT(1) NOT NULL AFTER `ad_count`;

CREATE TABLE IF NOT EXISTS `Vista_invsrc_products` (
 `id` bigint(11) NOT NULL AUTO_INCREMENT,
 `invsrc_product_id` bigint(11) NOT NULL,
 `catalog_id` bigint(11) NOT NULL,
 `invsrc_product_name` text NOT NULL,
 `invsrc_upc` varchar(250) NOT NULL,
 `sync_date` datetime NOT NULL,
 `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE `Vista_order_details` ADD COLUMN `order_item_status_id` INT(11) NULL DEFAULT '0' AFTER `price_type`;
CREATE TABLE IF NOT EXISTS `Vista_order_cronjob_errors` (
 `id` bigint(11) NOT NULL AUTO_INCREMENT,
 `order_id` bigint(11) NOT NULL,
 `order_invsrc_id` bigint(11) NOT NULL,
 `channel_id` bigint(11) NOT NULL,
 `order_endpoint` text NOT NULL,
 `error_msg` text NOT NULL,
 `issued_date` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Order';
ALTER TABLE `Vista_orders` ADD `bd_transact_id` VARCHAR(250) NULL AFTER `is_cancelled`;
ALTER TABLE `Vista_orders` ADD `bd_user_id` VARCHAR(250) NULL AFTER `bd_transact_id`;
ALTER TABLE `Vista_abandoned_cart_details` CHANGE `discount_type` `discount_type` varchar(5) COLLATE 'utf8_general_ci' NULL AFTER `extra_price`;
ALTER TABLE `Vista_products` CHANGE `featured` `featured` tinyint NOT NULL DEFAULT '1' AFTER `image2`,CHANGE `invsrc_firearm` `invsrc_firearm` tinyint NULL DEFAULT '0' AFTER `invsrc_isbn`;
ALTER TABLE `Vista_productvariantdetails` ADD `inv_barcode` VARCHAR(100) NULL AFTER `pdctfile`;
ALTER TABLE `Vista_abandoned_cart_details` CHANGE `discount` `discount` decimal(10,2) NULL DEFAULT '0.00' AFTER `discount_type`;
ALTER TABLE `Vista_abandoned_carts` ADD `payment_intent` VARCHAR(250) NULL AFTER `isActive`;