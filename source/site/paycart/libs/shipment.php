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
	protected $tracking_url			= '';
	protected $created_date		    = '';
	protected $delivered_date	    = '';
	protected $dispatched_date        = '';
	
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
		$this->tracking_url			= '';
		$this->created_date		    = Rb_Date::getInstance();
		$this->delivered_date	    = Rb_Date::getInstance();
		$this->dispatched_date      = Rb_Date::getInstance();
		
		$this->_products			= array();
		
		return $this;
	}
	
	public function bind($data, $ignore=array())
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
		$products = PaycartFactory::getModel('shipment')->loadRecords(array('shipment_id' => $this->getId()));
		$record   = !empty($products)?array_shift($products)->products:array();
		
		return $record;
	}
	
	public function getTrackingNumber()
	{
		return $this->tracking_number;
	}
	
	public function getTrackingUrl()
	{
		return $this->tracking_url;
	}
	
	public function getCart($requireInstance = true)
	{
		if(!$requireInstance){
			return $this->cart_id;
		}
		
		return PaycartCart::getInstance($this->cart_id);
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
	
	/**
     *
     * @param PaycartShipment $previousObject
     * @return boolean or id
     */
	protected function _save($previousObject)
	{
		$triggerName = false;
		$prevStatus  = (!empty($previousObject))? $previousObject->status : '';
		$newStatus	 = $this->status;
		
		if( $prevStatus != $newStatus && $newStatus == Paycart::STATUS_SHIPMENT_DISPATCHED){
	  		$triggerName = Paycart::STATUS_SHIPMENT_DISPATCHED;
	  		$this->markDispatched();
		}
		
		if( $prevStatus != $newStatus && $newStatus == Paycart::STATUS_SHIPMENT_DELIVERED){
	  		$triggerName = Paycart::STATUS_SHIPMENT_DELIVERED;
  		  	$this->markDelivered();  	
		}
		
		$prevShipping = (!empty($previousObject))? $previousObject->shippingrule_id : '';
		
		//add tracking url only if it is not set
		//PCTODO :: #258 do not allow to change shipping method once shipment is saved
		if($prevShipping != $this->shippingrule_id || empty($this->tracking_url)){
			$this->tracking_url = PaycartShippingrule::getInstance($this->shippingrule_id)->getProcessor()->getTrackingUrl();
		}
		
		// save to data to table
        $id =  parent::_save($previousObject);
            
        //if save was not complete, then id will be null, do not trigger after save
        if(!$id){
            return false;
        }

        // correct the id, for new records required
        $this->setId($id);

        //save $this to static cache, so that if someone tries to create instance in between the save process
        //then proper object would be returned 
        if(!$previousObject){
           self::setInstance($this);			
        }
	 
		if($triggerName){            
	       /*  @var $event_helper PaycartHelperEvent   */
	       $event_helper = PaycartFactory::getHelper('event');
	        
	       $triggerFunction  = 'onPaycartShipmentAfter'.ucfirst($triggerName); 
	         
	       $event_helper->$triggerFunction($this);
		}
		  
		return $id;
	}
	
	/**
	 * Mark shipment delivered if it is not 
	 */
	public function markDelivered()
	{
		$this->status            =   Paycart::STATUS_SHIPMENT_DELIVERED;
        $this->delivered_date    =   Rb_Date::getInstance();
	}
	
	/**
	 * Mark shipment dispatched if it is not 
	 */
	public function markDispatched()
	{
		$this->status            =   Paycart::STATUS_SHIPMENT_DISPATCHED;
        $this->dispatched_date   =   Rb_Date::getInstance();
	}
}