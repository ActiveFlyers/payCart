<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Product Html View
* @author rimjhim
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewProduct extends PaycartSiteBaseViewProduct
{	
	/**
	 * Display product
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{			
		$productId = $this->getModel()->getId();
		$product   = PaycartProduct::getInstance($productId);
		
		//if product is not valid 
		if(!$product){
			return false;
		}
		
		// collect variants
		$variants = $product->getVariants();
		$filters  = array();
		
		//collect filterable attributes
		if(count($variants) > 1){
			$productIds    = array_keys($variants); 
			$attrRecords   = $product->getFilterableAttributes(implode(',', $productIds));
			$baseAttrId    = 0;
			$totalProducts = 0;
			
			foreach($attrRecords as $key => $record){
				if($record['totalProducts'] > $totalProducts){
					$totalProducts   = $record['totalProducts'];
					$baseAttrId    	 = $key;
				}	
			}
			
			$filters = PaycartFactory::getHelper('product')->buildFilterAttributes($attrRecords, $baseAttrId, $variants, $product);
		}
				
		$this->assign('filters', $filters);
		$this->assign('variants', $variants);
		$this->assign('product',$product);
		return true;
	}
}