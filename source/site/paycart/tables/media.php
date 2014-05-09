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
 * media Table
 * @author rimjhim
 */
class PaycartTableMedia extends PaycartTable
{
	function __construct($tblFullName='#__paycart_media', $tblPrimaryKey='media_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}

/** 
 * media language Table
 * @author rimjhim
 */
class PaycartTableLangMedia extends PaycartTable
{
	function __construct($tblFullName='#__paycart_media_lang', $tblPrimaryKey='media_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}