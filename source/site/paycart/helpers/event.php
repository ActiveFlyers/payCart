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
    static $plugin_type = 'paycart';


    /**
	 * #######################################################################
	 *
     *  Listed all available triggers on cart
     *      1#. OnCart Drafted 
     *      2#. OnCart Locked
     *      3#. OnCart Approved
     *      4#. OnCart Paid
     *      5#. OnCart Delivered
	 * #######################################################################
	 */
        
        /**
         *
         * Oncart Drafted
         * @param Array  $event_params  { previos_object, current_object }
         * 
         * @return void
         */
        public function onCartAfterDrafted(Array $event_params)
        {
            Rb_HelperPlugin::trigger('onPaycartCartAfterDrafted', $event_params, self::$plugin_type);
        }

        /**
         *
         * Oncart Locked
         * @param Array  $event_params  { previos_object, current_object }
         * 
         * @return void
         */
        public function onCartAfterLocked(Array $event_params)
        {
            Rb_HelperPlugin::trigger('onPaycartCartAfterLocked', $event_params, self::$plugin_type);
        }
        
        /**
         *
         * Oncart Approved
         * @param Array  $event_params  { previos_object, current_object }
         * 
         * @return void
         */
        public function onCartAfterApproved(Array $event_params)
        {
            Rb_HelperPlugin::trigger('onPaycartCartAfterApproved', $event_params, self::$plugin_type);
            
            /* @var $current_cart PaycartCart */
            $current_cart   = $event_params['current_object'];
            $cart_id        = $current_cart->getId();
            
            // 1#. update product's quatity
            $cartHelper =  PaycartFactory::getHelper('cart');
            PaycartFactory::getHelper('product')
                    ->updateProductStock($cartHelper->getCartparticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_PRODUCT));
        }
        
        /**
         *
         * Oncart Paid
         * @param Array  $event_params  { previos_object, current_object }
         * 
         * @return void
         */
        public function onCartAfterPaid(Array $event_params)
        {
            Rb_HelperPlugin::trigger('onPaycartCartAfterPaid', $event_params, self::$plugin_type);    
        }
        
        /**
         *
         * Oncart Delivered
         * @param Array  $event_params  { previos_object, current_object }
         * 
         * @return void
         */
        public function onCartAfterDelivered(Array $event_params)
        {
            Rb_HelperPlugin::trigger('onPaycartCartAfterDelivered', $event_params, self::$plugin_type);
        }
        
}