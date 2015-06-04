<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Product
* @subpackage       Product
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Product Group Rule
 *
 * @author Garima Agal
 */
class PaycartGroupruleProduct extends PaycartGrouprule
{	
	public function isApplicable($product_id)
	{	
		$productAssignment = $this->config->get('products_assignment', 'selected');
		
		$config_products   = $this->config->get('products', array());
		
		$product  		   = is_array($product_id)?$product_id:array($product_id);
		$common_products   = array_intersect($product, $config_products);
		
		if('selected' == $productAssignment){
			if(count($common_products) > 0){
				return true;
			}
			
			return false;
		}
		
		if('except' == $productAssignment){
			if(count($common_products) > 0){
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
			$scripts[] 	 = 'paycart.jQuery("select.pc-chosen").chosen({disable_search_threshold : 10, allow_single_deselect : true, search_contains:true });';
			$scriptAdded = true;
		}
		
		return array($contents, $scripts);
	}
}
