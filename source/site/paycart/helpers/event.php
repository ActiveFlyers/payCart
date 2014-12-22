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
     *      1#. onPaycartCart Drafted 
     *      2#. onPaycartCart Locked
     *      3#. onPaycartCart Approved
     *      4#. onPaycartCart Paid
     *      5#. onPaycartCart Delivered
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
                    ->updateProductStock($cartHelper->getCartparticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_PRODUCT));
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
            $instance = PaycartNotification::getInstanceByEventname($event_name, $cart->getLangCode());
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
            
            $cart 			 = $shipment->getCart();
            $shipments 		 = PaycartFactory::getHelper('cart')->getShipments($cart->getId());
            $isCartDelivered = true;
            
            foreach ($shipments as $data) {
            	if($data->status != Paycart::STATUS_SHIPMENT_DELIVERED){
            		$isCartDelivered = false;
            		break;
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
          * onPaycartCron event
		  * Actions to be performed on cron will be done from here and trigger an event as well
		  */
         public function onPaycartCron()
         {
         	PaycartFactory::getHelper('productindex')->SyncIndexing(PaycartFactory::getConfig()->get('product_index_limit'));
         	 
         	$args = array();
         	return Rb_HelperPlugin::trigger('onPaycartCron', $args , self::$default_plugin_type);
         }
}