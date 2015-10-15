-- --------------------------------------------------------

--
-- Update Database version: 1.0.11
--

-- --------------------------------------------------------
ALTER TABLE `#__paycart_notification` ADD `params` TEXT AFTER `media`;

ALTER TABLE `#__paycart_notification_lang` 
 ADD COLUMN `admin_subject` varchar(255) AFTER `body`,
 ADD COLUMN `admin_body` TEXT AFTER `admin_subject`;
