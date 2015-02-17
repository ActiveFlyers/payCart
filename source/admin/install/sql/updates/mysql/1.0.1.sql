-- --------------------------------------------------------

--
-- Update Database version: 1.0.1
--

-- --------------------------------------------------------
ALTER TABLE `#__paycart_product` ADD COLUMN `quantity_sold` int(10) NOT NULL AFTER `quantity`;

ALTER TABLE `#__paycart_productcategory` DROP COLUMN `ordering`;
