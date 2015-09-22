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
			PaycartFactory::getModel('product')->updateHits($productId);
			return parent::display();
		}
		
		// if product doesn't exist or unpublished then show error
		JError::raiseError(404, "Product was not found.");
	}
	
	/**
     * Serve teaser file to download
     */
	function serveTeaser()
	{
		$productId = $this->input->get('product_id',0,'INT');
		$teaser    = $this->input->get('file_id',0,'STRING');
		if(!$productId || !$teaser){
			JError::raiseError(404,"File doesn't exist");
		}
		
		$teaserId = (int)str_ireplace('file-', '', base64_decode($teaser));
		
		if(!$teaserId){
			JError::raiseError(404,"File doesn't exist");
		}
		
		$media = PaycartMedia::getInstance($teaserId);
		
		/* @var $helper PaycartHelperMedia */
		$helper = PaycartFactory::getHelper('media');
		$helper->download($media, false);		
	}
	
	public function addToCart()
	{
		$productId = $this->input->get('product_id',0,'INT');
		$quantity  = $this->input->get('quantity',1,'INT');
		
		return PaycartFactory::getHelper('cart')->addProduct($productId, $quantity);
			return true;
	}
}
