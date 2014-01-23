<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Category Table
 * @author Manish Trivedi
 */
class PaycartTableProductcategory extends PaycartTable
{}


class PaycartTableProductcategorylang extends PaycartTable
{
	
	function __construct($tblFullName='#__paycart_productcategory_lang', $tblPrimaryKey='productcategory_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}
