<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Item Table
 * @author Team Readybytes
 */
class PaycartTableProduct extends PaycartTable
{
	
}

class PaycartTableProductlang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_product_lang', $tblPrimaryKey='product_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
