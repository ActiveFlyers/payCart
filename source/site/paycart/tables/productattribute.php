<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * product attribute Table
 * @author rimjhim
 */
class PaycartTableProductAttribute extends PaycartTable
{
	function __construct($tblFullName='#__paycart_productattribute', $tblPrimaryKey='productattribute_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}

/** 
 * product attribute language Table
 * @author rimjhim
 */
class PaycartTableLangProductAttribute extends PaycartTable
{
	function __construct($tblFullName='#__paycart_productattribute_lang', $tblPrimaryKey='productattribute_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
