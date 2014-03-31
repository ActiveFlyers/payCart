

--
-- Table structure for table `jos_paycart_config`
--

CREATE TABLE `jos_paycart_config` (
  `config_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `key` TEXT NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `jos_paycart_productattribute`
--

CREATE TABLE `jos_paycart_productattribute` (
  `productattribute_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `type` TEXT NOT NULL,
  `css_class` TEXT DEFAULT NULL,
  `filterable` TEXT NOT NULL,
  `searchable` TEXT,
  `status` text, --enum('published','invisible','unpublished','trashed') NOT NULL ,
  `config` TEXT,
  `ordering` INTEGER 
);

-- --------------------------------------------------------
--
-- Table structure for table `jos_paycart_productattribute_lang`
--

CREATE TABLE `jos_paycart_productattribute_lang` (
  `productattribute_lang_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `productattribute_id` INTEGER NOT NULL,
  `lang_code` INTEGER NOT NULL,
  `title` TEXT NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `jos_paycart_productattribute_value`
--

CREATE TABLE `jos_paycart_productattribute_value` (
  `product_id` INTEGER NOT NULL,
  `productattribute_id` INTEGER NOT NULL,
  `productattribute_value` INTEGER NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `jos_paycart_product`
--

CREATE TABLE `jos_paycart_product` (
  `product_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `type` TEXT NOT NULL ,
  `productcategory_id` INTEGER DEFAULT 0,
  `status` text, --enum('published','invisible','unpublished','trashed') NOT NULL,
  `variation_of` INTEGER NOT NULL ,
  `sku` TEXT NOT NULL,
  `price` REAL NOT NULL,
  `quantity` int(10) NOT NULL,
  `featured` INTEGER NOT NULL ,
  `cover_media` TEXT DEFAULT NULL,
  `stockout_limit`int NOT NULL,
  `weight` REAL DEFAULT '0.0000',
  `weight_unit` TEXT DEFAULT NULL,
  `height` REAL DEFAULT '0.0000',
  `length` REAL DEFAULT '0.0000',
  `depth` REAL DEFAULT '0.0000',
  `dimension_unit`TEXT DEFAULT NULL,
  `config` TEXT ,
  `created_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` INTEGER NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------
--
-- Table structure for table `jos_paycart_product_lang`
--

CREATE TABLE `jos_paycart_product_lang` (
  `product_lang_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `product_id` INTEGER NOT NULL,
  `lang_code` INTEGER NOT NULL,
  `title` TEXT NOT NULL ,
  `alias` TEXT NOT NULL ,
  `description` text,
  `teaser` TEXT ,
  `metadata_title` TEXT ,
  `metadata_keywords` TEXT ,
  `metadata_description` TEXT 
);


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

-- --------------------------------------------------------
--
-- Table structure for table `jos_paycart_productattribute_option`
--

CREATE TABLE `jos_paycart_productattribute_option` (
  `productattribute_option_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `productattribute_id` INTEGER NOT NULL,
  `option_ordering` INTEGER NOT NULL
);

-- --------------------------------------------------------
--
-- Table structure for table `jos_paycart_productattribute_option_lang`
--

CREATE TABLE `jos_paycart_productattribute_option_lang` (
  `productattribute_option_lang_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `productattribute_option_id` INTEGER NOT NULL,
  `lang_code` INTEGER NOT NULL,
  `title` TEXT NOT NULL
);
