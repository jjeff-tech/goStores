ALTER TABLE `goStores_Banners` MODIFY `eType` ENUM( 'Home Page Sliding Banner', 'Footer' ) NOT NULL DEFAULT 'Footer';
DROP TABLE `cms_sections`;
CREATE TABLE `cms_sections` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;