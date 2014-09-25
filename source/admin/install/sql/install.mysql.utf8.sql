
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
  `published` tinyint(1) NOT NULL DEFAULT '1',
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
  PRIMARY KEY (`productcategory_lang_id`)
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
  `variation_of` int(11) NOT NULL DEFAULT '0' COMMENT 'This product is variation of another product. ',
  `sku` varchar(50) NOT NULL COMMENT 'Stock keeping unit',
  `price` double(15,4) NOT NULL,
  `quantity` int(10) NOT NULL COMMENT 'Quantity of Product',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `cover_media` varchar(250) DEFAULT NULL,
  `stockout_limit`int NOT NULL COMMENT 'out-of-stock limit of Product',
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
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) DEFAULT '0',
  `session_id` varchar(200) DEFAULT '',
  `invoice_id` int(11) DEFAULT '0' COMMENT 'mapped invoice id with rb_ecommerce_invoice table',
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
  `md5` varchar(32) NOT NULL,
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
  KEY `idx_md5` (`md5`),
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
  `filename` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
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
  KEY `lang_code` (`lang_code`,`paymentgateway_id`)
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
  PRIMARY KEY (`notification_lang_id`),
  INDEX `idx_notification_id` (`notification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__paycart_shippingrule`
--

CREATE TABLE IF NOT EXISTS `#__paycart_shippingrule` (
  `shippingrule_id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL,
  `grade` tinyint(1) NOT NULL COMMENT '0-9 (according to speed of shipping delivery) 9 is fastest and 0 is slowest',
  `min_days` tinyint(2) NOT NULL,
  `max_days` tinyint(2) NOT NULL,
  `packaging_weight` decimal(12,4) DEFAULT '0.0000',
  `handling_charge` double(15,5) NOT NULL,
  `tracking_url` text NOT NULL COMMENT 'Common url related to shipping method that will be used to tracks shipments',
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
  KEY `idx_shippingrule_id` (`shippingrule_id`),
  KEY `idx_lang_code` (`lang_code`)
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
  KEY `idx_shippingrule_id` (`shippingrule_id`),
  KEY `idx_lang_code` (`lang_code`)
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
  `created_date` datetime NOT NULL,
  `delivered_date` datetime NOT NULL,
  `dispatched_date` datetime NOT NULL,
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
(6, 1, 'onpaycartshipmentafterdelivered', '[[buyer_email]]', '', '', '');


--
-- Dumping data for table `#__paycart_notification_lang`
--

INSERT IGNORE INTO `#__paycart_notification_lang` (`notification_lang_id`, `notification_id`, `lang_code`, `subject`, `body`) VALUES
(1, 1, 'en-GB', 'Order Confirmation ', 'Thank you for placing your order with [[store_name]]\r\n\r\nThis email is just let you know your recent order.\r\n\r\n[[products_detail]]\r\n\r\n\r\n'),
(2, 2, 'en-GB', 'Order Approved Successfully', 'Your order is successfully approved. \r\n\r\n[[products_detail]]'),
(3, 3, 'en-GB', 'Order successfully Paid', 'Your payment is successfully received by [[store_name]]\r\n\r\n[[products_detail]]'),
(4, 4, 'en-GB', 'Order Successfully Delivered ', 'Your order is successfully delivered on your shipping address.\r\n\r\n[[shipping_to]],\r\n[[shipping_address]] , \r\n[[shipping_city]] , [[shipping_state]]\r\n[[shipping_country]] [[shipping_zip_code]]\r\n\r\n'),
(5, 5, 'en-GB', '', ''),
(6, 6, 'en-GB', '', '');


--
-- Dumping data for table `#__paycart_config`
--
INSERT IGNORE INTO `#__paycart_config` (`key`, `value`) VALUES
('catalogue_dimension_unit', 'm'),
('catalogue_image_optimized_height', 'auto'),
('catalogue_image_optimized_width', '300'),
('catalogue_image_thumb_height', 'auto'),
('catalogue_image_thumb_width', '64'),
('catalogue_image_squared_size', '200'),
('catalogue_image_upload_size', '2'),
('catalogue_weight_unit', 'kg'),
('company_address', ''),
('company_name', ''),
('invoice_serial_prefix', 'paycart'),
('localization_currency', 'USD'),
('localization_currency_format', 'symbol'),
('localization_currency_position', 'before'),
('localization_date_format', '%Y-%m-%d'),
('localization_decimal_separator', '.'),
('localization_fraction_digit_count', '2');

INSERT IGNORE INTO `#__paycart_productcategory_lang` (`productcategory_lang_id`, `productcategory_id`, `title`, `alias`, `lang_code`, `description`, `metadata_title`, `metadata_keywords`, `metadata_description`) VALUES
(1, 1, 'root', 'root', 'en-GB', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO `#__paycart_productcategory` (`productcategory_id`, `cover_media`, `parent_id`, `lft`, `rgt`, `level`, `published`, `created_date`, `modified_date`, `ordering`) VALUES
(1, NULL, 0, 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

