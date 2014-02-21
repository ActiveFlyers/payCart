<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @author		mManishTrivedi
* @contact		support+paycart@readybytes.in
* 
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/**
 * 
 * Product Controller
 * @author Manish
 *
 */

class PaycartSiteControllerCatlogue extends PaycartController 
{
	// display catlogue
	function display($cachable = false, $urlparams = array())
	{
		return parent::display();
	}
	
	function getModel()
	{
		return null;
	}
}