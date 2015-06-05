-- --------------------------------------------------------

--
-- Update Database version: 1.0.6
--

-- --------------------------------------------------------
INSERT INTO `#__paycart_notification` (`notification_id`, `published`, `event_name`, `to`, `cc`, `bcc`, `media`) VALUES
(8, 0, 'onpaycartshipmentafterfailed', '[[buyer_email]]', '', '', '{}');

INSERT INTO `#__paycart_notification_lang` (`notification_id`, `lang_code`, `subject`, `body`) VALUES
(8, 'en-GB', 'Shipment Failed', '[[products]]');

-- Add notes column in shipment table
ALTER TABLE `#__paycart_shipment` ADD COLUMN `notes` text AFTER `status`;

-- Add notes column in group table
ALTER TABLE `#__paycart_group` ADD `operator` VARCHAR(4) NOT NULL DEFAULT 'AND' AFTER `type`;
