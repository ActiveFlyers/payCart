
--
-- Database: `com_paycart`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_configuration`
--

CREATE TABLE IF NOT EXISTS `#__paycart_configuration` (
  `configuration_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_log`
--

CREATE TABLE IF NOT EXISTS `#__paycart_log` (
  `log_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_product_review`
--

CREATE TABLE IF NOT EXISTS `#__paycart_review` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `publish` bit(1) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `created_date` datetime NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_rating`
--

CREATE TABLE IF NOT EXISTS `#__paycart_rating` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`rating_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Product rating will store here' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_attribute`
--

CREATE TABLE IF NOT EXISTS `#__paycart_attribute` (
  `attribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(11) NOT NULL COMMENT 'predefine types like text, numeric etc',
  `searchable` bit(1) DEFAULT 0,
  `publish` bit(1) DEFAULT 0,
  `visible` bit(1) DEFAULT 0,
  `ordering` int(11) DEFAULT 0,
  `params` text ,
  PRIMARY KEY (`attribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='All attributes and their configuration param will store here.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_attribute_value`
--

CREATE TABLE IF NOT EXISTS `#__paycart_attribute_value` (
  `attribute_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_value` text ,
  PRIMARY KEY (`attribute_value_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Values of item''s attribute will be store here' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_category`
--

CREATE TABLE IF NOT EXISTS `#__paycart_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `description` text,
  `cover_image` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `params` text,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='All products''s category will be store here ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_product`
--

CREATE TABLE IF NOT EXISTS `#__paycart_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification of product',
  `name` varchar(255) NOT NULL COMMENT 'Product name',
  `alias` varchar(255) NOT NULL COMMENT 'usful for sef urls',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `type` int(4) DEFAULT NULL COMMENT 'Store pre-defined constant valuestypes like digital,physical etc',
  `amount` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT 'Quantity of Product',
  `sku` varchar(50) NOT NULL COMMENT 'Stock keeping unit',
  `variation_of` int(11) NOT NULL DEFAULT '0' COMMENT 'This product is variation of another product. ',
  `category_id` int(11) DEFAULT '0',
  `params` text,
  `cover_image` varchar(250) DEFAULT NULL,
  `teaser` varchar(250) DEFAULT NULL,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `description` text,
  `hits` int(11) NOT NULL DEFAULT '0',
  `meta_data` text COMMENT 'Here you can store meta title, tag and description.',
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table have all PayCart Products and thier core element.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_cart`
--

CREATE TABLE IF NOT EXISTS `#__paycart_cart` (
  `cart_id` 		int(11)		NOT NULL	AUTO_INCREMENT,
  `buyer_id` 		int(11) 				DEFAULT '0',
  `subtotal` 		decimal(15,5)	 		DEFAULT '0.00000',
  `total` 			decimal(15,5) 			DEFAULT '0.00000', 
  `modifiers` 		text,
  `currency` 		char(3) 				DEFAULT NULL,
  `status` 			int(5) 					DEFAULT '0',
  `created_date` 	datetime 	NOT NULL,
  `modified_date` 	datetime 	NOT NULL,
  `checkout_date` 	datetime 				DEFAULT '0000-00-00 00:00:00',
  `paid_date` 		datetime 				DEFAULT '0000-00-00 00:00:00',
  `complete_date` 		datetime 			DEFAULT '0000-00-00 00:00:00',
  `cancellation_date` 	datetime 			DEFAULT '0000-00-00 00:00:00',
  `refund_date` 	datetime 				DEFAULT '0000-00-00 00:00:00',
  `params` 			text,
  PRIMARY KEY (`cart_id`),
  INDEX `idx_buyer_id` (`buyer_id`),
  INDEX `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_cart_particulars`
--

CREATE TABLE IF NOT EXISTS `#__paycart_cart_particulars` (
  `cart_particulars_id` int(11) 	NOT NULL 	AUTO_INCREMENT,
  `cart_id` 		int(11) 					DEFAULT '0',
  `buyer_id` 		int(11) 					DEFAULT '0',
  `product_id` 		int(11) 					DEFAULT '0',
  `title` 			varchar(255) 				DEFAULT NULL,
  `quantity` 		int(11) 					DEFAULT '0',
  `unit_cost` 		decimal(15,5) 				DEFAULT '0.00000',
  `tax` 			decimal(15,5) 				DEFAULT '0.00000',
  `discount` 		decimal(15,5) 				DEFAULT '0.00000',
  `price` 			decimal(15,5) 				DEFAULT '0.00000',
  `shipment_date` 	datetime 					DEFAULT '0000-00-00 00:00:00',
  `reversal_date` 	datetime 					DEFAULT '0000-00-00 00:00:00',
  `delivery_date` 	datetime 					DEFAULT '0000-00-00 00:00:00',
  `params` 			text 			COMMENT 'Include extra stuff like, Notes.',
  PRIMARY KEY (`cart_particulars_id`),
  INDEX `idx_buyer_id` (`buyer_id`),
  INDEX `idx_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


