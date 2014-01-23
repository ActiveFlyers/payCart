

--
-- Table structure for table `jos_paycart_config`
--

CREATE TABLE `jos_paycart_config` (
  `config_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `key` TEXT NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL

);


--
-- Table structure for table `jos_paycart_attribute`
--

CREATE TABLE `jos_paycart_attribute` (
  `attribute_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL DEFAULT '',
  `type` INTEGER NOT NULL ,
  `default` TEXT DEFAULT NULL,
  `class` TEXT DEFAULT NULL,
  `filterable` INTEGER NOT NULL DEFAULT '0',
  `searchable` INTEGER NOT NULL DEFAULT '0',
  `published` INTEGER NOT NULL DEFAULT '1',
  `visible` INTEGER NOT NULL DEFAULT '0',
  `ordering` INTEGER DEFAULT '0',
  `params` TEXT DEFAULT NULL,
  `xml` TEXT DEFAULT NULL
);

--
-- Table structure for table `jos_paycart_attributevalue`
--
CREATE TABLE `jos_paycart_attributevalue` (
  `attributevalue_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `product_id` INTEGER NOT NULL,
  `attribute_id` INTEGER NOT NULL,
  `value` TEXT DEFAULT NULL,
  `order` INTEGER NOT NULL
);

--
-- Table structure for table `jos_paycart_product`
--
CREATE TABLE `jos_paycart_product` (
  `product_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title`  TEXT NOT NULL,
  `alias`  TEXT NOT NULL UNIQUE,
  `published`INTEGER NOT NULL DEFAULT '1',
  `type` INTEGER DEFAULT NULL ,
  `amount` REAL NOT NULL DEFAULT '0.00000' ,
  `quantity` INTEGER NOT NULL DEFAULT '0' ,
  `file` TEXT  DEFAULT NULL,
  `sku` TEXT NOT NULL UNIQUE,
  `variation_of` INTEGER NOT NULL DEFAULT '0' ,
  `category_id` INTEGER DEFAULT '0',
  `params` TEXT DEFAULT NULL,
  `cover_media` TEXT  DEFAULT NULL,
  `teaser` TEXT DEFAULT NULL ,
  `publish_up` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INTEGER NOT NULL DEFAULT '0',
  `ordering` INTEGER NOT NULL DEFAULT '0',
  `featured` INTEGER NOT NULL DEFAULT '0',
  `description` TEXT DEFAULT NULL,
  `hits` INTEGER NOT NULL DEFAULT '0',
  `meta_data` TEXT DEFAULT NULL
) ;

-------------------------------------------------------------
-- Table structure for table `jos_paycart_productcategory`
-------------------------------------------------------------

CREATE TABLE `jos_paycart_productcategory` (
  `productcategory_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `cover_media` TEXT DEFAULT NULL,
  `parent` INTEGER  DEFAULT '0',
  `status` TEXT DEFAULT '0',
  `created_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` INTEGER NOT NULL DEFAULT '0'
) ;


-------------------------------------------------------------
-- Table structure for table `jos_paycart_productcategory_lang`
-------------------------------------------------------------

CREATE TABLE `jos_paycart_productcategory_lang` (
  `productcategory_lang_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `productcategory_id` INTEGER NOT NULL DEFAULT '0',  
  `title` TEXT NOT NULL,
  `alias` TEXT NOT NULL UNIQUE,
  `lang_code` TEXT DEFAULT NULL,    
  `description` TEXT DEFAULT NULL,
  `metadata_title` TEXT DEFAULT NULL,
  `metadata_keywords` TEXT DEFAULT NULL,
  `metadata_description` TEXT DEFAULT NULL
) ;

--
-- Table structure for table `jos_paycart_indexer`
--

CREATE TABLE `jos_paycart_productindex` (
  `product_id` INTEGER PRIMARY KEY,
  `content` TEXT
);

--
-- Table structure for table `jos_paycart_cart`
--
CREATE TABLE `jos_paycart_cart` (
  `cart_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `buyer_id` INTEGER DEFAULT '0',
  `address_id` INTEGER DEFAULT 0,
  `subtotal` REAL NOT NULL DEFAULT '0.00000' ,
  `total` REAL NOT NULL DEFAULT '0.00000' ,
  `modifiers` TEXT,
  `currency` TEXT DEFAULT NULL,
  `status` INTEGER DEFAULT '0',
  `created_date` TEXT NOT NULL,
  `modified_date` TEXT NOT NULL,
  `checkout_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `paid_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `complete_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `cancellation_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `refund_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `params` TEXT DEFAULT NULL
) ;

--
-- Table structure for table `jos_paycart_cartparticulars`
--
CREATE TABLE `jos_paycart_cartparticulars` (
  `cartparticulars_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `cart_id` INTEGER DEFAULT '0',
  `buyer_id` INTEGER DEFAULT '0',
  `product_id` INTEGER DEFAULT '0',
  `title` TEXT DEFAULT NULL,
  `quantity` INTEGER DEFAULT '0',
  `unit_cost` REAL NOT NULL DEFAULT '0.00000' ,
  `tax` REAL NOT NULL DEFAULT '0.00000' ,
  `discount` REAL NOT NULL DEFAULT '0.00000' ,
  `price` REAL NOT NULL DEFAULT '0.00000' ,
  `shipment_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `reversal_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `delivery_date` TEXT DEFAULT '0000-00-00 00:00:00',
  `params` TEXT COMMENT 'Include extra stuff like, Notes.'
) ;

