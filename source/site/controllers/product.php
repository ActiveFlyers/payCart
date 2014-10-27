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

class PaycartSiteControllerProduct extends PaycartController 
{
	// display product
	function display($cachable = false, $urlparams = array())
	{
		$productId = $this->getModel()->getId();
		
		$record = PaycartFactory::getModel('product')->loadRecords(array('product_id' => $productId , 'published' => 1));
		
		if(isset($record[$productId]) && !empty($record[$productId])){
			return parent::display();
		}
		
		// if product doesn't exist or unpublished then show error
		JError::raiseError(404, "Product was not found.");
	}
}
