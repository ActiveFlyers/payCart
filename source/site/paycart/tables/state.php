<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManish Trivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * State Table
 * @author manish
 *
 */
class PaycartTableState extends PaycartTable
{}


/**
 * 
 * Language specific Table
 * @author manish
 *
 */
class PaycartTableLangState extends PaycartTable
{
	function __construct($tblFullName='#__paycart_state_lang', $tblPrimaryKey='state_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}	
}