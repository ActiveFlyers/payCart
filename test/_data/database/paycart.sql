

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

--
-- Table structure for table `jos_paycart_category`
--

CREATE TABLE `jos_paycart_category` (
  `category_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL,
  `alias` TEXT NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `cover_image` TEXT DEFAULT NULL,
  `parent` INTEGER  DEFAULT '0',
  `ordering` INTEGER NOT NULL DEFAULT '0',
  `published` INTEGER DEFAULT '0',
  `params` TEXT DEFAULT NULL,
  `created_by` INTEGER NOT NULL DEFAULT '0',
  `created_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00'
) ;

--
-- Table structure for table `jos_paycart_indexer`
--

CREATE TABLE `jos_paycart_indexer` (
  `indexer_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `product_id` INTEGER NOT NULL UNIQUE,
  `content` TEXT
);

--
-- Table structure for table `jos_paycart_filter`
--

CREATE TABLE `jos_paycart_filter` (
  `filter_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `product_id` INTEGER NOT NULL UNIQUE
);
