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
	/**
	 * @var PaycartShippingruleRequestGlobalconfig
	 */
	public $global_config = null;
	
	/**
	 * @var PaycartShippingruleRequestRuleconfig
	 */
	public $rule_config = null;
	
	/**
	 * @var stdclass
	 */
	public $processor_config = null;
	
	protected function _requestConfightml(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response)
	{
		$config 	= $this->getConfig();
		$location	= $this->getLocation();
		
		ob_start();
		
		include_once $location.'/tmpl/config.php';
		
		$content = ob_get_contents();
		ob_end_clean();
		
		$response->configHtml = $content;
		return true;
	}
	
	public function getConfig($key = null, $defaultValue = null)
	{
		if($key === null){
			return $this->processor_config;
		}
		
		if(isset($this->processor_config->$key)){
			return $this->processor_config->$key;
		}
		
		return $defaultValue;
	}
	
	public function getLocation()
	{
		return $this->location;
	}
	
	abstract public function getPackageShippingCost(PaycartShippingruleRequest $request, PaycartShippingruleResponse $response);
}