<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @author		rimjhim
* @contact		support+paycart@readybytes.in
* 
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/**
 * 
 * Productcategory Controller
 * @author rimjhim
 *
 */

class PaycartSiteControllerProductCategory extends PaycartController 
{
	// display category
	function display($cachable = false, $urlparams = array())
	{
		$catId = $this->getModel()->getId();
		
		$record = PaycartFactory::getModel('productcategory')->loadRecords(array('productcategory_id' => $catId , 'published' => 1));
		
		if(empty($catId) || (isset($record[$catId]) && !empty($record[$catId]))){
			return parent::display();
		}
		
		// if category doesn't exist or unpublished then show error
		JError::raiseError(404, "Category was not found.");
	}
	
}