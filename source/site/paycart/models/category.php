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
 * Category Model
 */
class PaycartModelCategory extends PaycartModel
{
	var $filterMatchOpeartor = Array(
									'alias' => array('LIKE')
									);
}

class PaycartModelformCategory extends PaycartModelform { }