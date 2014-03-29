	CREATE TABLE IF NOT EXISTS `jos_rb_ecommerce_invoice` (
	  `invoice_id`  INTEGER PRIMARY KEY AUTOINCREMENT,
	  `object_id` INTEGER NOT NULL DEFAULT '0',
	  `object_type` TEXT DEFAULT NULL,
	  `buyer_id` INTEGER NOT NULL,
	  `master_invoice_id` INTEGER NOT NULL DEFAULT '0',
	  `currency` TEXT DEFAULT NULL,
	  `sequence` INTEGER DEFAULT '0',
	  `serial` TEXT NOT NULL ,
	  `status` INTEGER NOT NULL DEFAULT '0',
	  `title` TEXT DEFAULT NULL,
	  `expiration_type` TEXT NOT NULL,
	  `time_price` text NOT NULL,
	  `recurrence_count` INTEGER NOT NULL DEFAULT '0',
	  `subtotal` REAL DEFAULT 0.00000,
	  `total` REAL NOT NULL DEFAULT 0.00000,
	  `notes` text,
	  `params` text,
	  `created_date` TEXT NOT NULL,
	  `modified_date` TEXT NOT NULL,
	  `paid_date` TEXT DEFAULT NULL,
	  `refund_date` TEXT DEFAULT NULL,
	  `due_date` TEXT DEFAULT NULL,
	  `issue_date` TEXT DEFAULT NULL,
	  `processor_type` TEXT DEFAULT NULL,
	  `processor_config` text,
	  `processor_data` text

	) ;

CREATE INDEX `idx_buyer_id` ON `jos_rb_ecommerce_invoice` (`buyer_id`);
CREATE INDEX `idx_object_id` ON `jos_rb_ecommerce_invoice` (`object_id`);


CREATE TABLE IF NOT EXISTS `jos_rb_ecommerce_transaction` (
  `transaction_id`  INTEGER PRIMARY KEY AUTOINCREMENT,
  `buyer_id` INTEGER DEFAULT '0',
  `invoice_id` INTEGER DEFAULT '0',
  `processor_type` TEXT DEFAULT NULL,
  `gateway_txn_id` TEXT DEFAULT NULL,
  `gateway_parent_txn` TEXT DEFAULT NULL,
  `gateway_subscr_id` TEXT DEFAULT NULL,
  `amount` REAL DEFAULT '0.00000',
  `payment_status` TEXT DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `created_date` TEXT NOT NULL,
  `params` TEXT,
  `signature` TEXT DEFAULT NULL
);

CREATE INDEX `idx_user_id` ON `jos_rb_ecommerce_transaction` (`buyer_id`);
CREATE INDEX `idx_invoice_id` ON `jos_rb_ecommerce_transaction` (`invoice_id`);
CREATE INDEX `idx_signature` ON `jos_rb_ecommerce_transaction` (`signature`);



CREATE TABLE IF NOT EXISTS `jos_rb_ecommerce_modifier` (
  `modifier_id` INTEGER NOT NULL PRIMARY KEY  AUTOINCREMENT,
  `buyer_id` INTEGER NOT NULL,
  `invoice_id` INTEGER DEFAULT NULL,
  `amount` REAL DEFAULT '0.00000',
  `value` REAL DEFAULT NULL,
  `object_type`   TEXT DEFAULT NULL,
  `object_id`     TEXT DEFAULT NULL,
  `message`       TEXT,
  `percentage`    INTEGER NOT NULL DEFAULT '1',
  `serial`        INTEGER NOT NULL DEFAULT '0',
  `frequency`     TEXT DEFAULT NULL,
  `created_date`  TEXT DEFAULT NULL,
  `consumed_date` TEXT DEFAULT NULL
);


-- CREATE INDEX `idx_invoice_id` ON `jos_rb_ecommerce_modifier` (`invoice_id`);
-- CREATE INDEX `idx_buyer_id` ON `jos_rb_ecommerce_modifier` (`buyer_id`);
-- CREATE INDEX `idx_object_type` ON `jos_rb_ecommerce_modifier` (`object_type`);
-- CREATE INDEX `idx_object_id` ON `jos_rb_ecommerce_modifier` (`object_id`);


CREATE TABLE IF NOT EXISTS `jos_rb_ecommerce_currency` (
  `currency_id` TEXT NOT NULL PRIMARY KEY,
  `title` TEXT DEFAULT NULL,
  `published` INTEGER DEFAULT 1,
  `params` text NULL,
  `symbol` TEXT DEFAULT NULL
) ; 

CREATE TABLE IF NOT EXISTS `jos_rb_ecommerce_country` (
  `country_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL,
  `isocode2` TEXT DEFAULT NULL,
  `isocode3` TEXT DEFAULT NULL,
  `isocode3n` INTEGER DEFAULT NULL
); 


