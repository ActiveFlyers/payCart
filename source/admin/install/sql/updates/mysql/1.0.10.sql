-- --------------------------------------------------------
-- 
-- Update Database version: 1.0.10
-- 
-- --------------------------------------------------------

ALTER TABLE `#__paycart_cart` ADD `invoice_serial` varchar(255) DEFAULT '0' COMMENT 'order of paid invoices' AFTER `invoice_id`;

INSERT INTO `#__paycart_config`(`key`, `value`) VALUES ('invoice_serial_number_format','[[number]]');

