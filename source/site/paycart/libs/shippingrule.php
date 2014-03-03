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
	protected $processor_type	= '';
	
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
	 * Gets shipping cost of package
	 * @param Array $product_list
	 * @param Int $delivery_address_id
	 * @param Array $product_details : array(product_id => array(quantity, cart_id, buyer_id, address_id, unit_price, total), ...); 
	 * @throws InvalidArgumentException if any product in $product_list does not exists in $product_details
	 * 
	 */	
	public function getPackageShippingCost($product_list, $delivery_address_id, $product_details)
	{
		// create request object
		$request 	= new PaycartShippingruleRequest();
				
		foreach($product_list as $id_product){
			if(!isset($product_details[$id_product])){
				throw new InvalidArgumentException(Rb_Text::_('COM_PAYCART_LIB_SHIPPINGRULE_PRODUCT_DETAIL_MISSING'), 404);
			}

			$request->product[$id_product] = $this->_createProductRequestObject($id_product, $product_details[$id_product]);
		}
				
		$request->config 			= $this->_createConfigRequestObject();
		$request->delivery_address 	= $this->_createAddressRequestObject($delivery_address_id);
		//IMP : Multiple warehouses are not supported yet
		//@TODO :  load origin address id from global configuration
		$origin_address_id = 0;
		$request->origin_address 	= $this->_createAddressRequestObject($origin_address_id);
		
		// @PCTODO: Rmeove this code once autoloading to response classes is done  
		$response  = new PaycartShippingruleResponse();
		$processor = $this->getProcessor();
		$response  = $processor->getPackageShippingCost($request, $response);
		
		//@ PCTODO : Trigger for gettin tax on shipping
		
		if($response->amount === false){
			// @PCTODO : Log error if required
			return array(false, false);
		}
		
		//@PCTODO : Currently assuming price_ with_tax = price_without_tax
		return array($response->cost, $response->cost);
	}
	
	protected function _createConfigRequestObject()
	{
		$config = new PaycartShippingruleRequestConfig();
		$config->dimenssion_unit  = 'INCH'; //@TODO : get from global config
		$config->weight_unit	  = 'KG';   //@TODO : get from global config
		$config->packaging_weight = $this->getPackagingWeight();
		$config->package_by		  = $this->getPackageBy(); // per item or per order
		return $config;
	}
	
	protected function _createProductRequestObject($id_product, $cart_product_details)
	{
		$product = new PaycartShippingruleRequestProduct();

		// @TODO get Product Data  with caching 
		$product->title 		= "Product";
		$product->unit_price 	= $cart_product_details['unit_price'];
		$product->quantity		= $cart_product_details['quantity'];
		$product->price			= $cart_product_details['price'];
		$product->discount		= 0; // @TODO : do when required
		$product->tax			= 0; // @TODO : do when required
		$product->total			= $cart_product_details['total'];
		
		// dimenssion & weight
		// @TODO get from product
		$product->length 		= '';
		$product->width 		= '';
		$product->height 		= '';
		$product->weight		= '';
		
		return $product;
	}
	
	protected function _createAddressRequestObject($address_id)
	{
		// delivery address
		// @TODO get from $cart_product_details['delivery_address_id']
		$address = new PaycartShippingruleRequestAddress();
		$address->line1 	= '';
		$address->line2 	= '';
		$address->city 		= '';
		$address->state 	= '';
		$address->country 	= '';
		$address->zipcode	= '';
		$address->phone 	= '';
		
		return $address;	
	}
}