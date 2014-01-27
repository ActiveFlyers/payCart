<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Base class for Paycart Shipping Rule processor
 * Contains medthod of calculation whcih might be common for all Shipping Rule Processors.
 * These methods can also be overrided in Processor also.
 * It contains stateless design, so do not assume that X property will b avaialbe on X object, all should be passed as arguments.
 * 
 * @abstract
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
abstract class PaycartShippingruleProcessor
{
	
	protected function _requestConfightml(PaycartShippingruleRequest $request)
	{
		$config 	= $this->getConfig();
		$location	= $this->getLocation();
		
		ob_start();
		
		include_once $location.'/tmpl/config.php';
		
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	public function getConfig($key = null, $defaultValue = null)
	{
		if($key === null){
			return $this->config;
		}
		
		if(isset($this->config->$key)){
			return $this->config->$key;
		}
		
		return $defaultValue;
	}
	
	abstract public function getPackageShippingCost(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response);
}