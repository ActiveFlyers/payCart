<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Product
* @subpackage       Productcategory
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Product Group Rule
 *
 * @author Rimjhim Jain
 */
class PaycartGroupruleProductCategory extends PaycartGrouprule
{	
	public function isApplicable($product_id)
	{
		$categoryAssignment = $this->config->get('category_assignment', 'any');
		
		if('any' == $categoryAssignment){
			return true;
		}			
		
		
		$productCategory_id = PaycartProduct::getInstance($product_id)->getProductCategory();
		
		$config_category    = $this->config->get('category', array());		
		$common_categories  = array_intersect(array($productCategory_id), $config_category);
		
		if('selected' == $categoryAssignment){
			if(count($common_categories) > 0){
				return true;
			}
			
			return false;
		}
		
		if('except' == $categoryAssignment){
			if(count($common_categories) > 0){
				return false;
			}
			
			return true;
		}

		return false;		
	}
	
	/**
	 * Gets the html and js script call of parameteres 
	 * @return array() Array of Html and JavaScript functions to be called
	 */
	public function getConfigHtml($namePrefix = '')
	{
		$idPrefix = str_replace(array('[', ']'), '', $namePrefix);
		
		$config 	= $this->config->toArray();
		
		ob_start();
		include dirname(__FILE__).'/tmpl/config.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		$scripts 	= array();
		static $scriptAdded = false;
		if(!$scriptAdded){			
			$scripts[] 	 = 'paycart.jQuery("select.paycart-grouprule-product-category").chosen({disable_search_threshold : 10, allow_single_deselect : true });';
			$scriptAdded = true;
		}
		
		return array($contents, $scripts);
	}
}
