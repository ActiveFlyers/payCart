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
 * Productcategory Model
 * @author manish
 *
 */
class PaycartModelProductcategory extends PaycartModel
{}

/**
 * 
 * Productcategory Lang Model
 * @author manish
 *
 */
class PaycartModellangProductcategory extends PaycartModel
{
	protected $uniqueColumns = Array('alias');
}

class PaycartModelformProductCategory extends PaycartModelform { }