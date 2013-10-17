<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * 
 * Index Table
 * @author manish
 *
 */
class PaycartTableProductIndex extends PaycartTable
{
	//change primary key
	public function __construct($tblFullName=null, $tblPrimaryKey='product_id', $db=null)
	{
		//call parent to build the table object
		return parent::__construct( $tblFullName, $tblPrimaryKey, $db);
	}
	
}
