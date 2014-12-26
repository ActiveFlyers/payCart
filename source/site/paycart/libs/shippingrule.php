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
	protected $shippingrule_id     	= 0;
	protected $published      		= 1;
	protected $delivery_grade	    = 0;
	protected $delivery_min_days	= 0;
	protected $delivery_max_days	= 0;
	protected $handling_charge		= 0;
	protected $packaging_weight		= 0;
	protected $processor_classname	= '';
	protected $processor_config		= '';
	protected $created_date			= null;
	protected $modified_date		= null;
	protected $ordering				= 0;
		
	// language specific
	protected $shippingrule_lang_id = 0;
	protected $lang_code 			= '';
	protected $message				= '';
	protected $title	      		= '';
	protected $description    		= '';
	
	// others
	protected $_buyergroups			= array();
	protected $_productgroups		= array();
	protected $_cartgroups			= array();
	
	/**
	 * @var PaycartHelperShippingRule
	 */
	protected $_helper = null;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// IMP :check for class existance
		// 		if class is not loaded alread then it will autoload the class
		// 		We have done this because other request classes are dependent on it 
		if(!class_exists('PaycartShippingruleRequest', true)){
			throw new Exception('Class PaycartShippingruleRequest not found');
		}	
		
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
		$this->shippingrule_id 		= 0;
		$this->title				= '';
		$this->published			= 1;
		$this->description			= '';
		$this->delivery_grade		= 0;
		$this->delivery_min_days	= 0;
		$this->delivery_max_days	= 0;
		$this->handling_charge		= 0;
		$this->packaging_weight		= 0;
		$this->processor_classname	= '';
		$this->processor_config		= new Rb_Registry();
		$this->created_date			= new Rb_date();
		$this->modified_date		= new Rb_date();
		$this->ordering				= 0;
		
		$this->shippingrule_lang_id	= 0;
		$this->lang_code			= PaycartFactory::getPCDefaultLanguageCode();
		$this->message				= '';
		
		$this->_buyergroups			= array();
		$this->_productgroups		= array();
		$this->_cartgroups			= array();
		
		return $this;
	}
	
	public function getOrdering()
	{
		return $this->ordering;
	}
	
	public function getDeliveryGrade()
	{
		return $this->delivery_grade;
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
	public function getPackageShippingCost($product_list, $delivery_md5_address, $product_details)
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
		$origin_address 			= PaycartFactory::getConfig()->get('localization_origin_address');
		$request->origin_address 	= $helperRequest->getBuyeraddressObject(PaycartBuyeraddress::getInstance(0,$origin_address));
		
		$delivery_address = PaycartFactory::getHelper('shippingrule')->getAddressObject($delivery_md5_address);
		$request->delivery_address 	= $helperRequest->getBuyeraddressObject(PaycartBuyeraddress::getInstance(0,$delivery_address));
		
		// get processor instance and set some parameters
		$processor = $this->getProcessor();
		$processor->global_config  	  = $this->getGlobalconfigRequestObject();
		$processor->rule_config  	  = $this->getRuleconfigRequestObject();
		$processor->processor_config  = $this->getProcessorConfig();
		
		$response  = new PaycartShippingruleResponse();
		$response  = $processor->getPackageShippingCost($request, $response);
				
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
		$config->dimension_unit  = PaycartFactory::getConfig()->get('catalogue_dimension_unit');
		$config->weight_unit	 = PaycartFactory::getConfig()->get('catalogue_weight_unit');
		$config->origin_address  = PaycartFactory::getConfig()->get('localization_origin_address');
		return $config;
	}
	
	public function getRuleconfigRequestObject()
	{
		$config = new PaycartShippingruleRequestRuleconfig();
		$config->packaging_weight = $this->packaging_weight;
		$config->handling_charge  = $this->handling_charge;
		return $config;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getProcessorConfigHtml($namePrefix)
	{
		$response = $this->getResponseObject();
		$this->getProcessor()->getConfigHtml(new PaycartShippingruleRequest, $response, $namePrefix);
		return $response->configHtml;
	}
	
	/**
	 * 
	 * create response object
	 * 
	 * @return PaycartDiscountRuleResponse object
	 */
	function getResponseObject()
	{
		return new PaycartShippingruleResponse();
	}
	
	public function toArray()
	{
		$data = parent::toArray();

		$data['_buyergroups'] 	= $this->_buyergroups;
		$data['_productgroups'] = $this->_productgroups;
		$data['_cartgroups'] 	= $this->_cartgroups;

		return $data;
	}
	
	function bind($data, $ignore = Array()) 
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
				
		parent::bind($data, $ignore);		
		
		if(!isset($data['_buyergroups'])) {
			$this->_buyergroups = $this->_getGroups(Paycart::GROUPRULE_TYPE_BUYER);
		}
		else{
			$this->_buyergroups = $data['_buyergroups'];
		}
		
		if(!isset($data['_productgroups'])) {
			$this->_productgroups = $this->_getGroups(Paycart::GROUPRULE_TYPE_PRODUCT);
		}
		else{
			$this->_productgroups = $data['_productgroups'];
		}
		
		if(!isset($data['_cartgroups'])) {
			$this->_cartgroups = $this->_getGroups(Paycart::GROUPRULE_TYPE_CART);
		}
		else{
			$this->_cartgroups = $data['_cartgroups'];
		}	
		
		return $this;
	}	
	
	protected function _getGroups($type)
	{
		if(!$this->getId()){
			return array();
		}
		
		return $this->getModel()->getGroups($this->getId(), $type);		
	}
	
	protected function _save($previousObject)
	{
		$id = parent::_save($previousObject);
		
		// if save fail
		if (!$id) { 
			return false;
		}
		
		$model = $this->getModel();
		$model->saveGroups($id, Paycart::GROUPRULE_TYPE_BUYER, $this->_buyergroups);
		$model->saveGroups($id, Paycart::GROUPRULE_TYPE_PRODUCT, $this->_productgroups);
		$model->saveGroups($id, Paycart::GROUPRULE_TYPE_CART, $this->_cartgroups);
		
		return $id;
	}	
	
	function getPackagingWeight()
	{
		return $this->packaging_weight;
	}
	
	function getDeliveryMinDays()
	{
		return $this->delivery_min_days;
	}
	
	function getDeliveryMaxDays()
	{
		return $this->delivery_max_days;
	}
}