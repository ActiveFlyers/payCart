<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class PaycartShipment extends PaycartLib
{
	protected $shipment_id 		    = 0;
	protected $shippingrule_id      = 0;
	protected $cart_id  		    = 0;
	protected $weight			    = 0;
	protected $status			    = '';
	protected $actual_shipping_cost = 0;
	protected $tracking_number		= '';
	protected $created_date		    = '';
	protected $delivered_date	    = '';
	protected $returned_date        = '';
	
	protected $_products			= array();
	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{
		$this->shipment_id 		    = 0;
		$this->shippingrule_id      = 0;
		$this->cart_id  		    = 0;
		$this->weight			    = 0;
		$this->status			    = '';
		$this->actual_shipping_cost = 0;
		$this->tracking_number	    = '';
		$this->created_date		    = Rb_Date::getInstance();
		$this->delivered_date	    = Rb_Date::getInstance();
		$this->returned_date        = Rb_Date::getInstance();
		
		$this->_products			= array();
		
		return $this;
	}
	
	public function bind($data, $ignore)
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		parent::bind($data, $ignore);
		
		$this->_products = (isset($data['products']))?$data['products']:array(); 
		
		return $this;
	}
	
	/**
	 * 
	 * PaycartShipment Instance
	 * @param  $id, existing Product id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartProduct lib instance
	 */
	public static function getInstance($id = 0, $data = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('shipment', $id, $data);
	}
	
	public function getProducts()
	{
		return $this->_products;
	}
	
	/**
	 * Overridden it so that 'products' can be set (required on model)
	 */
	public function toDatabase()
	{
		$data = parent::toDatabase();
		$data['_products'] = $this->_products;
		
		return $data;
	}
}