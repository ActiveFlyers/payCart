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
    static $_tokens = Array();
    
    /**
     *
     * @var PaycartHelperFormat
     */
    protected $_formatter;


    public function __construct() 
    {
        $this->buildTokens();
        $this->_formatter = PaycartFactory::getHelper('format');
    }
    
    /**
     * Invoke to get List of tokens
     * @param string $event_name
     * @return Array
     */
    public function getTokenList($event_name = null)  
    {
        $event_specific_tokens  = static::$_tokens;
        
        switch(strtolower($event_name)) 
        {
            case 'onpaycartcartafterlocked':
                    unset($event_specific_tokens['shipment']);
                break;
            
            case 'onpaycartcartafterapproved':
                    unset($event_specific_tokens['shipment']);
                break;
            
            case 'onpaycartcartafterpaid':
                    unset($event_specific_tokens['shipment']);
                break;
            
            case 'onpaycartcartafterdelivered':
		    		unset($event_specific_tokens['shipment']);
                break;
            
            case 'onpaycartshipmentafterdispatched':         
            case 'onpaycartshipmentafterdelivered':
            case 'onpaycartorderurlrequest' :    
            	break;
        }
        
        return $event_specific_tokens;
    }
    
    private function buildTokens() 
    {
        if (!empty(static::$_tokens)) {
            return true;
        }
        
        //buyer specific
        static::$_tokens['buyer'] =
                Array('buyer_email' , 'buyer_name', 'buyer_orders', 'buyer_account');
                    
        // billing specific
        static::$_tokens['billing'] =
                Array(  'billing_to',   'billing_address',  'billing_phone',
                        'billing_zip_code','billing_vat_number',
                        'billing_country', 'billing_state','billing_city'
                      );
        // shipping specific
        static::$_tokens['shipping'] =   
                Array(  'shipping_to',      'shipping_address',  'shipping_phone',
                        'shipping_zip_code','shipping_vat_number',
                        'shipping_country', 'shipping_state','shipping_city'
                      );
        
        // cart specific
        static::$_tokens['cart']  =
                Array(  'cart_total', 'cart_product_count', 'cart_created_date',
                        'cart_paid_date', 'order_url', 'order_id'  
//                        'cart_link', 
                    );
        
        //config specific
        static::$_tokens['config']   =
                Array('store_address', 'store_name','company_logo', 'store_url');
        
        //Product Tokens
        static::$_tokens['product']   =
                Array('products_detail', 'products_name');
                
        static::$_tokens['shipment']  = 
        		Array('tracking_number','tracking_url','products');
        
        return true;
    }
    
    
//    public function setTokens(Array $tokens, $token_type) 
//    {
//        static::$_tokens[$token_type] = $tokens;
//    }

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
     * Invoke to build token on cart object
     * @param PaycartCart $cart 
     * 
     */
    public function buildCartTokens( PaycartCart $cart )
    {        
        // fetch all relative objects
        $buyer                = $cart->getBuyer(true);
        $billing_address      = $cart->getBillingAddress(true);
        $shipping_address     = $cart->getShippingAddress(true);
        $config               = PaycartFactory::getConfig();
        $cart                 = $cart;

        if($cart->isLocked()){
        	/* @var $cart_helper PaycartHelperCart */
        	$cart_helper = PaycartFactory::getHelper('cart');
        
	        $product_particular_list   =  $cart_helper->getCartparticularsData($cart->getId(), Paycart::CART_PARTICULAR_TYPE_PRODUCT);
	        $shipping_particular_list  =  $cart_helper->getCartparticularsData($cart->getId(), Paycart::CART_PARTICULAR_TYPE_SHIPPING);
	        $promotion_particular_list =  $cart_helper->getCartparticularsData($cart->getId(), Paycart::CART_PARTICULAR_TYPE_PROMOTION);
        }else{
        	$cart->calculate();
        	$product_particular_list   =  $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
	        $shipping_particular_list  =  $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
	        $promotion_particular_list =  $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
        }
        
        $tokens = Array();
            
        $tokens = array_merge($tokens, $this->getCartToken($cart));
        $tokens = array_merge($tokens, $this->getConfigToken($config));
        $tokens = array_merge($tokens, $this->getBuyerToken($buyer));
        $tokens = array_merge($tokens, $this->getProductToken($product_particular_list,$shipping_particular_list,$promotion_particular_list));
        $tokens = array_merge($tokens, $this->getBillingToken($billing_address));
        $tokens = array_merge($tokens, $this->getShippingToken($shipping_address));
        
        
        //@PCTODO :: Trigger to add new tokens
        
        return $tokens;
    }
    
    
   /**
     * Invoke to build token on cart object
     * @param PaycartCart $cart 
     * 
     */
    public function buildShipmentTokens(PaycartShipment $shipment )
    {       
        $tokens = Array();
        
        // @PCTODO :: move to buildCartTokens 
        $tokens = array_merge($tokens, $this->getShipmentToken($shipment));
        $tokens = array_merge($tokens, $this->buildCartTokens($shipment->getCart()));
        
        //@PCTODO :: Trigger to add new tokens
        
        return $tokens;
    }
    
    /**
     * Invoke to get tokens
     * @param type $lib_object either PaycartCart onject or PaycartShippment object
     * @return type Array of tokens
     * @throws RuntimeException 
     */
    public function getTokens($lib_object, $lang_code = null)
    {
		//if lang_code is different than current lang, then load that file
		$current_lang = PaycartFactory::getPCCurrentLanguageCode();
		$language = PaycartFactory::getLanguage();		 
		if($lang_code !== null && $lang_code !== $current_lang){			
			$language->load('com_paycart', JPATH_SITE, $lang_code, true, false);
		}
			
        // get all relative objects
        if ($lib_object instanceof PaycartCart) {
            $tokens = $this->buildCartTokens($lib_object);
        } 
		elseif($lib_object instanceof PaycartShipment) {
            $tokens = $this->buildShipmentTokens($lib_object);
        }
        else{
        	throw new RuntimeException('Unknown object for token fetching ');
        }
                
        // load the current language file again, if another language file was loaded previously
        if($lang_code !== null && $lang_code !== $current_lang){
        	$language->load('com_paycart', JPATH_SITE, $current_lang, true, false);
        }
        return $tokens;
    }

    /**
     * Invoke to get billing token with their values
     * @param PaycartBuyeraddress $billing_address 
     * 
     * @return Array
     */
    private  function getBillingToken(PaycartBuyeraddress $billing_address ) 
    {
        $tokens =  Array();
        
        // cart specific
        $tokens['billing_to']              =   $billing_address->getTo();
        $tokens['billing_address']         =   $billing_address->getAddress();
        $tokens['billing_phone']           =   $billing_address->getPhone();
        $tokens['billing_zip_code']        =   $billing_address->getZipcode();
        $tokens['billing_vat_number']      =   $billing_address->getVatnumber();
        $tokens['billing_country']         =   $this->_formatter->country($billing_address->getCountryId());
        $tokens['billing_state']           =   $this->_formatter->state($billing_address->getStateId());
        $tokens['billing_city']            =   $billing_address->getCity();
        
        return $tokens;
    }
    
    
    /**
     * Invoke to get Shipping token with their values
     * @param PaycartBuyeraddress $shipping_address
     *
     * @return Array
     */
    private function getShippingToken(PaycartBuyeraddress $shipping_address ) 
    {
       $tokens =  Array();
        
        // cart specific
        $tokens['shipping_to']              =   $shipping_address->getTo();
        $tokens['shipping_address']         =   $shipping_address->getAddress();
        $tokens['shipping_phone']           =   $shipping_address->getPhone();
        $tokens['shipping_zip_code']        =   $shipping_address->getZipcode();
        $tokens['shipping_vat_number']      =   $shipping_address->getVatnumber();
        $tokens['shipping_country']         =   $this->_formatter->country($shipping_address->getCountryId());
        $tokens['shipping_state']           =   $this->_formatter->state($shipping_address->getStateId());
        $tokens['shipping_city']            =   $shipping_address->getCity();
        
        return $tokens;
    }
    
    /**
     * Invoke to get cart token with their values
     * @param PaycartCart $cart 
     *
     * @return Array
     */
    private function getCartToken(PaycartCart $cart ) 
    {
       $tokens =  Array();
        
        // cart specific
        $tokens['cart_total']              =   $this->_formatter->amount($cart->getTotal());
        $tokens['cart_product_count']      =   count($cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT));
        $tokens['cart_created_date']       =   $this->_formatter->date($cart->getCreatedDate());
        // @PCTOD :: date formating here
        $tokens['cart_paid_date']          =   $this->_formatter->date($cart->getPaidDate());
        $tokens['current_cart']		       =   JUri::root().'index.php?option=com_paycart&view=cart';
        $tokens['order_url']               =   $cart->getOrderUrl(true);
        $tokens['order_id']            	   =   $cart->getId();
        
        return $tokens;
    }
    
    /**
     * Invoke to get buyer token with their values
     * @param PaycartBuyer $buyer
     * 
     * @return Array 
     */
    private  function getBuyerToken(PaycartBuyer $buyer)
    {
       $tokens =  Array();
       
       $tokens['buyer_email']      = $buyer->getEmail();
       $tokens['buyer_name']       = $buyer->getRealName();
       $tokens['buyer_orders']     = '<a href="'.JUri::root().'index.php?option=com_paycart&view=account&task=order'.'" target="_blank">'.JText::_('COM_PAYCART_NOTIFICATION_LINK_YOUR_ORDERS').'</a>';
       $tokens['buyer_account']    = '<a href="'.JUri::root().'index.php?option=com_paycart&view=account&task=display'.'" target="_blank">'.JText::_('COM_PAYCART_NOTIFICATION_LINK_MY_ACCOUNT').'</a>';        
       return $tokens;
    }
    
    /**
     * Invoke to get Config token with their values
     * @param stdClass $config
     * @return Array 
     */
    private  function getConfigToken($config)
    {
       $tokens =  Array();
       
       //@PCTODO :: origin address, Company logo
       $config = PaycartFactory::getConfig();
       
       $tokens['store_name']   = rtrim($config->get('company_name', $config->get('sitename')),'/');
       
       $tokens['company_logo'] = '';
       $media_id 			   =  $config->get('company_logo');
       if($media_id){
       	$media = PaycartMedia::getInstance($media_id);
       	$tokens['company_logo'] = $media->getOriginal();
       }      
       
       $displayData = $config->get('localization_origin_address');
       $tokens['store_address'] =  Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $displayData, PAYCART_LAYOUTS_PATH);
       
       $tokens['store_url'] = '<a href="'.JUri::root().'" target="_blank">'.$tokens['store_name'].'</a>';
           
       return $tokens;
    }
    
    
    /**
     * Invoke to get Product tokens
     * @param Array $product_particulars
     * 
     * @return array 
     */
    private  function getProductToken(Array $product_particulars, Array $shipping_particulars, Array $promotion_particulars)    
    {
        $tokens =  Array();
       
        $displayData = new stdClass;
        $displayData->product_particulars   = $product_particulars;
        $displayData->shipping_particulars  = $shipping_particulars;
        $displayData->promotion_particulars = $promotion_particulars;
        
        // Create a layout to render all product details
        $tokens['products_detail'] = Rb_HelperTemplate::renderLayout('paycart_token_product_deatils', $displayData, PAYCART_LAYOUTS_PATH);
        
        $displayData = new stdClass;
        $displayData->product_particulars   = $product_particulars;
        
		$tokens['products_name']   = Rb_HelperTemplate::renderLayout('paycart_token_product_names', $displayData, PAYCART_LAYOUTS_PATH);
        return $tokens;
    }
    
	/**
     * Invoke to get shipment tokens
     * @param PaycartShipment $shipment
     * 
     * @return array 
     */
    private function getShipmentToken(PaycartShipment $shipment)
    {
    	$tokens = array();
    	
    	$tokens['tracking_number'] = $shipment->getTrackingNumber();
    	$tokens['tracking_url']	   = $shipment->getTrackingUrl();
    	$cart         			   = $shipment->getCart();
    	$cart_helper			   = PaycartFactory::getHelper('cart');  
        $product_particular_list   = $cart_helper->getCartparticularsData($cart->getId(), Paycart::CART_PARTICULAR_TYPE_PRODUCT);
       	$promotion_particular_list = $cart_helper->getCartparticularsData($cart->getId(), Paycart::CART_PARTICULAR_TYPE_PROMOTION);        
        	        
       	$promotion = 0;
       	
       	foreach ($promotion_particular_list as $pp){
			if(!$pp instanceof stdClass){
				$pp = $pp->toObject();
			}
			$promotion += $pp->total;	
       	}
       	
       	if(!empty($promotion)){
       		$promotion = round($promotion/count($cart_helper->getShipments($cart->getId())));
       	}
       	       	
       	$shipmentArray = $shipment->toArray();
        $products      = $shipment->getProducts();
    	$display_data = new stdClass();
    	$display_data->products = $products;
    	$display_data->product_particulars   = $product_particular_list;
    	$display_data->promotion = $promotion;
    	$display_data->shipping_charge = $shipmentArray['actual_shipping_cost'];
    	
        $tokens['products'] = Rb_HelperTemplate::renderLayout('paycart_token_shipment_products',$display_data, PAYCART_LAYOUTS_PATH);
        
        return $tokens;
    }
    
}