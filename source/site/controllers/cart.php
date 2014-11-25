<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal, mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Cart Front end Controller
 */
class PaycartSiteControllerCart extends PaycartController
{
	protected $message		=	'';
	protected $message_type	=	'';

	/**
	 * @var PaycartHelperCheckout
	 */
	protected $helper;
	
	/**
	 * @var PaycartCart
	 */
	protected $cart;
								
	/**
	 * 
	 * define here checkout 
	 * 		- Step sequence
	 * 		- Helper object
	 * 		- Cart for checkout
	 *
	 *
	 * @since 	1.0
	 * @author 	Manish
	 *  
	 * @param Array $options
	 * 
	 */
	public function __construct($options = array()) 
	{
		parent::__construct($options);
		
		$this->setCurrentLanguage();
		$this->helper			=	PaycartFactory::getHelper('checkout');
		$this->cart				=	PaycartFactory::getHelper('cart')->getCurrentCart();
		
		// change the language code, only not locked
		if ( $this->cart instanceof PaycartCart && !$this->cart->isLocked()) {
			$this->cart->set('lang_code', PaycartFactory::getPCCurrentLanguageCode());
		}
		
		$this->step_sequence	=	$this->helper->getSequence();
	}
	
    /**
     *
     * Invoke to check existing cart is valid or not
     * @param array $form_data
     * @return boolean 
     */
	private function _isCartValid(Array $form_data = Array() )
	{
		// if cart is not exist or cart is empty then intimate to end user 
		if ( !($this->cart instanceof PaycartCart) ) {
			$this->setTemplate('error_invalid_cart');
			return false;
		}
		
        // If cart is empty
		if ( 0 == count($this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT)) ) {
		 	$this->setTemplate('error_empty_cart');
			return false;
		}	
		
		return true;
	}
	
	public function checkout()
	{
		$this->setTemplate('checkout');
		
		// If there is error then return and do nothing
		$isCartValid = $this->_isCartValid();
		$this->getView()->assign('isCartValid', $isCartValid);
		if(!$isCartValid){			
			return true;
		}
				
		// if user login then move next step
		if (PaycartFactory::getUser()->id)  {
			
			// buyer already login then set thier detail into cart param
			$loggedin_user =  PaycartFactory::getUser();
		
			$buyer = $this->cart->getParam('buyer', new stdClass());	
			$buyer->email 	=	$loggedin_user->get('email');
			$buyer->id		=	$loggedin_user->get('id');
			
			//set it not guest checkout
			$this->cart->setIsGuestCheckout(false);
                        
             //set buyer id on cart
             $this->cart->setBuyer($buyer->id);
			
			// set buyer 
			$this->cart->setParam('buyer', $buyer);
			$this->cart->setBuyer($buyer->id);		
			
			//cart Process and save
			$this->cart->calculate()->save();
		}
		
		$this->getView()->set('cart', $this->cart);
		return true;
	}
	
	public function login()
	{	
		// If there is error then return and do nothing
		$isCartValid = $this->_isCartValid();
		$this->getView()->assign('isCartValid', $isCartValid);
		if(!$isCartValid){			
			return true;
		}

		// If user is logged in, execute next task
		if (PaycartFactory::getUser()->id)  {
			$this->execute('address');			
			return false;
		}
		
		$form_data = $this->input->get('paycart_cart_login',Array(), 'ARRAY');

		if(!empty($form_data)){
			$errors = array();			 
			// validate email address
			if (!JMailHelper::isEmailAddress($form_data['email'])) {
				$error = new stdClass();
				$error->message 		= JText::_('COM_PAYCART_INVALID_BUYER_EMAIL_ID');
				$error->message_type	= Paycart::MESSAGE_TYPE_WARNING;
				$error->for				= 'email';
				$errors[] = $error;				
			}		
			
			if(empty($errors)){			
				if ($form_data['emailcheckout'] ) {
					// email checkout
					$this->_loginWithEmail($form_data, $errors);
				} else {
					//checkout by login
					$this->_login($form_data, $errors);
				}
			}
			
			// if errors
			if ( !empty($errors )) {
				$this->getView()->assign('errors', $errors);
				return true;
			}
		
			//calculation on cart then save it 
			$this->cart->calculate();
			
			//Cart should be save after processing
			$this->cart->save();
			
			if ($form_data['emailcheckout'] ) {
				$this->execute('address');
			} 

			// need to refresh page when user loggin successfully 
			$this->setRedirect(JRoute::_('index.php?option=com_paycart&view=cart&task=checkout'));

			return false;
		}
		
		$this->getView()->set('cart', $this->cart);		
		return true;
	}	
	
	/**
	 * Login user by their username and pwd
	 * @param array $form_data = Array('email' => _EMAIL_ID_, 'password'=> _PASSWORD_ )
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return user_id if buyer successfully login otherwise false
	 */
	private function _login(Array $form_data, &$errors = array())
	{
		// get username
		$username = PaycartFactory::getHelper('buyer')->getUsername( $form_data['email']);
		
		if (!$username) {
			$error = new stdClass();
			$error->message 		= JText::_('COM_PAYCART_BUYER_IS_NOT_EXIT');
			$error->message_type	= Paycart::MESSAGE_TYPE_ERROR;
			$error->for				= 'email';
			$errors[] = $error;
			return false;			
		}
		
		// prepare credential data
		$credentials				=	Array();
		$credentials['username']	=	$username;
		$credentials['password']	=	$form_data['password'];
		
		$options				=	Array();
		$options['remember']	=	@$form_data['remember'];
		
		if (! PaycartFactory::getApplication()->login($credentials, $options))
		{			
			$error = new stdClass();
			$error->message 		= JText::_('COM_PAYCART_BUYER_FAIL_TO_LOGIN');
			$error->message_type	= Paycart::MESSAGE_TYPE_ERROR;
			$error->for				= 'header';
			$errors[] = $error;
			return false;	
		} 
		
		$loggedin_user =  PaycartFactory::getUser();
		
		$buyer = new stdClass();	
		$buyer->email 	=	$loggedin_user->get('email');
		$buyer->id		=	$loggedin_user->get('id');
		
		//set it not guest checkout
		$this->cart->setIsGuestCheckout(false);
        //set buyer id on cart
        $this->cart->setBuyer($buyer->id);
		
		// set buyer 
		$this->cart->setParam('buyer', $buyer);
		$this->cart->setBuyer($buyer->id);

		return true;
	} 
	
	/**
	 * Email Checkout by user email-id
	 * 		- Create new account if user is not exist
	 * @param array $form_data = Array('email' => _EMAIL_ID_ )
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return user_id if buyer successfully registered otherwise false
	 */
	private function _loginWithEmail(Array $form_data, &$errors = array())
	{
		$buyer =	new stdClass();
		
		$buyer->email 	= $form_data['email'];
		$buyer->id		= 0;

		$this->cart->setParam('buyer', $buyer);
		
		// guest checkout enabled on this cart 
		$this->cart->setIsGuestCheckout(true);
		$this->cart->setBuyer($buyer->id);
	
		return true;
	}
	
	
	public function address()
	{
		// If there is error then return and do nothing
		$isCartValid = $this->_isCartValid();
		$this->getView()->assign('isCartValid', $isCartValid);
		if(!$isCartValid){			
			return true;
		}

		// If user is logged in, execute next task
		if (!PaycartFactory::getUser()->id && !($this->cart->isGuestcheckout()))  {
			$this->execute('login');			
			return false;
		}
		
		$form_data = $this->input->get('paycart_cart_address',Array(), 'ARRAY');

		if(!empty($form_data)){
			$errors = array();
			$this->_address($form_data, $errors);
			// if errors
			if ( !empty($errors )) {
				$this->getView()->assign('errors', $errors);
				return true;
			}			
			
			//calculation on cart then save it 
			$this->cart->calculate();
			
			//Cart should be save after processing
			$this->cart->save();
			
			$this->execute('confirm');
		}
		
		$this->getView()->set('cart', $this->cart);
		return true;
	}
	
	/**
	 * Step Address execute here.
	 * 		- Store user address
	 * 	
	 * @param Array $form_data Post data 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return true, if successfully process
	 */
	private function _address(Array $form_data, &$errors = array())
	{
		// get Billing Address
		
		// copy variable
		$billing_to_shipping	=	isset($form_data['billing_to_shipping']) ? $form_data['billing_to_shipping'] : false;
		$shipping_to_billing	=	isset($form_data['shipping_to_billing']) ? $form_data['shipping_to_billing'] : false;
		
		$billingaddress_id 	= 	0;
		$shippingaddress_id	=	0;
		
		$billing_address_data 	=	'';
		$shipping_address_data	=	'';
		
		//if buyer is loggin then might be user select address into existing address
		if (PaycartFactory::getUser()->id ) {
			$buyer_id = PaycartFactory::getUser()->get('id');
			
			//if user selected address into existing address then get buyer address data and set into cart param 
			$billingaddress_id	=	isset($form_data['billingaddress_id']) ? $form_data['billingaddress_id'] : false;
			$shippingaddress_id	=	isset($form_data['shippingaddress_id']) ? $form_data['shippingaddress_id'] : false;
			
			//@PCTODO ::  Cross check this address binded with loggedin user id
			
			$data = Array();
			
			//get billing address data
			if ( $billingaddress_id ) {
				$data = PaycartBuyeraddress::getInstance($billingaddress_id)->toArray();
				$billing_address_data = (object)($data);
			}
			
			
			// get shipping address data
			if ( $shippingaddress_id ) {
				$data = PaycartBuyeraddress::getInstance($shippingaddress_id)->toArray();
				$shipping_address_data = (object)($data);
			}
			
			
		}
		
		// if no need to copy from shipping to billing OR billing address data is not available in earliar processing
		// its means billing data is posted (in req data) 
		if ( !$shipping_to_billing && empty($billing_address_data) ) {
			// get billing address details
			$billing_address_data 					=	(object)$form_data['billing'];
			$billing_address_data->buyer_id			=	$this->cart->getBuyer();
			$billing_address_data->buyeraddress_id	=	0;
		}

		// if no need to copy from billing to shipping OR shipping address data is not available in earliar processing
		// its means shipping data is posted (in req data)
		if ( !$billing_to_shipping && empty($shipping_address_data) ) {
			// get billing address details
			$shipping_address_data 					=	(object)$form_data['shipping'];
			$shipping_address_data->buyer_id		=	$this->cart->getBuyer();
			$shipping_address_data->buyeraddress_id	=	0;
		}
		
		//copy address data one entity to another entity
		if ($billing_to_shipping) {
			$shipping_address_data	= 	clone $billing_address_data;
		} else if ($shipping_to_billing) {
			$billing_address_data	=	clone $shipping_address_data;
		}
		
		// Make sure if address are diffrent-diffrent submitted then we will create only one address  
		if ( $billing_address_data == $shipping_address_data ) {
			$billing_to_shipping	= true;
		}
		
		//set address on cart-param 
		$this->cart->setParam('billing_address', 		$billing_address_data);
		$this->cart->setParam('shipping_address', 		$shipping_address_data);
		$this->cart->setParam('billing_to_shipping', 	(bool)$billing_to_shipping);
		$this->cart->setParam('shipping_to_billing', 	(bool)$shipping_to_billing);
		
		$this->cart->setBillingAddressId($billing_address_data->buyeraddress_id);
		$this->cart->setShippingAddressId($shipping_address_data->buyeraddress_id);

		return true;
	}
	
	public function confirm()
	{
		// If there is error then return and do nothing
		$isCartValid = $this->_isCartValid();
		$this->getView()->assign('isCartValid', $isCartValid);
		if(!$isCartValid){			
			return true;
		}

		// If user is logged in, execute next task
		if (!PaycartFactory::getUser()->id && !($this->cart->isGuestcheckout()))  {
			$this->execute('login');			
			return false;
		}
		
		$form_data = $this->input->get('paycart_cart_confirm',Array(), 'ARRAY');

		if(!empty($form_data)){
			$errors = array();
			
			try{			
				$this->cart->confirm();
			}catch (Exception $ex) {
				$error = new stdClass();
				$error->message 		= $ex->getMessage();
				$error->message_type	= Paycart::MESSAGE_TYPE_ERROR;
				$error->for				= 'header';
				$errors[] = $error;
				return false;
			}
			
			//calculation on cart then save it 
			$this->cart->calculate();
			
			//Cart should be save after processing
			$this->cart->save();
			
			$this->execute('gatewayselection');
		}
		
		$this->getView()->set('cart', $this->cart);
		return true;
	}	
	
	public function gatewayselection()
	{
		// If there is error then return and do nothing
		$isCartValid = $this->_isCartValid();
		$this->getView()->assign('isCartValid', $isCartValid);
		if(!$isCartValid){			
			return true;
		}

		// If user is logged in, execute next task
		if (!PaycartFactory::getUser()->id && !($this->cart->isGuestcheckout()))  {
			$this->execute('login');
			return false;
		}
		
		$form_data = $this->input->get('paycart_cart_payment',Array(), 'ARRAY');

		if(!empty($form_data)){
			
		}
		
		$this->getView()->set('cart', $this->cart);
		return true;
	}	

	/**	
	 * JSON Task. 
	 * @return : return Payment form html
	 */
	public function paymentForm() 
	{
		$payment_data	=	$this->input->post->get('payment_data', array(), 'array');		
		if(!empty($payment_data)){
			$cart_id		=	$this->input->get('cart_id', 0);
			$cart_instance	=	PaycartCart::getInstance($cart_id);
	
			$cart_instance->requestPayment($payment_data);
	
			$this->setRedirect(Rb_Route::_('index.php?option=com_paycart&view=cart&task=complete&cart_id='.$cart_id));
	
			return false;
		}
		
		// if payment form is not posted  then return payment form html		
		$gateway_id	= $this->input->get('paymentgateway_id', 0 );
		$errors = array();		
		if ( empty($gateway_id) ) {		
			$error = new stdClass();
			$error->message 		= JText::_('Processor-Required');
			$error->message_type	= Paycart::MESSAGE_TYPE_ERROR;
			$error->for				= 'payment-gateway';
			$errors[] = $error;	
		}
		else{
			$this->cart->updatePaymentProcessor($gateway_id)->save();
			$this->getView()->set('cart', 		$this->cart);
		}
		
		$this->getView()->set('errors', $errors);
		return true;
	}
	
	/**	
	 * JSON Task. 
	 * @return 
	 */
	public function order() 
	{
		$errors = array();
		try {
			// store all params data
			$this->cart->order();			
		} catch (Exception $e) {			
			$error  			   	= new stdClass();	
			$error->message_type   	= Paycart::MESSAGE_TYPE_ERROR;
			$error->for				= 'header';			
			$error->message   		= $e->getMessage();
			$errors[] 				= $error;
		}
		
		$this->getView()->set('errors',		$errors);
		$this->getView()->set('cart', 		$this->cart);
		
		return true;
	}
	
	/**
	 * JSON task
	 * //@PCTODO :: should be move into buyer address
	 */
	public function getBuyerAddress()
	{
		return true;
	}

	/**
	 * 
	 * Enter description here ...
	 */
	function complete()
	{
		$cart_id = $this->get('cart_id', 0);
		
		return true;
	}
	
	
	function cancel()
	{
		echo "Payment cancelllllllllllllllllll";
		exit;
	}
	
	public function notify()
	{
		$get 				= $this->input->get->getArray();	
		$post 				= $this->input->post->getArray();	
		
		$response_data 		= array_merge($get, $post);
		
		$response 			= new stdClass();
		$response->data 	= $response_data;
		$response->__post	= $post;
		$response->__get	= $get;

		//file_put_contents(JPATH_SITE.'/tmp/'.time(), var_export($response_data,true), FILE_APPEND);
		
		if (defined('JDEBUG') && JDEBUG) {
			// dump data
			file_put_contents(JPATH_SITE.'/tmp/'.time(), var_export($response_data,true), FILE_APPEND);
		}
		
		if(!isset($response_data['processor'])){
			// @PCFIXME:: dump data and notify to admin	
		}
		
		try {
			/* @var $invoice_helper_instance PaycartHelperInvoice */
			$invoice_helper_instance = PaycartFactory::getHelper('invoice');
			$invoice_id = $invoice_helper_instance->getNotificationInvoiceId($response);
			
			$cart_records	= 	PaycartFactory::getModel('cart')->loadRecords(Array('invoice_id' => $invoice_id));
			$cart_record 	=	array_pop($cart_records);
			
			$cart = PaycartCart::getInstance(0, $cart_record);
			
			$cart->processNotification($response);

		} catch (Exception $ex) {
			//@PCTODO :: dump exception into log file
			$ex->getMessage();
		}
		echo "Success!!";
		exit;				
	}
	
	/**
	 * Displays the list of products added to cart
	 * It is triggered by html and ajax type urls
	 */
	function display($cachable = false, $urlparams = array())
	{
		return parent::display($cachable, $urlparams);
	}
	
	/**
	 * Adds a product to current cart
	 * It redirects to Cartview direclty after processing the request.
	 * That is the difference between "buy now" and "add to cart"  
	 */
	public function buy()
	{
		$this->_addProduct();
		$this->setRedirect(PaycartRoute::_('index.php?option=com_paycart&view=cart&task=display'));
		return false;
	}
	
	/**
	 * Ajaxified task to add product
	 */
	public function addProduct()
	{
		return $this->_addProduct();
	}
	
	/**
	 * JSON task to any product from cart
	 */
	public function removeProduct()
	{
		//get current cart
		$helper = PaycartFactory::getHelper('cart');
		$cart 	= $helper->getCurrentCart();
		
		//delete product
		$productId = $this->input->get('product_id',0,'INT');
		$cart->removeProduct($productId);		
		$cart->calculate()->save();
		
		//@PCTODO : Error Handling
		
		$view = $this->getView();
		$view->assign('productId', 	$productId);
		$view->assign('errors', array());
		return true;
	}
	
	/**
	 * Updates the product quantity
	 * It is a JSON Task
	 */
    public function updateProductQuantity()
	{
		//PCTODO : Clean this code
		list($result,$prevQuantity,$productId,$allowedQuanity) = $this->_addProduct();
		
		$errors 	=  array();
		if(!$result){	
			$error 	=  array();					
			$error['message_type']   	= Paycart::MESSAGE_TYPE_ERROR;
			$error['for']				= '';			
			$error['message']   		= JText::_("COM_PAYCART_PRODUCT_INVALID_QUANTITY").": ".$allowedQuanity;
			$errors[] = $error;
		}
		
		$view = $this->getView();
		$view->assign('productId', 	$productId);
		$view->assign('prevQuantity', $prevQuantity);
		$view->assign('allowedQuantity', $allowedQuanity);
		$view->assign('errors', $errors);
		return true;
	}
	

	/**
	 * Adds product in the current cart or updates the quantity of existing product
	 * It is a sub-task and used by addProduct and updateProductQuantity tasks.
	 */
    private function _addProduct()
	{
		$productId = $this->input->get('product_id',0,'INT');
		$quantity  = $this->input->get('quantity',1,'INT');
		
		// @PCTODO :: error handling
		return PaycartFactory::getHelper('cart')->addProduct($productId, $quantity);
	}
	
	/**
	 * Applies promotion code on current cart
	 * JSON Task
	 */
	public function applyPromotion()
	{
		// get promotion code
		$promotion_code = $this->input->get('promotion_code', null, 'ALNUM');
		$errors 	=  array();
			
		try {
			if (!empty($promotion_code)) {
				PaycartFactory::getHelper('cart')->applyPromotion($promotion_code);
			}
			
		} catch (Exception $ex) {
			$error 	=  array();			
			$error['message_type']   	= Paycart::MESSAGE_TYPE_ERROR;
			$error['for']				= '';				
			$error['message']   		= $e->getMessage();
			$errors[] = $error;
		}
	
		$view = $this->getView();
		$view->assign('errors', $errors);
		return true;	
	}
	
	/**
	 * Do calculation again, when shipping method is changed by user
	 */
	function changeShippingMethod()
	{
		// get shipping option
		$shippingMethod = $this->input->get('shipping','','STRING');
		
		//get current cart
		$helper = PaycartFactory::getHelper('cart');
		$cart 	= $helper->getCurrentCart();
		
		try {
			$cart->setParam('shipping', $shippingMethod)->calculate()->save();
		}
		catch(Exception $e){
			$error 	=  array();			
			$error['message_type']   	= Paycart::MESSAGE_TYPE_ERROR;
			$error['for']				= '';				
			$error['message']   		= $e->getMessage();
			$errors[] = $error;
		}
	
		$view = $this->getView();
		$view->assign('errors', $errors);
		return true;	
	}
	
	/**
	 * ============================================================================================
	 *  @PCTODO :: should be created new controller for this kind of task
	 *  Json function invoke to retrive data
	 * ============================================================================================
	 */
	
	public function getProductCount() 
	{}
}