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
            
            // send notification after trigger
            $this->sendnotification($event_name, $cart);
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

            // send notification after trigger
            $this->sendnotification($event_name, $cart);
                        
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
            
            // send notification after trigger
            $this->sendnotification($event_name, $cart);
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
            
            // send notification after trigger
            $this->sendnotification($event_name, $cart);
        }
        
        
        /**
         * Invoke to send notification 
         * @param type $event_name, Available notification find on this event-name 
         * @param type $lib_object
         * @return boolean 
         */
        public function sendnotification($event_name, $lib_object)
        {
            $notification_objectes = PaycartNotification::getInstanceByEventname(strtolower($event_name));
            
            foreach ($notification_objectes  as $notification ) {
                $notification->sendNotification($lib_object);
            }
            
            return true;
            
        }
}