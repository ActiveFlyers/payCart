<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Paycart
* @subpackage	Acymailing
* @contact 		support@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PaycartTableAcymailing extends Rb_Table
{
	public function __construct($tblFullName=null, $tblPrimaryKey='acymailing_id', $db=null)
	{
		static $isTableExist = false;
		if(!$isTableExist && Rb_HelperUtils::isTableExist('#__paycart_acymailing')==false)
		{
			$sql = 'CREATE TABLE IF NOT EXISTS `#__paycart_acymailing` (
						  `acymailing_id`	       INT NOT NULL AUTO_INCREMENT,
						  `object_id`              INT NOT NULL,
						  `type`	               varchar(255) NULL, 
  						  `acymailing_groups`	   VARCHAR(255),
  						  `params`                 TEXT,
  						   PRIMARY KEY (`acymailing_id`)
						)
						ENGINE = MyISAM
						DEFAULT CHARACTER SET = utf8;';
			$dbo = Rb_Factory::getDBO();
			$dbo->setQuery($sql);
		
			$dbo->query();
		}
	
		return parent::__construct($tblFullName, 'acymailing_id', $db);
	}
 }