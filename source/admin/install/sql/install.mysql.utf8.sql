
--
-- Database: `com_paycart`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_config`
--
CREATE TABLE IF NOT EXISTS `#__paycart_config` (
  `key` varchar(255) NOT NULL,
  `value` text,
  UNIQUE KEY `idx_key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_productattribute`
--

CREATE TABLE IF NOT EXISTS `#__paycart_productattribute` (
  `productattribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL COMMENT 'Type of attribute',
  `code` varchar(100) DEFAULT NULL,
  `filterable` tinyint(1) NOT NULL COMMENT 'Treat as a filter',
  `searchable` tinyint(1) DEFAULT '0' COMMENT 'Use for keyword search',
  `published` tinyint(1) NOT NULL DEFAULT '1',
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
  UNIQUE KEY `uni_id_lang` (`lang_code`, `productattribute_id`)
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
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `params` text COMMENT 'Include extra stuff like, tree.',
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
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `description` text,
  `metadata_title` varchar(255) DEFAULT NULL,
  `metadata_keywords` varchar(255) DEFAULT NULL,
  `metadata_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`productcategory_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `productcategory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_product`
--

CREATE TABLE IF NOT EXISTS `#__paycart_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification of product',
  `productcategory_id` int(11) DEFAULT 0,
  `type` varchar(50) NOT NULL COMMENT 'Type of Product',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `variation_of` int(11) NOT NULL DEFAULT '0' COMMENT 'This product is variation of another product. ',
  `sku` varchar(50) NOT NULL COMMENT 'Stock keeping unit',
  `price` double(15,4) NOT NULL,
  `retail_price` double(15,4) DEFAULT NULL,
  `cost_price` double(15,4) NOT NULL,
  `quantity` int(10) NOT NULL COMMENT 'Quantity of Product',
  `quantity_sold` int(10) NOT NULL COMMENT 'Sold Quantity of Product',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `cover_media` varchar(250) DEFAULT NULL,
  `stockout_limit`int NOT NULL COMMENT 'out-of-stock limit of Product',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT 'maintain hit count of products',
  `weight` decimal(12,4) DEFAULT '0.0000',
  `weight_unit` varchar(50) DEFAULT NULL,
  `height` decimal(12,4) DEFAULT '0.0000',
  `length` decimal(12,4) DEFAULT '0.0000',
  `width` decimal(12,4) DEFAULT '0.0000',
  `dimension_unit`varchar(50) DEFAULT NULL,
  `config` text COMMENT 'Store layouts',
  `config_digital` text COMMENT 'Store digital media ids',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `productcategory_id` (`productcategory_id`)
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
  `metadata_title` varchar(255) COMMENT 'Here you can store meta title.',
  `metadata_keywords` varchar(255) COMMENT 'Here you can store meta tag.',
  `metadata_description` varchar(255) COMMENT 'Here you can store meta description.',
  PRIMARY KEY (`product_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_cart`
--

CREATE TABLE IF NOT EXISTS `#__paycart_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) DEFAULT '0',
  `session_id` varchar(200) DEFAULT '',
  `invoice_id` int(11) DEFAULT '0' COMMENT 'mapped invoice id with rb_ecommerce_invoice table',
  `invoice_serial` varchar(255) DEFAULT '0' COMMENT 'order of paid invoices',
  `lang_code` char(7) NOT NULL,
  `status` enum('drafted','paid','cancelled') NOT NULL,
  `is_locked` int(4) NOT NULL DEFAULT '0' COMMENT 'after cart lock, Buyer can''t change into cart',
  `is_approved` int(4) NOT NULL DEFAULT '0' COMMENT 'Approved either by admin or on payment.',
  `is_delivered` int(4) NOT NULL DEFAULT '0' COMMENT 'cart delivered or not',
  `is_guestcheckout` int(4) NOT NULL,
  `currency` char(3) NOT NULL COMMENT 'isocode 3',
  `reversal_for` int(11) DEFAULT '0' COMMENT 'reversal of cart (parent) : When cart is reversal then new entry is created into cart and set here cart_id which is reversed  (might be cart partial refunded)',
  `ip_address` varchar(255) DEFAULT '0' COMMENT 'cart created from',
  `billing_address_id` int(11) DEFAULT '0',
  `shipping_address_id` int(11) DEFAULT '0' COMMENT 'Cart will shipp only one address',
  `secure_key` varchar(255) NOT NULL COMMENT 'used for url security',
  `created_date` datetime DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `locked_date` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'Date of either cart is checked out or reversal is created',
  `approved_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paid_date` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'Payment Completion date.',
  `cancelled_date` datetime DEFAULT '0000-00-00 00:00:00',
  `delivered_date` datetime DEFAULT '0000-00-00 00:00:00',
  `note` varchar(255) DEFAULT '' ,
  `params` text COMMENT 'Products and their quantiy store here initial',
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
  `type` varchar(100) NOT NULL COMMENT 'particular-type',  
  `unit_price` decimal(15,5) DEFAULT '0.00000',
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
  `default_address_id` int(11) DEFAULT NULL,
  `default_phone` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`buyer_id`),
  KEY `idx_default_address_id` (`default_address_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_buyeraddress`
--

CREATE TABLE IF NOT EXISTS `#__paycart_buyeraddress` (
  `buyeraddress_id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) NOT NULL,
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `to` varchar(100) NOT NULL COMMENT 'reference name',
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state_id` int(11) NOT NULL COMMENT 'State id',
  `country_id` char(3) NOT NULL COMMENT 'Country ISO code3',
  `zipcode` varchar(10) NOT NULL DEFAULT '',
  `vat_number` varchar(100) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `is_removed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`buyeraddress_id`),
  KEY `idx_md5` (`md5`),
  KEY `idx_buyer_id` (`buyer_id`),
  KEY `idx_is_removed` (`is_removed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


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
  `processor_classname` varchar(100) NOT NULL COMMENT 'processor class-name in small-case',
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
  UNIQUE KEY `uni_id_lang` (`lang_code`, `discountrule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_discountrule_x_class`
--

CREATE TABLE IF NOT EXISTS `#__paycart_discountrule_x_group` (
  `discountrule_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  KEY `idx_discountrule_id` (`discountrule_id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_type` (`type`)
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
  `published` tinyint(1) NOT NULL ,
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
  PRIMARY KEY (`country_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_state`
--

CREATE TABLE IF NOT EXISTS `#__paycart_state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `isocode` varchar(7) NOT NULL,
  `country_id` char(3) NOT NULL,
  `published` tinyint(1) NOT NULL,
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
  `state_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`state_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `state_id`)
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
  UNIQUE KEY `uni_id_lang` (`lang_code`, `taxrule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_taxrule_x_group`
--

CREATE TABLE IF NOT EXISTS `#__paycart_taxrule_x_group` (
  `taxrule_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  KEY `idx_taxrule_id` (`taxrule_id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Mapping of taxrule and groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_media`
--

CREATE TABLE IF NOT EXISTS `#__paycart_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_free` TINYINT(1) NOT NULL DEFAULT 1,
  `filename` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
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
  UNIQUE KEY `uni_id_lang` (`lang_code`, `media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_color`
--

CREATE TABLE IF NOT EXISTS `#__paycart_color` (
  `color_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_code` varchar(50) NOT NULL,
  `productattribute_id` int(11) NOT NULL,
  PRIMARY KEY (`color_id`),
  KEY `idx_productattribute_id` (`productattribute_id`)
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
  UNIQUE KEY `uni_id_lang` (`lang_code`, `color_id`)
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
  UNIQUE KEY `uni_id_lang` (`lang_code`, `productattribute_option_id`)
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
  `operator` VARCHAR(4) NOT NULL DEFAULT 'AND',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `params` text,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_paymentgateway`
--

CREATE TABLE IF NOT EXISTS `#__paycart_paymentgateway` (
  `paymentgateway_id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `processor_type` varchar(255) NOT NULL,
  `processor_config` text,
  PRIMARY KEY (`paymentgateway_id`),
  INDEX `idx_processor_type` (`processor_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_paymentgateway_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_paymentgateway_lang` (
  `paymentgateway_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_code` char(7) NOT NULL,
  `paymentgateway_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`paymentgateway_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `paymentgateway_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


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

--
-- Table structure for table `#__paycart_notification`
--

CREATE TABLE IF NOT EXISTS `#__paycart_notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `event_name` varchar(100) NOT NULL,
  `to` varchar(255) NOT NULL,
  `cc` varchar(255) NOT NULL,
  `bcc` varchar(255) NOT NULL,
  `media` varchar(255) NOT NULL COMMENT 'all attachment media store  here (In json format)',
  `params` TEXT,
  PRIMARY KEY (`notification_id`),
  INDEX `idx_event_name` (`event_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;


-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_notification_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_notification_lang` (
  `notification_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `lang_code` varchar(7) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `admin_subject` varchar(255),
  `admin_body` text,
  PRIMARY KEY (`notification_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `notification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_shippingrule`
--

CREATE TABLE IF NOT EXISTS `#__paycart_shippingrule` (
  `shippingrule_id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL,
  `delivery_grade` tinyint(1) NOT NULL COMMENT '0-9 (according to speed of shipping delivery) 9 is fastest and 0 is slowest',
  `delivery_min_days` tinyint(2) NOT NULL,
  `delivery_max_days` tinyint(2) NOT NULL,
  `packaging_weight` decimal(12,4) DEFAULT '0.0000',
  `handling_charge` double(15,5) NOT NULL,
  `processor_classname` varchar(100) NOT NULL COMMENT 'Classname of processor in small-case',
  `processor_config` text NOT NULL COMMENT 'processor configuration',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`shippingrule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__paycart_shippingrule_lang`
--

CREATE TABLE IF NOT EXISTS `#__paycart_shippingrule_lang` (
  `shippingrule_lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `shippingrule_id` int(11) NOT NULL,
  `lang_code` char(7) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL COMMENT 'Help msg for end user',
  PRIMARY KEY (`shippingrule_lang_id`),
  UNIQUE KEY `uni_id_lang` (`lang_code`, `shippingrule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_shippingrule_x_group`
--

CREATE TABLE IF NOT EXISTS `#__paycart_shippingrule_x_group` (
  `shippingrule_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  KEY `idx_shippingrule_id` (`shippingrule_id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Mapping of shippingrule and groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_shipment`
--

CREATE TABLE IF NOT EXISTS `#__paycart_shipment` (
  `shipment_id` int(11) NOT NULL AUTO_INCREMENT,
  `shippingrule_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `weight` decimal(12,4) DEFAULT '0.0000',
  `actual_shipping_cost` double(15,5) NOT NULL,
  `tracking_number` varchar(100),
  `tracking_url` varchar(255),
  `status` enum('pending','dispatched','delivered','failed') not null,
  `notes` text COMMENT 'Shipment tarcking notes for frontend users',
  `created_date` datetime NOT NULL,
  `delivered_date` datetime NOT NULL,
  `dispatched_date` datetime NOT NULL,
  `est_delivery_date` datetime NOT NULL,
  PRIMARY KEY (`shipment_id`),
  KEY `idx_shippingrule_id` (`shippingrule_id`),
  KEY `idx_cart_id` (`cart_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Shipment to be delivered' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_shipment_x_product`
--
CREATE TABLE IF NOT EXISTS `#__paycart_shipment_x_product` (
  `shipment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) Default '1',
  KEY `idx_shipment_id` (`shipment_id`),
  KEY `idx_cart_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='mapping product and shipments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_product_index`
--
CREATE TABLE IF NOT EXISTS `#__paycart_product_index` (
  `product_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`product_id`),
  FULLTEXT KEY `idx_content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- ------------------- DEFAULT VALUES ---------------------
-- --------------------------------------------------------

--
-- Dumping data for table `#__paycart_notification`
--

INSERT IGNORE INTO `#__paycart_notification` (`notification_id`, `published`, `event_name`, `to`, `cc`, `bcc`, `media`) VALUES
(1, 0, 'onpaycartcartafterlocked', '[[buyer_email]]', '', '', '{}'),
(2, 1, 'onpaycartcartafterapproved', '[[buyer_email]]', '', '', '{}'),
(3, 1, 'onpaycartcartafterpaid', '[[buyer_email]]', '', '', '{}'),
(4, 1, 'onpaycartcartafterdelivered', '[[billing_to]]', '', '', '{}'),
(5, 1, 'onpaycartshipmentafterdispatched', '[[buyer_email]]', '', '', '{}'),
(6, 1, 'onpaycartshipmentafterdelivered', '[[buyer_email]]', '', '', '{}'),
(7, 1, 'onorderurlrequest', '[[buyer_email]]', '', '', '{}'),
(8, 0, 'onpaycartshipmentafterfailed', '[[buyer_email]]', '', '', '{}');


--
-- Dumping data for table `#__paycart_notification_lang`
--

INSERT IGNORE INTO `#__paycart_notification_lang` (`notification_lang_id`, `notification_id`, `lang_code`, `subject`, `body`) VALUES
(1, 1, 'en-GB', 'Order Confirmation ', 'Thank you for placing your order with [[store_name]]\r\n\r\nThis email is just let you know your recent order.\r\n\r\n[[products_detail]]\r\n\r\n\r\n'),
(2, 2, 'en-GB', 'Order Approved Successfully', 'Your order is successfully approved. \r\n\r\n[[products_detail]]'),
(3, 3, 'en-GB', 'Order successfully Paid', 'Your payment is successfully received by [[store_name]]\r\n\r\n[[products_detail]]'),
(4, 4, 'en-GB', 'Order Successfully Delivered ', 'Your order is successfully delivered on your shipping address.\r\n\r\n[[shipping_to]],\r\n[[shipping_address]] , \r\n[[shipping_city]] , [[shipping_state]]\r\n[[shipping_country]] [[shipping_zip_code]]\r\n\r\n'),
(5, 5, 'en-GB', '', ''),
(6, 6, 'en-GB', '', ''),
(7, 7, 'en-GB', 'Order Details Request of your order (id : [[order_id]])', 'Hello [[buyer_name]], \r\n\r\nYou have requested for the order detail url of your order [[order_id]]. This email contains the order detail url from which you can track your order. \r\n\r\n[[order_url]]\r\n\r\nPlease save or bookmark this url for further tracking. Still you can request this url again anytime at our website.'),
(8, 8, 'en-GB', 'Shipment Failed', '[[products]]');



--
-- Dumping data for table `#__paycart_config`
--
INSERT IGNORE INTO `#__paycart_config` (`key`, `value`) VALUES
('catalogue_dimension_unit', 'cm'),
('catalogue_image_optimized_height', 'auto'),
('catalogue_image_optimized_width', '300'),
('catalogue_image_thumb_height', 'auto'),
('catalogue_image_thumb_width', '64'),
('catalogue_image_squared_size', '200'),
('catalogue_image_upload_size', '2'),
('catalogue_weight_unit', 'gm'),
('catalogue_allowed_files','zip,gz,gzip,rar,tar.gz,doc,docx,txt,odt,pdf,xls,xlsx,epub,pps,csv,ico,odg,odp,ods,ppt,swf,xcf,mp3,ogg,flac,mpa,wma,wav,wmv,avi,mkv,fla,flv,mp4,aac,mov,mpg,jpg,jpeg,gif,png,bmp'),
('company_address', ''),
('company_name', ''),
('cron_frequency','1800'),
('cron_run_automatic','1'),
('invoice_serial_prefix', 'order'),
('invoice_serial_number_format','[[number]]'),
('localization_currency', 'USD'),
('localization_currency_format', 'symbol'),
('localization_currency_position', 'before'),
('localization_date_format', 'Y-m-d'),
('localization_decimal_separator', '.'),
('localization_fraction_digit_count', '2'),
('product_index_limit','20');

INSERT IGNORE INTO `#__paycart_productcategory_lang` (`productcategory_lang_id`, `productcategory_id`, `title`, `alias`, `lang_code`, `description`, `metadata_title`, `metadata_keywords`, `metadata_description`) VALUES
(1, 1, 'root', 'root', 'en-GB', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO `#__paycart_productcategory` (`productcategory_id`, `cover_media`, `parent_id`, `lft`, `rgt`, `level`, `published`, `created_date`, `modified_date`) VALUES
(1, NULL, 0, 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');


--
-- Dumping data for table `#__paycart_country`
--

INSERT IGNORE INTO `#__paycart_country` (`country_id`, `isocode2`, `call_prefix`, `zip_format`, `published`, `ordering`) VALUES
('IND', 'IN', '91', 'NNNNNN', 1, 1),
('USA', 'US', '', 'NNNNN', 1, 2);

--
-- Dumping data for table `#__paycart_country_lang`
--

INSERT IGNORE INTO `#__paycart_country_lang` (`country_lang_id`, `country_id`, `lang_code`, `title`) VALUES
(1, 'IND', 'en-GB', 'India'),
(2, 'USA', 'en-GB', 'United States');

--
-- Dumping data for table `#__paycart_state`
--

INSERT IGNORE INTO `#__paycart_state` (`state_id`, `isocode`, `country_id`, `published`, `ordering`) VALUES
(1, 'AND', 'IND', 1, 1),
(2, 'ANI', 'IND', 1, 2),
(3, 'ARU', 'IND', 1, 3),
(4, 'ASS', 'IND', 1, 4),
(5, 'BIH', 'IND', 1, 5),
(6, 'CHA', 'IND', 1, 6),
(7, 'CHH', 'IND', 1, 7),
(8, 'DAD', 'IND', 1, 8),
(9, 'DAM', 'IND', 1, 9),
(10, 'DEL', 'IND', 1, 10),
(11, 'GOA', 'IND', 1, 11),
(12, 'GUJ', 'IND', 1, 12),
(13, 'HAR', 'IND', 1, 13),
(14, 'HIM', 'IND', 1, 14),
(15, 'JAM', 'IND', 1, 15),
(16, 'JHA', 'IND', 1, 16),
(17, 'KAR', 'IND', 1, 17),
(18, 'KER', 'IND', 1, 18),
(19, 'LAK', 'IND', 1, 19),
(20, 'MAD', 'IND', 1, 20),
(21, 'MAH', 'IND', 1, 21),
(22, 'MAN', 'IND', 1, 22),
(23, 'MEG', 'IND', 1, 23),
(24, 'MIZ', 'IND', 1, 24),
(25, 'NAG', 'IND', 1, 25),
(26, 'ORI', 'IND', 1, 26),
(27, 'PON', 'IND', 1, 27),
(28, 'PUN', 'IND', 1, 28),
(29, 'RAJ', 'IND', 1, 29),
(30, 'SIK', 'IND', 1, 30),
(31, 'TAM', 'IND', 1, 31),
(32, 'TRI', 'IND', 1, 32),
(33, 'UAR', 'IND', 1, 33),
(34, 'UTT', 'IND', 1, 34),
(35, 'WES', 'IND', 1, 35),
(36, 'ALA', 'USA', 1, 36),
(37, 'ALK', 'USA', 1, 37),
(38, 'ARK', 'USA', 1, 38),
(39, 'ARZ', 'USA', 1, 39),
(40, 'CAL', 'USA', 1, 40),
(41, 'CCT', 'USA', 1, 41),
(42, 'COL', 'USA', 1, 42),
(43, 'DEL', 'USA', 1, 43),
(44, 'DOC', 'USA', 1, 44),
(45, 'FLO', 'USA', 1, 45),
(46, 'GEA', 'USA', 1, 46),
(47, 'HWI', 'USA', 1, 47),
(48, 'IDA', 'USA', 1, 48),
(49, 'ILL', 'USA', 1, 49),
(50, 'IND', 'USA', 1, 50),
(51, 'IOA', 'USA', 1, 51),
(52, 'KAS', 'USA', 1, 52),
(53, 'KTY', 'USA', 1, 53),
(54, 'LOA', 'USA', 1, 54),
(55, 'MAI', 'USA', 1, 55),
(56, 'MIC', 'USA', 1, 56),
(57, 'MIN', 'USA', 1, 57),
(58, 'MIO', 'USA', 1, 58),
(59, 'MIS', 'USA', 1, 59),
(60, 'MLD', 'USA', 1, 60),
(61, 'MOT', 'USA', 1, 61),
(62, 'MSA', 'USA', 1, 62),
(63, 'NEB', 'USA', 1, 63),
(64, 'NEH', 'USA', 1, 64),
(65, 'NEJ', 'USA', 1, 65),
(66, 'NEM', 'USA', 1, 66),
(67, 'NEV', 'USA', 1, 67),
(68, 'NEY', 'USA', 1, 68),
(69, 'NOC', 'USA', 1, 69),
(70, 'NOD', 'USA', 1, 70),
(71, 'OHI', 'USA', 1, 71),
(72, 'OKL', 'USA', 1, 72),
(73, 'ORN', 'USA', 1, 73),
(74, 'PEA', 'USA', 1, 74),
(75, 'RHI', 'USA', 1, 75),
(76, 'SOC', 'USA', 1, 76),
(77, 'SOD', 'USA', 1, 77),
(78, 'TEN', 'USA', 1, 78),
(79, 'TXS', 'USA', 1, 79),
(80, 'UTA', 'USA', 1, 80),
(81, 'VIA', 'USA', 1, 81),
(82, 'VMT', 'USA', 1, 82),
(83, 'WAS', 'USA', 1, 83),
(84, 'WEV', 'USA', 1, 84),
(85, 'WIS', 'USA', 1, 85),
(86, 'WYO', 'USA', 1, 86);

--
-- Dumping data for table `#__paycart_state_lang`
--

INSERT IGNORE INTO `#__paycart_state_lang` (`state_lang_id`, `state_id`, `lang_code`, `title`) VALUES
(1, 1, 'en-GB', 'Andaman & Nicobar Islands'),
(2, 2, 'en-GB', 'Andhra Pradesh'),
(3, 3, 'en-GB', 'Arunachal Pradesh'),
(4, 4, 'en-GB', 'Assam'),
(5, 5, 'en-GB', 'Bihar'),
(6, 6, 'en-GB', 'Chandigarh'),
(7, 7, 'en-GB', 'Chhatisgarh'),
(8, 8, 'en-GB', 'Daman & Diu'),
(9, 9, 'en-GB', 'Dadra & Nagar Haveli'),
(10, 10, 'en-GB', 'Delhi'),
(11, 11, 'en-GB', 'Goa'),
(12, 12, 'en-GB', 'Gujarat'),
(13, 13, 'en-GB', 'Haryana'),
(14, 14, 'en-GB', 'Himachal Pradesh'),
(15, 15, 'en-GB', 'Jammu & Kashmir'),
(16, 16, 'en-GB', 'Jharkhand'),
(17, 17, 'en-GB', 'Karnataka'),
(18, 18, 'en-GB', 'Kerala'),
(19, 19, 'en-GB', 'Lakshadweep'),
(20, 20, 'en-GB', 'Madhya Pradesh'),
(21, 21, 'en-GB', 'Meghalaya'),
(22, 22, 'en-GB', 'Maharashtra'),
(23, 23, 'en-GB', 'Mizoram'),
(24, 24, 'en-GB', 'Manipur'),
(25, 25, 'en-GB', 'Nagaland'),
(26, 26, 'en-GB', 'Orissa'),
(27, 27, 'en-GB', 'Pondicherry'),
(28, 28, 'en-GB', 'Punjab'),
(29, 29, 'en-GB', 'Rajasthan'),
(30, 30, 'en-GB', 'Sikkim'),
(31, 31, 'en-GB', 'Tamil Nadu'),
(32, 32, 'en-GB', 'Tripura'),
(33, 33, 'en-GB', 'Uttaranchal'),
(34, 34, 'en-GB', 'Uttar Pradesh'),
(35, 35, 'en-GB', 'West Bengal'),
(36, 36, 'en-GB', 'Alaska'),
(37, 37, 'en-GB', 'Alabama'),
(38, 38, 'en-GB', 'Arkansas'),
(39, 39, 'en-GB', 'Arizona'),
(40, 40, 'en-GB', 'California'),
(41, 41, 'en-GB', 'Colorado'),
(42, 42, 'en-GB', 'Connecticut'),
(43, 43, 'en-GB', 'District Of Columbia'),
(44, 44, 'en-GB', 'Delaware'),
(45, 45, 'en-GB', 'Florida'),
(46, 46, 'en-GB', 'Georgia'),
(47, 47, 'en-GB', 'Hawaii'),
(48, 48, 'en-GB', 'Iowa'),
(49, 49, 'en-GB', 'Idaho'),
(50, 50, 'en-GB', 'Illinois'),
(51, 51, 'en-GB', 'Indiana'),
(52, 52, 'en-GB', 'Kansas'),
(53, 53, 'en-GB', 'Kentucky'),
(54, 54, 'en-GB', 'Louisiana'),
(55, 55, 'en-GB', 'Massachusetts'),
(56, 56, 'en-GB', 'Maryland'),
(57, 57, 'en-GB', 'Maine'),
(58, 58, 'en-GB', 'Michigan'),
(59, 59, 'en-GB', 'Minnesota'),
(60, 60, 'en-GB', 'Missouri'),
(61, 61, 'en-GB', 'Mississippi'),
(62, 62, 'en-GB', 'Montana'),
(63, 63, 'en-GB', 'North Carolina'),
(64, 64, 'en-GB', 'North Dakota'),
(65, 65, 'en-GB', 'Nebraska'),
(66, 66, 'en-GB', 'New Hampshire'),
(67, 67, 'en-GB', 'New Jersey'),
(68, 68, 'en-GB', 'New Mexico'),
(69, 69, 'en-GB', 'Nevada'),
(70, 70, 'en-GB', 'New York'),
(71, 71, 'en-GB', 'Ohio'),
(72, 72, 'en-GB', 'Oklahoma'),
(73, 73, 'en-GB', 'Oregon'),
(74, 74, 'en-GB', 'Pennsylvania'),
(75, 75, 'en-GB', 'Rhode Island'),
(76, 76, 'en-GB', 'South Carolina'),
(77, 77, 'en-GB', 'South Dakota'),
(78, 78, 'en-GB', 'Tennessee'),
(79, 79, 'en-GB', 'Texas'),
(80, 80, 'en-GB', 'Utah'),
(81, 81, 'en-GB', 'Virginia'),
(82, 82, 'en-GB', 'Vermont'),
(83, 83, 'en-GB', 'Washington'),
(84, 84, 'en-GB', 'Wisconsin'),
(85, 85, 'en-GB', 'West Virginia'),
(86, 86, 'en-GB', 'Wyoming');

