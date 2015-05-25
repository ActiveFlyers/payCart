-- --------------------------------------------------------

--
-- Update Database version: 1.0.6
--

-- --------------------------------------------------------
INSERT INTO `#__paycart_notification` (`notification_id`, `published`, `event_name`, `to`, `cc`, `bcc`, `media`) VALUES
(8, 0, 'onpaycartshipmentafterfailed', '[[buyer_email]]', '', '', '{}');

INSERT INTO `#__paycart_notification_lang` (`notification_lang_id`, `notification_id`, `lang_code`, `subject`, `body`) VALUES
(8, 8, 'en-GB', 'Shipment Failed', '[[products]]');
