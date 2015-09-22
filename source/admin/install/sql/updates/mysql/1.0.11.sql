INSERT INTO `#__paycart_notification` (`notification_id`, `published`, `event_name`, `to`, `cc`, `bcc`, `media`) VALUES
(9, 0, 'onPaycartCartAfterCancel', '[[buyer_email]]', '', '', '{}'),
(10, 0, 'onPaycartCartAfterRefund', '[[buyer_email]]', '', '', '{}');


INSERT INTO `#__paycart_notification_lang` (`notification_id`, `lang_code`, `subject`, `body`) VALUES
(9, 'en-GB', ' Order Cancel', '[[products]]'),
(10, 'en-GB', 'Refund Processed', '[[products]]');


ALTER TABLE `#__paycart_group` ADD `is_refunded` int(4) NOT NULL DEFAULT '0' COMMENT 'amount refunded or not' AFTER `is_delivered`,
				   `refunded_date` datetime DEFAULT '0000-00-00 00:00:00' AFTER `delivered_date`;
