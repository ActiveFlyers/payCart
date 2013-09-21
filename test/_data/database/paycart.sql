--
-- Table structure for table `jos_paycart_attribute`
--

CREATE TABLE `jos_paycart_attribute` (
  `attribute_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL DEFAULT '',
  `type` TEXT NOT NULL ,
  `default` TEXT NOT NULL DEFAULT '',
  `class` TEXT NOT NULL DEFAULT '',
  `searchable` INTEGER NOT NULL DEFAULT '0',
  `published` INTEGER NOT NULL DEFAULT '1',
  `visible` INTEGER NOT NULL DEFAULT '0',
  `ordering` INTEGER DEFAULT '0',
  `params` TEXT NOT NULL DEFAULT '',
  `xml` TEXT NOT NULL DEFAULT ''
);

--
-- Table structure for table `jos_paycart_attributevalue`
--
CREATE TABLE `jos_paycart_attributevalue` (
  `attributevalue_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `product_id` INTEGER NOT NULL,
  `attribute_id` INTEGER NOT NULL,
  `value` TEXT NOT NULL DEFAULT '',
  `order` INTEGER NOT NULL
);

--
-- Table structure for table `jos_paycart_product`
--
CREATE TABLE `jos_paycart_product` (
  `product_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title`  INTEGER NOT NULL,
  `alias`  INTEGER NOT NULL,
  `published`INTEGER NOT NULL DEFAULT '1',
  `type` INTEGER DEFAULT NULL ,
  `amount` REAL NOT NULL DEFAULT '0.00000' ,
  `quantity` INTEGER NOT NULL DEFAULT '0' ,
  `file` TEXT  DEFAULT NULL,
  `sku` TEXT NOT NULL ,
  `variation_of` INTEGER NOT NULL DEFAULT '0' ,
  `category_id` INTEGER DEFAULT '0',
  `params` TEXT NOT NULL DEFAULT '',
  `cover_media` TEXT  DEFAULT '',
  `teaser` TEXT NOT NULL DEFAULT '',
  `publish_up` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INTEGER NOT NULL DEFAULT '0',
  `ordering` INTEGER NOT NULL DEFAULT '0',
  `featured` INTEGER NOT NULL DEFAULT '0',
  `description` TEXT NOT NULL DEFAULT '',
  `hits` INTEGER NOT NULL DEFAULT '0',
  `meta_data` TEXT NOT NULL DEFAULT ''
) ;

--
-- Table structure for table `jos_paycart_category`
--

CREATE TABLE `jos_paycart_category` (
  `category_id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL,
  `alias` TEXT DEFAULT NULL,
  `description` TEXT,
  `cover_image` TEXT DEFAULT NULL,
  `parent` INTEGER  DEFAULT '0',
  `ordering` INTEGER NOT NULL DEFAULT '0',
  `published` INTEGER DEFAULT '0',
  `params` TEXT,
  `created_by` INTEGER NOT NULL DEFAULT '0',
  `created_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` TEXT NOT NULL DEFAULT '0000-00-00 00:00:00'
) ;

