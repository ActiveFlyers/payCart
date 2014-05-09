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
 * color Table
 * @author rimjhim
 */
class PaycartTableColor extends PaycartTable
{
	function __construct($tblFullName='#__paycart_color', $tblPrimaryKey='color_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}

/** 
 * color language Table
 * @author rimjhim
 */
class PaycartTableLangColor extends PaycartTable
{
	function __construct($tblFullName='#__paycart_color_lang', $tblPrimaryKey='color_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}