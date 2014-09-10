<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact	support+paycart@readybytes.in
 * @author      mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Token Helper
 * @author manish
 *
 */
class PaycartHelperToken extends PaycartHelper
{
    
    /**
     * Invoke to replace tokens from text 
     * @param type $text
     * @param type $token
     * @param type $start_pattern
     * @param type $last_pattern 
     * 
     * @return string
     */
    public function replaceTokens($text, $token , $start_pattern='/\[\[', $last_pattern = '\]\]/')
    {
        // iterate token and replace with their values
        foreach ($token as $key => $value) {
            $text =  preg_replace($start_pattern.$key.$last_pattern , $value, $text);
        }
        
        return $text;
    }


    /**
     * Invoke to get all relative object get from cart
     * @param PaycartCart $cart
     * 
     * @return stdClass
     */
    public function getCartRelativeObjects(PaycartCart $cart  ) 
    {
        $relative_objects = new stdClass();
        
        // fetch all relative objects
        $relative_objects->buyer                = $cart->getBuyer(true);
        $relative_objects->billing_address      = $cart->getBillingAddress(true);
        $relative_objects->shipping_address     = $cart->getShippingAddress(true);
        $relative_objects->config               = PaycartFactory::getConfig();
        $relative_objects->cart                 = $cart;
        
        $relative_objects->product_particular_list =  $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
        
        return $relative_objects;
    }
    
    
    /**
     * Invoke to get billing token with their values
     * @param PaycartBuyeraddress $billing_address 
     * 
     * @return Array
     */
    public function getBillingToken(PaycartBuyeraddress $billing_address ) 
    {
       $tokens =  Array();
        
        // cart specific
        $tokens['billing_to']              =   $billing_address->getTo();
        $tokens['billing_address']         =   $billing_address->getAddress();
        $tokens['billing_phone1']          =   $billing_address->getPhone1();
        $tokens['billing_phone2']          =   $billing_address->getPhone2();
        $tokens['billing_zip_code']        =   $billing_address->getZipcode();
        $tokens['billing_vat_number']      =   $billing_address->getVatnumber();
        
        return $tokens;
    }
    
    
    /**
     * Invoke to get Shipping token with their values
     * @param PaycartBuyeraddress $shipping_address
     *
     * @return Array
     */
    public function getshippingToken(PaycartBuyeraddress $shipping_address ) 
    {
       $tokens =  Array();
        
        // cart specific
        $tokens['shipping_to']              =   $shipping_address->getTo();
        $tokens['shipping_address']         =   $shipping_address->getAddress();
        $tokens['shipping_phone1']          =   $shipping_address->getPhone1();
        $tokens['shipping_phone2']          =   $shipping_address->getPhone2();
        $tokens['shipping_zip_code']        =   $shipping_address->getZipcode();
        $tokens['shipping_vat_number']      =   $shipping_address->getVatnumber();
        
        return $tokens;
    }
    
    /**
     * Invoke to get cart token with their values
     * @param PaycartCart $cart 
     *
     * @return Array
     */
    public function getCartToken(PaycartCart $cart ) 
    {
       $tokens =  Array();
        
        // cart specific
        $tokens['cart_total']              =   $cart->getTotal();
        $tokens['cart_product_count']      =   count($cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT));
        $tokens['cart_created_date']       =   $cart->getCreatedDate();
        // @PCTOD :: date formating here
        $tokens['cart_paid_date']          =   $cart->getPaidDate();
        $tokens['cart_link']               =   $cart->getLink();
        
        return $tokens;
    }
    
    /**
     * Invoke to get buyer token with their values
     * @param PaycartBuyer $buyer
     * 
     * @return Array 
     */
    public function getBuyerToken(PaycartBuyer $buyer)
    {
       $tokens =  Array();
       
       $tokens['buyer_email']      =   $buyer->getEmail();
       $tokens['buyer_name']       =   $buyer->getRealName();
        
       return $tokens;
    }
    
    /**
     * Invoke to get Config token with their values
     * @param stdClass $config
     * @return Array 
     */
    public function getConfigToken($config)
    {
       $tokens =  Array();
       
       //@PCTODO :: origin address, Company logo
       
       return $tokens;
    }
    
    
    /**
     * Invoke to get Product tokens
     * @param Array $product_particulars
     * 
     * @return array 
     */
    public function getProductToken(Array $product_particulars)    
    {
        $tokens =  Array();
        
        $products   =   Array();
        foreach ($product_particulars as $product_id ) {
            $products[$product_id] = PaycartProduct::getInstance($product_id);
        }
        
        // @FIXME :: create a layout to render all product details
        
        $tokens['product_details'] = ' ITS PENDING :-)'; 
        
        return $tokens;
    }
    
}