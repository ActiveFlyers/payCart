<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * ProductAttributeValue Table
 * @author rimjhim
 */
class PaycartTableProductAttributeValue extends PaycartTable
{
	function __construct($tblFullName='#__paycart_productattribute_value', $tblPrimaryKey='', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}

