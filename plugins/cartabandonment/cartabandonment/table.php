<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Paycart
* @subpackage	cartabandonment
* @contact 		support@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PaycartTablecartabandonment extends Rb_Table
{
	public function __construct($tblFullName=null, $tblPrimaryKey='acymailing_id', $db=null)
	{
		static $isTableExist = false;
		if(!$isTableExist && Rb_HelperUtils::isTableExist('#__paycart_cartabandonment')==false)
		{
			$sql = 'CREATE TABLE IF NOT EXISTS `#__paycart_cartabandonment` (
						  `cartabandonment_id`	       INT NOT NULL AUTO_INCREMENT,
					 	  `published` tinyint(1),
						  `to` varchar(255) NULL,
					          `cc` varchar(255) NULL,
						  `bcc` varchar(255) NULL,
						  `when_to_email`              timestamp NULL,
  						  `params`                 TEXT,
  						   PRIMARY KEY (`cartabandonment_id`)
						)
						ENGINE = MyISAM
						DEFAULT CHARACTER SET = utf8;';
			$dbo = Rb_Factory::getDBO();
			$dbo->setQuery($sql);
			
			// For resolving caching issue of tables:
			// if this work is not done then the new table is not 
			// added to the cached table list even after successfully 
			// creation of the table and sometimes generate error
			$dbo->query();

			$sql = 'CREATE TABLE IF NOT EXISTS `#__paycart_cartabandonment_lang` (
						  `cartabandonment_lang_id`	       INT NOT NULL AUTO_INCREMENT,
						  `cartabandonment_id`	       INT NOT NULL,
						  `lang_code` varchar(7),
						  `subject` varchar(255) NULL,
					          `body` TEXT,
  						   PRIMARY KEY (`cartabandonment_lang_id`)
						)
						ENGINE = MyISAM
						DEFAULT CHARACTER SET = utf8;';
			$dbo->setQuery($sql);
			
			// For resolving caching issue of tables:
			// if this work is not done then the new table is not 
			// added to the cached table list even after successfully 
			// creation of the table and sometimes generate error
			$dbo->query();
		}
	
		return parent::__construct($tblFullName, 'cartabandonment_id', $db);
	}
 }
 
 class PaycartTablecartabandonmentlang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_cartabandonment_lang', $tblPrimaryKey='cartabandonment_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
