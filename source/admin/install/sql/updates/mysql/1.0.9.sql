-- --------------------------------------------------------

--
-- Update Database version: 1.0.9
--

-- --------------------------------------------------------
ALTER TABLE `#__paycart_product` 
 ADD COLUMN `retail_price` double(15,4) DEFAULT NULL AFTER `price`,
 ADD COLUMN `cost_price` double(15,4) NOT NULL AFTER `retail_price`,
 ADD COLUMN `config_digital` text COMMENT 'Store digital media ids' AFTER `config`;


INSERT IGNORE INTO `#__paycart_config` (`key`, `value`) VALUES
('catalogue_allowed_files','zip,gz,gzip,rar,tar.gz,doc,docx,txt,odt,pdf,xls,xlsx,epub,pps,csv,ico,odg,odp,ods,ppt,swf,xcf,mp3,ogg,flac,mpa,wma,wav,wmv,avi,mkv,fla,flv,mp4,aac,mov,mpg,jpg,jpeg,gif,png,bmp');


