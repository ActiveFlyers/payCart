--
-- Database: `com_paycart`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_attribute`
--

CREATE TABLE IF NOT EXISTS `#__paycart_attribute` (
  `attribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `searchable` tinyint(1) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL,
  `params` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='All attributes and their configuration param will store here.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_attribute_value`
--

CREATE TABLE IF NOT EXISTS `#__paycart_attribute_value` (
  `attribute_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`attribute_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Values of item''s attribute will be store here' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_category`
--

CREATE TABLE IF NOT EXISTS `#__paycart_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_bin NOT NULL,
  `alias` varchar(250) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `cover_image` varchar(250) COLLATE utf8_bin NOT NULL,
  `parent` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  `params` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='All item''s category will be store here ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_item`
--

CREATE TABLE IF NOT EXISTS `#__paycart_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification of item',
  `name` varchar(200) COLLATE utf8_bin NOT NULL COMMENT 'Item name',
  `alias` varchar(200) COLLATE utf8_bin NOT NULL COMMENT 'usful for sef urls',
  `price` float NOT NULL,
  `sku` int(10) NOT NULL COMMENT 'Stock keeping unit, Quantity of items',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT 'define group of items',
  `params` text COLLATE utf8_bin NOT NULL,
  `cover_image` varchar(250) COLLATE utf8_bin NOT NULL,
  `teaser` varchar(250) COLLATE utf8_bin NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `hits` int(22) NOT NULL,
  `meta_data` text COLLATE utf8_bin NOT NULL COMMENT 'Here you can store meta title, tag and description.',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table have all PayCart items and thier core element.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_item_category`
--

CREATE TABLE IF NOT EXISTS `#__paycart_item_category` (
  `item_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`item_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tbale contain item and actegory relation.' AUTO_INCREMENT=1 ;

