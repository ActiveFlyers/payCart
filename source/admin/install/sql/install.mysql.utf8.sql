
--
-- Database: `com_paycart`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_config`
--
CREATE TABLE IF NOT EXISTS `#__paycart_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text,
  UNIQUE KEY `idx_key` (`key`),
  UNIQUE KEY `idx_config_id` (`config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
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
-- Table structure for table `#__paycart_productattribute`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productattribute` (
  `productattribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL COMMENT 'Type of attribute',
  `css_class` varchar(100) DEFAULT NULL,
  `filterable` tinyint(1) NOT NULL COMMENT 'Treat as a filter',
  `searchable` tinyint(1) DEFAULT '0' COMMENT 'Use for keyword search',
  `status` enum('published','invisible','unpublished','trashed') NOT NULL ,
  `config` text,
  `ordering` int(11) DEFAULT '0',
  PRIMARY KEY (`productattribute_id`),
  KEY `idx_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='All attributes and their configuration param will store here.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_productattribute_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productattribute_lang` (
  `productattribute_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `productattribute_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'attribute name',
  PRIMARY KEY (`productattribute_lang_id`),
  KEY `idx_productattribute_id` (`productattribute_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_productattribute_value`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productattribute_value` (
  `product_id` int(11) NOT NULL,
  `productattribute_id` int(11) NOT NULL,
  `productattribute_value` int(11) NOT NULL,
  INDEX `idx_product_id` (`product_id`),
  INDEX `idx_productattribute_id` (`productattribute_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Values of item''s attribute will be store here' ;


-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_productcategory`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productcategory` (
  `productcategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `cover_media` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `status` enum('published','invisible','unpublished','trashed') NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`productcategory_id`),
  KEY `idx_left_right` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_productcategory_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productcategory_lang` (
  `productcategory_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `productcategory_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `description` text,
  `metadata_title` varchar(255) DEFAULT NULL,
  `metadata_keywords` varchar(255) DEFAULT NULL,
  `metadata_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`productcategory_lang_id`),
  UNIQUE KEY `idx_alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_product`
--

CREATE TABLE IF NOT EXISTS `#__paycart_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification of product',
  `productcategory_id` int(11) DEFAULT 0,
  `type` varchar(50) NOT NULL COMMENT 'Type of Product',
  `status` enum('published','invisible','unpublished','trashed') NOT NULL,
  `variation_of` int(11) NOT NULL DEFAULT '0' COMMENT 'This product is variation of another product. ',
  `sku` varchar(50) NOT NULL COMMENT 'Stock keeping unit',
  `price` double(15,4) NOT NULL,
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT 'Quantity of Product',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `cover_media` varchar(250) DEFAULT NULL,
  `stockout_limit`int NOT NULL DEFAULT '0' COMMENT 'out-of-stock limit of Product',
  `weight` decimal(12,4) DEFAULT '0.0000',
  `weight_unit` varchar(50) DEFAULT NULL,
  `height` decimal(12,4) DEFAULT '0.0000',
  `length` decimal(12,4) DEFAULT '0.0000',
  `width` decimal(12,4) DEFAULT '0.0000',
  `dimension_unit`varchar(50) DEFAULT NULL,
  `config` text COMMENT 'Store layouts',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `productcategory_id` (`productcategory_id`),
  UNIQUE KEY `idx_sku` (`sku`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table have all PayCart Products and thier core element.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_product_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_product_lang` (
  `product_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'Product name',
  `alias` varchar(100) NOT NULL COMMENT 'useful for sef urls',
  `description` text,
  `teaser` varchar(255) DEFAULT NULL COMMENT 'Product short description',
  `metadata_title` varchar(255) COMMENT 'Here you can store meta title.',
  `metadata_keywords` varchar(255) COMMENT 'Here you can store meta tag.',
  `metadata_description` varchar(255) COMMENT 'Here you can store meta description.',
  PRIMARY KEY (`product_lang_id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_cart`
--

CREATE TABLE IF NOT EXISTS `#__paycart_cart` (
  `cart_id` 		int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` 		int(11) DEFAULT '0',
  `session_id` 		varchar(200) DEFAULT '',
  `invoice_id` 		int(11) DEFAULT '0' COMMENT 'mapped invoice id with rb_ecommerce_invoice table',
  `status` 			enum('drafted','checkedout','paid','cancelled','completed') NOT NULL,
  `currency` 		char(3) NOT NULL COMMENT 'isocode 3',
  `reversal_for` 	int(11) DEFAULT '0' COMMENT 'reversal of cart (parent) : When cart is reversal then new entry is created into cart and set here cart_id which is reversed  (might be cart partial refunded)',
  `ip_address` 		varchar(255) DEFAULT '0' COMMENT 'cart created from',
  `billing_address_id` 	int(11) DEFAULT '0',
  `shipping_address_id` int(11) DEFAULT '0' COMMENT 'Cart will shipp only one address',
  `secure_key`		varchar(255) NOT NULL COMMENT 'used for url security',
  `is_locked` 		int(11)  NOT NULL COMMENT 'Stop re-calculation',
  `created_date` 	datetime DEFAULT '0000-00-00 00:00:00',
  `modified_date` 	datetime DEFAULT '0000-00-00 00:00:00',
  `checkedout_date` 	datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'Date of either cart is checked out or reversal is created',
  `paid_date` 		datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'Payment Completion date.',
  `cancelled_date` 	datetime DEFAULT '0000-00-00 00:00:00',
  `completed_date` 	datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'when final status done (paid+shipped)',
  `params` 		text		 			COMMENT 'Products and their quantiy store here initial',
  `is_guestcheckout` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `idx_invoice_id` (`invoice_id`),
  KEY `idx_buyer_id` (`buyer_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_cart_particulars`
--

CREATE TABLE IF NOT EXISTS `#__paycart_cartparticular` (
  `cartparticular_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) DEFAULT '0',
  `buyer_id` int(11) DEFAULT '0',
  `particular_id` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `unit_cost` decimal(15,5) DEFAULT '0.00000',
  `tax` decimal(15,5) DEFAULT '0.00000',
  `discount` decimal(15,5) DEFAULT '0.00000',
  `price` decimal(15,5) DEFAULT '0.00000',
  `total` decimal(15,5) DEFAULT '0.00000',
  `shipment_date` datetime DEFAULT '0000-00-00 00:00:00',
  `reversal_date` datetime DEFAULT '0000-00-00 00:00:00',
  `delivery_date` datetime DEFAULT '0000-00-00 00:00:00',
  `params` text COMMENT 'Include extra stuff like, Notes.',
  PRIMARY KEY (`cartparticular_id`),
  INDEX `idx_buyer_id` (`buyer_id`),
  INDEX `idx_particular_id` (`particular_id`),
  INDEX `idx_cart_id` (`cart_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_buyer`
--

CREATE TABLE IF NOT EXISTS `#__paycart_buyer` (
  `buyer_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_registered_by_guestcheckout` tinyint(4) NOT NULL DEFAULT '0',
  `billing_address_id` int(11) DEFAULT NULL,
  `shipping_address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`buyer_id`),
  KEY `idx_billing_address_id` (`billing_address_id`),
  KEY `idx_shipping_address_id` (`shipping_address_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_buyeraddress`
--

CREATE TABLE IF NOT EXISTS `#__paycart_buyeraddress` (
  `buyeraddress_id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `to` varchar(100) NOT NULL COMMENT 'reference name',
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state_id` VARCHAR(7) NOT NULL COMMENT 'State ISO code3',
  `country_id` char(3) NOT NULL COMMENT 'Country ISO code3',
  `zipcode` varchar(10) NOT NULL DEFAULT '',
  `vat_number` varchar(100) NOT NULL,
  `phone1` varchar(32) NOT NULL,
  `phone2` varchar(32) NOT NULL,
  PRIMARY KEY (`buyeraddress_id`),
  KEY `idx_buyer_id` (`buyer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_filter`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productfilter` (
  `product_id` int(11) NOT NULL,
   PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Filter Column Available here as Fields';


-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_indexer`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productindex` (
  `product_id` int(11) NOT NULL COMMENT 'Product identification',
  `content` longtext CHARACTER SET utf8 NOT NULL COMMENT 'Use for keyword search',
   PRIMARY KEY (`product_id`),  
   FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_discountrule`
--

CREATE TABLE IF NOT EXISTS `#__paycart_discountrule` (
  `discountrule_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `apply_on` varchar(50) NOT NULL COMMENT 'entity-name, rule apply on',
  `published` tinyint(1) NOT NULL,
  `amount` double(15,4) NOT NULL COMMENT 'Discount amount',
  `is_percentage` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is percentage or flat discount',
  `buyer_usage_limit` int(11) NOT NULL COMMENT 'usage Counter per buyer',
  `usage_limit` int(11) NOT NULL COMMENT 'rule''s usage counter',
  `coupon` varchar(100) NOT NULL,
  `is_clubbable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Clubbable with other discount or not',
  `is_successive` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'calculate discounted amount on row-total or base-price',
  `sequence` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Applied discount sequence/priority.',
  `start_date` datetime NOT NULL COMMENT 'Discount started date (date with time)',
  `end_date` datetime NOT NULL COMMENT 'Discount end date (date with time)',
  `processor_type` varchar(100) NOT NULL COMMENT 'processor name in small-case',
  `processor_config` text NOT NULL COMMENT 'processor configuration',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`discountrule_id`),
  KEY `idx_coupon` (`coupon`),
  KEY `idx_applied_on` (`apply_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='All available discountrule' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_discountrule_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_discountrule_lang` (
  `discountrule_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `discountrule_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `message` varchar(255) NOT NULL COMMENT 'Help msg for end user',
  PRIMARY KEY (`discountrule_lang_id`),
  KEY `idx_discountrule_id` (`discountrule_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_discountrule_x_class`
--

CREATE TABLE IF NOT EXISTS `#__paycart_discountrule_x_group` (
  `discountrule_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  KEY `idx_discountrule_id` (`discountrule_id`),
  KEY `idx_group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Discount-rule and class mapper table' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_country`
--

CREATE TABLE IF NOT EXISTS `#__paycart_country` (
  `country_id` char(3) NOT NULL COMMENT 'isocode3',
  `isocode2` char(2) NOT NULL,
  `call_prefix` varchar(10) NOT NULL,
  `zip_format` varchar(12) NOT NULL,
  `status` enum('published','unpublished','trashed') NOT NULL DEFAULT 'published',
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`country_id`),
  KEY `idx_isocode2` (`isocode2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_country_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_country_lang` (
  `country_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` char(3) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`country_lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_state`
--

CREATE TABLE IF NOT EXISTS `#__paycart_state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `isocode` varchar(7) NOT NULL,
  `country_id` char(3) NOT NULL,
  `status` enum('published','unpublished','trashed') NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`state_id`),
  KEY `idx_isocode2` (`isocode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_state_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_state_lang` (
  `state_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` char(3) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`state_lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_taxrule`
--

CREATE TABLE IF NOT EXISTS `#__paycart_taxrule` (
  `taxrule_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` double(15,4) NOT NULL,
  `apply_on` varchar(50) NOT NULL COMMENT 'entity name on which to apply rule',
  `processor_classname` varchar(100) NOT NULL COMMENT 'Classname of processor in small-case',
  `processor_config` text NOT NULL COMMENT 'processor configuration',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`taxrule_id`),
  KEY `idx_apply_on` (`apply_on`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_taxrule_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_taxrule_lang` (
  `taxrule_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `taxrule_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `message` varchar(255) NOT NULL COMMENT 'Help msg for end user',
  PRIMARY KEY (`taxrule_lang_id`),
  KEY `idx_taxrule_id` (`taxrule_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_taxrule_x_group`
--

CREATE TABLE IF NOT EXISTS `#__paycart_taxrule_x_group` (
  `taxrule_x_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `taxrule_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`taxrule_x_group_id`),
  KEY `idx_taxrule_id` (`taxrule_id`),
  KEY `idx_class_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Mapping of taxrule and groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_media`
--

CREATE TABLE IF NOT EXISTS `#__paycart_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `is_free` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_media_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_media_lang` (
  `media_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'media name',
  `description` text,
  `metadata_title` varchar(255) DEFAULT NULL,
  `metadata_keywords` varchar(255) DEFAULT NULL,
  `metadata_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`media_lang_id`),
  KEY `idx_media_id` (`media_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_color`
--

CREATE TABLE IF NOT EXISTS `#__paycart_color` (
  `color_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_code` varchar(50) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_color_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_color_lang` (
  `color_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `color_id` varchar(255) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'name of color',
  PRIMARY KEY (`color_lang_id`),
  KEY `idx_color_id` (`color_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_productattribute_option`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productattribute_option` (
  `productattribute_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `productattribute_id` int(11) NOT NULL,
  `option_ordering` int(11) NOT NULL,
  PRIMARY KEY (`productattribute_option_id`),
  KEY `idx_productattribute_id` (`productattribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_productattribute_option_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productattribute_option_lang` (
  `productattribute_option_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `productattribute_option_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`productattribute_option_lang_id`),
  KEY `idx_productattribute_option_id` (`productattribute_option_id`),
  KEY `idx_lang_code` (`lang_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_group`
--

CREATE TABLE IF NOT EXISTS `#__paycart_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `config` text,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_paymentgateway`
--

CREATE TABLE IF NOT EXISTS `#__paycart_paymentgateway` (
  `paymentgateway_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('published','unpublished','trashed') NOT NULL,
  `processor_type` varchar(255) NOT NULL,
  `processor_config` text,
  PRIMARY KEY (`paymentgateway_id`),
  INDEX `idx_processor_type` (`processor_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `rb790_paycart_usage`
--

CREATE TABLE IF NOT EXISTS `#__paycart_usage` (
  `usage_id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_type` varchar(100) NOT NULL COMMENT 'discountrule, taxrule',
  `rule_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `cartparticular_id` int(11) NOT NULL,
  `price` double(15,5) NOT NULL COMMENT ' Discount must be  -ive',
  `applied_date` datetime NOT NULL,
  `realized_date` datetime NOT NULL,
  `message` varchar(255) NOT NULL COMMENT ' for tax rate should be added in message',
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`usage_id`),
  INDEX `idx_rule_id` (`rule_id`),
  INDEX `idx_buyer_id` (`buyer_id`),
  INDEX `idx_cart_id` (`cart_id`),
  INDEX `idx_cartparticular_id` (`cartparticular_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='All rule''s usage history is available here' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- ------------------- DEFAULT VALUES ---------------------
-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_config`
--
INSERT IGNORE INTO `#__paycart_config` (`key`, `value`) VALUES
('image_extension', '.png'),
('image_maximum_upload_limit', '2'),
('image_render_url', NULL),
('image_thumb_height', '100'),
('image_thumb_width', '133'),
('image_upload_directory', NULL),
('discountrule_issuccessive', 1);

INSERT IGNORE INTO `#__paycart_productcategory_lang` (`productcategory_lang_id`, `productcategory_id`, `title`, `alias`, `lang_code`, `description`, `metadata_title`, `metadata_keywords`, `metadata_description`) VALUES
(1, 1, 'root', 'root', 'en-GB', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO `#__paycart_productcategory` (`productcategory_id`, `cover_media`, `parent_id`, `lft`, `rgt`, `level`, `status`, `created_date`, `modified_date`, `ordering`) VALUES
(1, NULL, 0, 0, 1, 0, 'published', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

