<?php
/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @contact		suppor+paycart@readybytes.in
 * 
 */


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// If file is already included
if ( defined( 'PAYCART_API_DEFINED' ) ) {
	return;	
}

//mark API already loaded
define('PAYCART_API_DEFINED', true);

// load paycart
include_once 'includes.php';


class PaycartAPI
{
	/**
	 * Invoke to get all available published categories
	 * @param INT $parentId :    
	 * 
	 * @return Array of stdclass, which conatin category data
	 */
 	static public function getCategories($categoryFilters = null, $lang = null, $order_by = "lft", $order_in = 'ASC', $limit = null)
	{
		if(!isset($categoryFilters['published'])){
			$categoryFilters['published'] = 1;
		}		
		
		$catModel = PaycartFactory::getInstance('productcategory', 'model');
		// @PCTODO : TEMP SOLUTION
		$catModel->set('_query', null);
		
		$pre_order_by = $catModel->getState('filter_order', null);
		$pre_order_in = $catModel->getState('filter_order_Dir', null);
		
		$catModel->setState('filter_order', $order_by);		
		$catModel->setState('filter_order_Dir', $order_in);		
		
		if($lang == null){
			$lang = paycart_getCurrentLanguage();
			$catModel->lang_code = $lang;
		}
		
		$query = $catModel->getQuery();
		if($limit != null){
			$query->limit($limit);
		}
		$catModel->set('_query', $query);
		
		$categories = $catModel->loadRecords($categoryFilters);
		
		$catModel->setState('filter_order', $pre_order_by);
		$catModel->setState('filter_order_Dir', $pre_order_in);
		
		$catModel->lang_code = null;
		return $categories;
	}

	static public function getCategory($id, $instanceRequired = false)
	{
		$category =  PaycartProductcategory::getInstance($id);
		
		if(!$instanceRequired){
			return $category->toArray();
		}
		
		return $category;
	}
	
	/**
	 * Invoke to get current cart
	 * 
	 * @return Paycartcart if cart exits otherwise false
	 */
	static public function getCurrentCart()
	{
		return PaycartFactory::getHelper('cart')->getCurrentCart();
	}
	
	static public function getProducts($filters = null, $lang = null, $order_by = "ordering", $order_in = 'ASC', $limit = null)
	{
		if(!isset($filters['published'])){
			$categoryFilters['published'] = 1;
		}		
		
		$pModel = PaycartFactory::getInstance('product', 'model');
		// @PCTODO : TEMP SOLUTION
		$pModel->set('_query', null);
		
		$pre_order_by = $pModel->getState('filter_order', null);
		$pre_order_in = $pModel->getState('filter_order_Dir', null);
		
		$pModel->setState('filter_order', $order_by);		
		$pModel->setState('filter_order_Dir', $order_in);		
		
		if($lang == null){
			$lang = paycart_getCurrentLanguage();
			$pModel->lang_code = $lang;
		}
		
		$query = $pModel->getQuery();
		if($limit != null){
			$query->limit($limit);
		}
		$pModel->set('_query', $query);
		
		$pModel->setState('filter_order', $pre_order_by);
		$pModel->setState('filter_order_Dir', $pre_order_in);
		
		$products = $pModel->loadRecords($filters);
		
		$pModel->lang_code = null;
		return $products;
	}
}