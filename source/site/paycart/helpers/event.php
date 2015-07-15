<?php
/**
 *@copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 *@license	GNU/GPL, see LICENSE.php
 *@package	PayCart
 *@subpackage	Pacart Form
 *@author 	mManishTrivedi 
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/**
 * 
 * Trigger all Paycart events
 *
 * @author Manish Trivedi
 *
 */
class PaycartHelperEvent extends PaycartHelper
{
    static $default_plugin_type = 'paycart';


    /**
	 * #######################################################################
	 *
     *  Listed all available triggers on cart
     *      1#. onPaycart-Cart-After-Drafted 
     *      2#. onPaycart-Cart-After-Locked
     *      3#. onPaycart-Cart-After-Approved
     *      4#. onPaycart-Cart-After-Paid
     *      5#. onPaycartCart-After-Delivered
     *      6#. onPaycart-Cart-Before-Calculate
     *      7#. onPaycart-Cart-After-Calculate
	 * #######################################################################
	 */
        
        /**
         *
         * onPaycartCart Drafted
         * @param PaycartCart $cart
         * 
         * @return void
         */
        public function onPaycartCartAfterDrafted(PaycartCart $cart)
        {
            $params     =   Array($cart);
            $event_name =   'onPaycartCartAfterDrafted';
            
            // trigger
            Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);
        }

        /**
         *
         * onPaycartCart Locked
         * @param PaycartCart $cart
         * 
         * @return void
         */
        public function onPaycartCartAfterLocked(PaycartCart $cart)
        {
            $params     =   Array($cart);
            $event_name =   'onPaycartCartAfterLocked';
            
            // trigger
            Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);
            
            //send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $cart->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($cart);
           	}
        }
        
        /**
         *
         * onPaycartCart Approved
         * @param PaycartCart $cart
         * 
         * @return void
         */
        public function onPaycartCartAfterApproved(PaycartCart $cart)
        {
            $params      =   Array($cart);
            $event_name =   'onPaycartCartAfterApproved';

            // trigger
            Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);

            //send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $cart->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($cart);
           	}

            /* @var $current_cart PaycartCart */
            $cart_id        = $cart->getId();
            
            // 1#. update product's quatity
            $cartHelper =  PaycartFactory::getHelper('cart');
            PaycartFactory::getHelper('product')
                    ->consumeProductStock($cartHelper->getCartparticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_PRODUCT));
                    
            // 2# create default Shipments
            $cart->createDefaultShipments();
        }
        
        /**
         *
         * onPaycartCart Paid
         * @param PaycartCart $cart
         * 
         * @return void
         */
        public function onPaycartCartAfterPaid(PaycartCart $cart)
        {
            $params     =   Array($cart);
            $event_name =   'onPaycartCartAfterPaid';

            //trigger
            Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);
            
           //send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $cart->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($cart);
           	}
        }
        
        /**
         *
         * onPaycartCart Delivered
         * @param PaycartCart $cart
         * 
         * @return void
         */
        public function onPaycartCartAfterDelivered(PaycartCart $cart)
        {
            $params     =   Array($cart);
            $event_name =   'onPaycartCartAfterDelivered';

            //trigger
            Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);
            
            //send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $cart->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($cart);
           	}
        }
        
		/**
         * 
         * Trigger before cart calculation begin
         * @param $params
         * 
         * @return trigger output
         */
        public function onPaycartCartBeforeCalculate(Array $params) 
        {
        	$event_name =   'onPaycartCartBeforeCalculate';
            
            // trigger
            return Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);
        }
        
        /**
         * 
         * Trigger After cart calculation begin
         * @param $params
         * 
         * @return trigger output
         */
		public function onPaycartCartAfterCalculate(Array $params) 
        {
        	$event_name =   'onPaycartCartAfterCalculate';
            
            // trigger
            return Rb_HelperPlugin::trigger($event_name, $params, self::$default_plugin_type);
        }
        
    /**
	 * #######################################################################
	 *
     *  Listed all available triggers on Shipment
     *      1#. onPaycart-Shipment-After-Dispatched
     *      2#. onPaycart-Shipment-After-Delivered
     *      3#. onPaycart-Shipment-After-Failed
	 * #######################################################################
	 */

		/**
         *
         * onPaycartShipment Dispatched
         * @param 
         *
         * @return void
         */
        public function onPaycartShipmentAfterDispatched(PaycartShipment $shipment)
        {
            $params = Array($shipment);
            $event_name = 'onPaycartShipmentAfterDispatched';
            
            Rb_HelperPlugin::trigger('onPaycartShipmentAfterDispatched', $params, self::$default_plugin_type);
            
            //send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $shipment->getCart()->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($shipment);
           	}
        }
        
		/**
         *
         * onPaycartShipment Delivered
         * @param 
         *
         * @return void
         */
        public function onPaycartShipmentAfterDelivered(PaycartShipment $shipment)
        {
            $params = Array($shipment);
            $event_name = 'onPaycartShipmentAfterDelivered';
            
            Rb_HelperPlugin::trigger('onPaycartShipmentAfterDelivered', $params, self::$default_plugin_type);
            
            $cart 		= $shipment->getCart();
            $id 		= $cart->getId();

            /* @var $cartHelper PaycartHelperCart */
            $cartHelper = PaycartFactory::getHelper('cart');
            $shipments 	= $cartHelper->getShipments($id);
            $productCartParticulars = $cartHelper->getCartParticularsData($id, Paycart::CART_PARTICULAR_TYPE_PRODUCT);
            $isCartDelivered = true;
            
            // Calculate Shipments
            $productShipments = array();
            foreach($shipments as $object){
            	foreach($object->products as $product){
            		if(!isset($productShipments[$product['product_id']])){
            			$productShipments[$product['product_id']] = array();
            		}
            
            		$productShipments[$product['product_id']][] = array('quantity' => $product['quantity'],
            				'shipment_id' => $object->shipment_id);
            	}
            }
            
            $products = array();
            foreach($productCartParticulars as $particular){
            	$products[$particular->particular_id] = PaycartProduct::getInstance($particular->particular_id);
            		
            	// if shipment is not created for any product, then do not mark the cart as delivered
            	if(!isset($productShipments[$particular->particular_id])){
            		$isCartDelivered = false;
            		break;
            	}
            	else{
            		// if all quantity of product is not consumed in shipment, then do not mark the cart as delivered
            		$quantity = 0;
            		foreach($productShipments[$product['product_id']] as $object){
            			$quantity += $object['quantity'];
            		}
            
            		if($quantity < $particular->quantity){
            			$isCartDelivered = false;
            			break;
            		}
            	}
            }
            
            // As all shipments are delivered so mark cart as delivered
            if($isCartDelivered){
            	$cart->markDelivered()->save();
            }
            
         	//send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $cart->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($shipment);
           	}
         }
         
		/**
         *
         * onPaycartShipment Failed
         * @param 
         *
         * @return void
         */
        public function onPaycartShipmentAfterFailed(PaycartShipment $shipment)
        {
            $params = Array($shipment);
            $event_name = 'onPaycartShipmentAfterFailed';
            
            Rb_HelperPlugin::trigger('onPaycartShipmentAfterFailed', $params, self::$default_plugin_type);
            
            //send notification 
            $instance = PaycartNotification::getInstanceByEventname($event_name, $shipment->getCart()->getLangCode());
            if($instance instanceof PaycartNotification){
            	$instance->sendNotification($shipment);
           	}
           	
           	// 1#. increase product's quatity on shipment failed
            $products   =  $shipment->getProducts();
            PaycartFactory::getHelper('product')
                    ->refillProductStock($products);
        }
                 
    /**
	 * #######################################################################
	 *
     *  Listed all available Paycart System triggers
     *      1#. onPaycart-Cron
	 * #######################################################################
	 */
         
		 /**
          * onPaycartCron event
		  * Actions to be performed on cron will be done from here and trigger an event as well
		  */
         public function onPaycartCron()
         {
         	PaycartFactory::getHelper('productindex')->SyncIndexing(PaycartFactory::getConfig()->get('product_index_limit'));
         	 
         	$args = array();
         	return Rb_HelperPlugin::trigger('onPaycartCron', $args , self::$default_plugin_type);
         }
         
		/**
         *
         * onPaycartImageBeforeLoad 
         * @param 
         *
         * @return void
         */
        public function onPaycartImageBeforeLoad(PaycartProduct $product)
        {
            $params = Array($product);
            $event_name = 'onPayplansImageBeforeLoad';
            $result = array();
            $result = Rb_HelperPlugin::trigger('onPaycartImageBeforeLoad', $params, self::$default_plugin_type);
            
            return $result;
            
        }
}