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
 * Lib for Shipping Rule
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartShippingrule extends PaycartLib 
{	
	protected $shippingrule_id	= 0;
	protected $processor_classname	= '';
	
	/**
	 * @var Rb_Registry
	 */
	protected $processor_config = null;
	
	/**
	 * @var PaycartHelperShippingRule
	 */
	protected $_helper = null;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_helper = PaycartFactory::getHelper('shippingrule');
	}
	
	/**
	 * @return PaycartShippingRule
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('Shippingrule', $id, $bindData);
	}
	
	public function reset() 
	{	
		$this->shippingrule_id	= 0;
		$this->processor_classname	= '';
		$this->processor_config = new Rb_Registry();
		
		return $this;
	}
	
	public function getConfigHtml()
	{
		$processor = $this->_helper->getProcessor('flatrate');
		return $processor->request('confightml', new PaycartShippingruleRequest())->html;		
	}
	
	public function getOrdering()
	{
		return $this->ordering;
	}
	
	public function getGrade()
	{
		return $this->grade;
	}	
	
	/**
	 * @return PaycartShippingruleProcessor
	 */
	public function getProcessor()
	{
		$processor = PaycartFactory::getProcessor(paycart::PROCESSOR_TYPE_SHIPPINGRULE, $this->processor_classname);
		$processor->processor_config = $this->getProcessorConfig();
		$processor->rule_config 	 = $this->getRuleconfigRequestObject();
		$processor->global_config 	 = $this->getGlobalconfigRequestObject();
		return $processor;
	}
	
	/**
	 * Get processor config
	 */
	function getProcessorConfig()
	{		
		return $this->processor_config->toObject();
	}
	
	/**
	 * Gets shipping cost of package
	 * @param Array $product_list
	 * @param Int $delivery_address_id
	 * @param Array $product_details : array(product_id => array(quantity, cart_id, buyer_id, address_id, unit_price, total), ...); 
	 * @throws InvalidArgumentException if any product in $product_list does not exists in $product_details
	 * 
	 */	
	public function getPackageShippingCost($product_list, $delivery_address_id, $product_details)
	{		
		$helperRequest 			= PaycartFactory::getHelper('request');
		/* @var $helperRequest PaycartHelperRequest */	
			
		// create request object
		$request 	= new PaycartShippingruleRequest();
				
		foreach($product_list as $id_product){
			if(!isset($product_details[$id_product])){
				throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_LIB_SHIPPINGRULE_PRODUCT_DETAIL_MISSING'), 404);
			}

			$request->cartparticulars[$id_product] = $helperRequest->getCartparticularObject($product_details[$id_product]);
		}

		//IMP : Multiple warehouses are not supported yet
		//@TODO :  load origin address id from global configuration
		$origin_address_id = 0;
		$request->origin_address 	= $helperRequest->getBuyeraddressObject($origin_address_id);
		$request->delivery_address 	= $helperRequest->getBuyeraddressObject($delivery_address_id);
		
		// get processor instance and set some parameters
		$processor = $this->getProcessor();
		$processor->global_config  	  = $this->getGlobalconfigRequestObject();
		$processor->rule_config  	  = $this->getRuleconfigRequestObject();
		$processor->processor_config  = $this->getProcessorConfig();
		
		$response  = new PaycartShippingruleResponse();
		$response  = $processor->getPackageShippingCost($request, $response);
		
		//@ PCTODO : Trigger for gettin tax on shipping
		
		if($response->amount === false){
			// @PCTODO : Log error if required
			return array(false, false);
		}
		
		//@PCTODO : Currently assuming price_ with_tax = price_without_tax
		return array($response->amount, $response->amount);
	}
	
	public function getGlobalconfigRequestObject()
	{
		$config = new PaycartShippingruleRequestGlobalconfig();
		$config->dimenssion_unit  = 'INCH'; //@TODO : get from global config
		$config->weight_unit	  = 'KG';   //@TODO : get from global config
		return $config;
	}
	
	public function getRuleconfigRequestObject()
	{
		$config = new PaycartShippingruleRequestRuleconfig();
		$config->packaging_weight = $this->getPackagingWeight();
		$config->package_by		  = $this->getPackageBy(); // per item or per order
		return $config;
	}
}