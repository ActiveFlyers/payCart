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
	protected $auto_generate_metadata = false;
	
	/**
	 * Display product
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{	
		$productId = $this->getModel()->getId();
		$product   = PaycartProduct::getInstance($productId);
		$baseAttr  = $this->input->get('pc-product-base-attribute', false);
		
		//if product is not valid 
		if(!$product){
			return false;
		}
				
		$attributes = $this->input->get('attributes', array(),'ARRAY');
		
		//collect variants
		$variants  = $product->getVariants();
		
		//if attributes are being posted then calculate and redirect to new product
		if(!empty($attributes)){
			//if there is a change in base attribute value then unset all the other post data and send 
			//new value of base attribute 
			if($baseAttr){
				$value 		= $attributes[$baseAttr];
				$attributes = array($baseAttr => $value);
			}
			
			$productId = PaycartFactory::getModel('productattributevalue')->loadProduct(array_keys($variants), $attributes);

			//PCTODO : What if product doesn't match 		
			if($productId){
				PaycartFactory::getApplication()->redirect(PaycartRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$productId));	
			}
		}
		
		// collect variants
		$variants   = $product->getVariants();
		$selectors  = array();
		$baseAttrId = 0;
		
		//collect filterable attributes
		if(count($variants) > 1){
			$productIds    = array_keys($variants); 
			$attrRecords   = $product->getSelectorAttributes($productIds);
			$totalProducts = 0;
			
			foreach($attrRecords as $key => $record){
				if($record['totalProducts'] > $totalProducts){
					$totalProducts   = $record['totalProducts'];
					$baseAttrId    	 = $key;
				}	
			}
			
			$selectors = PaycartFactory::getHelper('product')->buildSelectorAttributes($attrRecords, $baseAttrId, $variants, $product);
		}
		
		$isExist = PaycartFactory::getHelper('cart')->isProductExist($productId);
		$isAvailableInStock = PaycartFactory::getHelper('product')->isProductInStock($productId); 
		
		//set meta details
		$metaTitle       = $product->getMetadataTitle();
		$metaDescription = $product->getMetadataDescription();
		$metaKeywords    = $product->getMetadataKeywords();
		
		Rb_HelperJoomla::addDocumentMetadata($metaTitle,$metaKeywords,$metaDescription);
				
		// positions
		/* @var $helper PaycartHelperProduct */
		$helper = PaycartFactory::getHelper('product');
		$this->assign('positions', $helper->getPositions()); 	
		
		$this->assign('isAvailableInStock',$isAvailableInStock);
		$this->assign('isExistInCart', $isExist);
		$this->assign('baseAttrId',$baseAttrId);		
		$this->assign('selectors', $selectors);
		$this->assign('variants', $variants);
		$this->assign('product',$product);
		return true;
	}
}