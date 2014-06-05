<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Fronnt-end
* @author		mManishTrivedi
* @contact		support+paycart@readybytes.in
* 
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/**
 * 
 * Checkout Controller
 * @author Manish
 *
 */

class PaycartSiteControllerCheckout extends PaycartController 
{
	protected $_defaultTask = 	'init';
	
	//default step
	protected $step_current	=	Paycart::CHECKOUT_STEP_LOGIN;

	protected $message		=	'';
	protected $message_type	=	'';
	
	protected $step_sequence = Array();
	
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
		$this->helper			=	PaycartFactory::getHelper('checkout');
		$this->cart				=	$this->getCart();
		$this->step_sequence	=	$this->helper->getSequence();
		
		return parent::__construct($options);
	}
								
	
	/**
	 * 
	 * Checkout Process initiate. 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 */
	public function init()
	{
	
		if (!$this->preProcess()) {
			// Show error page
			$this->getView()->set('step_ready', 'error');
			
			$this->getView()->set('message', 		$this->message);
			$this->getView()->set('message_type', 	$this->message_type);
			return true; 
		}
		
		// @NOTE:: Very necessary thing
		// If user is cuming from outside then ALWAYS call init function. Ok  
		// If user was doing guest chekout an movie anywhere on site then Checkout always start with First Step  
		//@PCFIXME :: Check cart exist or not
		$this->cart->setIsGuestCheckout(false);

		// if user login then move next step
		if ($this->_is_loggedin())  {
			$this->step_current = $this->step_sequence[$this->step_current];
		}
	
		$this->getView()->set('step_ready', $this->step_current);
		$this->getView()->set('cart', 		$this->cart);
		return true;
	} 
	
	/**
	 * 
	 * Checked user is loggedin or not
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return bool true if user is loggedin
	 * 
	 */
	protected function _is_loggedin() 
	{
		$user = PaycartFactory::getUser();
		
		if ($user->get('id')) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Process whole chekout flow by this method
	 * 1#. Pre-Process current step
	 * 		- get Paycart form data
	 * 		- filter input var if required  
	 * 2#. Process Current-Step
	 * 		- Process current step and get result
	 * 3#. Post-Process current step
	 * 		- if current process successfully then go to view
	 * 		- if current process does not complete then notify to end user and respone send
	 * 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return (bool) true , if process successfully completed
	 */
	public function process()
	{
		//1#.	Pre-Process
		$form_data = $this->input->get('paycart_form',Array(), 'ARRAY');
		
		$this->step_current = $this->input->get('step_name');

		//@TODO :: event fire
		
		//2#. 	Steps process
		if ( $this->preProcess($form_data) && $this->stepProcess($form_data)) {
			
			//calculation on cart then save it 
			$this->cart->calculate();
			
			//Cart should be save after processing
			$this->cart->save();			
		}
		
		//3#.	Post-Processing
		return $this->postProcess();
		
		
	}
	
	/**
	 * Pre-Process current step
	 * 	All previous step will be verified then move next
	 *  
	 *  
	 * @param array $form_data
	 * 
	 * @since	1.0
	 * @author 	Manish
	 * 
	 * @return bool true if successfully validate
	 */
	protected function preProcess(Array $form_data = Array() )
	{
		// if cart is not exist or cart is empty then intimate to end user 
		if ( !($this->cart instanceof PaycartCart)  ) {
			$this->message			=	JText::_('COM_PAYCART_CART_NOT_EXIST');
			$this->message_type		=	Paycart::MESSAGE_TYPE_ERROR;
			return false;
		}
		
		if ( 0 == count($this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT)) ) {
		 	$this->message			=	JText::_('COM_PAYCART_CART_PRODUCT_NOT_EXIST');
			$this->message_type		=	Paycart::MESSAGE_TYPE_WARNING;
			return false;
		 }
		
		// @PCTODO:: Check  minimum condition for Checkout-flow like minimum amount, mimimum product.
		
		// If user neither login nor using guest checkout
		if (!$this->_is_loggedin() && !($this->cart->getIsGuestCheckout()) )  {
			//change current step
			$this->step_current	=	Paycart::CHECKOUT_STEP_LOGIN;
			return true;
		}
		
		return true;
	}
	
	protected function postProcess()
	{
		// if any msg type set then no need to further process
		if (!$this->message_type) {
			$this->getView()->set('step_ready', $this->step_sequence[$this->step_current]);
			$this->getView()->set('cart', 		$this->cart);
			return true;
		}
		
		//error handling
		$ajax_response = PaycartFactory::getAjaxResponse();
		$response_data = Array();
		$response_data['messgae'] 		= $this->message;
		$response_data['messgae_type'] 	= $this->message_type;

		//@PCTODO  :: 
		$ajax_response->addScriptCall('console.log', $response_data);
		
		// return false no need to execute view 
		return false;
	}

	/**
	 * Process current step
	 * @param array $form_data
	 * 
	 * @since	1.0
	 * @author	Manish
	 * 
	 * @return bool true if successfully process
	 */
	protected function stepProcess(Array $form_data )
	{
		try{
			
			switch ($this->step_current) 
			{
				case Paycart::CHECKOUT_STEP_LOGIN :
					return  $this->step_login($form_data);
					
				case Paycart::CHECKOUT_STEP_ADDRESS :
					return  $this->step_address($form_data);
					
				case Paycart::CHECKOUT_STEP_CONFIRM :
					return  $this->step_confirm($form_data);
					
				case Paycart::CHECKOUT_STEP_PAYMENT :
					return $this->step_payment($form_data);
					
				default:
					// @PCTODO:: throw exception unknown step. 
					$this->message = JText::_('COM_PAYCART_UNKNOWN_CHECKOUT_STEP');
					$this->message_type = Paycart::MESSAGE_TYPE_ERROR;
			}
			
		} catch (Exception $e) {
			//@PCXXX :: dump into logs
			$this->message		= $e->getMessage();
			$this->message_type	= Paycart::MESSAGE_TYPE_ERROR;
			
		}
		
		return false;
	}
	
	/**
	 * Step Login execute here.
	 * 		- if user checkout by email then Process by _do_emailCheckout
	 * 		- if user checkout by login then Process by _do_login
	 * 	
	 * @param Array $form_data Post data 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return true , if successfully process
	 */
	protected function step_login(Array $form_data)
	{
		
		// validate email address
		if (!JMailHelper::isEmailAddress($form_data['email'])) {
			$this->message 		=	JText::_('COM_PAYCART_INVALID_BUYER_EMAIL_ID');
			$this->message_type	=	Paycart::MESSAGE_TYPE_WARNING;
			return false;
		}		
		
		$is_processed = false; 
		
		if ($form_data['emailcheckout'] ) {
			// email checkout
			$is_processed = $this->_do_loginWithEmail($form_data);
		} else {
			//checkout by login
			$user_id = $this->_do_login($form_data);
		}
		
		// if do not get any user id 
		if ( !$is_processed ) {
			return false;
		}
	
		return true;
	}
	
	/**
	 * Invoke to get current cart whcih is mapped with current session id
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return Paycartcart if cart exits otherwise false
	 */
	protected function getCart() 
	{
		// get current session id
		$session_id =	PaycartFactory::getSession()->getId();
		
		// get cart data
		$cart_data 	=	PaycartFactory::getModel('cart')->loadRecords(Array('session_id' => $session_id));
		
		if (!$cart_data) {
			// @PCFIXME::for testing purpose, comment below code
			//return false;
		}

		//return PaycartCart::getInstance(0, $cart_data);
		// @FIXME :: use only testing purpose
		$cart = PaycartCart::getInstance(1);
		
		// Calculation should be done before any action
		if ( $cart instanceof PaycartCart ) {
			return $cart->calculate();
		}
			
		return $cart;
	}
	
	/**
	 * Login user by their username and pwd
	 * @param array $form_data = Array('email' => _EMAIL_ID_, 'password'=> _PASSWORD_ )
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return ser_id if buyer successfully login otherwise false
	 */
	protected function _do_login(Array $form_data)
	{
		// get username
		$username = PaycartFactory::getHelper('buyer')->getUsername( $form_data['email']);
		
		if (!$username) {
			$this->message 		= JText::_('COM_PAYCART_BUYER_IS_NOT_EXIT');
			$this->message_type	= Paycart::MESSAGE_TYPE_WARNING;
			
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
			$this->message 		= JText::_('COM_PAYCART_BUYER_FAIL_TO_LOGIN');
			$this->message_type	= Paycart::MESSAGE_TYPE_WARNING;
			
			return false;
		} 
		
		$loggedin_user =  PaycartFactory::getUser();
		
		$buyer = $this->cart->getParam('buyer', new stdClass());	
		$buyer->email 	= $loggedin_user->get('email');
		
		//set it not guest checkout
		$this->cart->setIsGuestCheckout(false);
		
		// set buyer 
		$this->cart->setParam('buyer', $buyer);

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
	protected function _do_loginWithEmail(Array $form_data)
	{
		$buyer = $this->cart->getParam('buyer', new stdClass());
		
		$buyer->email 	= $form_data['email'];
		

		$this->cart->setParam('buyer', $buyer);
		
		// guest checkout enabled on this cart 
		$this->cart->setIsGuestCheckout(true);
	
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
	protected function step_address(Array $form_data)
	{
		// get Billing Address
		
		// copy variable
		$billing_to_shipping	=	isset($form_data['billing_to_shipping']) ? (bool)$form_data['billing_to_shipping'] : false;
		$shipping_to_billing	=	isset($form_data['shipping_to_billing']) ? (bool)$form_data['shipping_to_billing'] : false;
		
		$billingaddress_id 	= 	0;
		$shippingaddress_id	=	0;
		
		$billing_address_data 	=	new stdClass();
		$shipping_address_data	=	new stdClass();
		
		//if buyer is loggin then might be user select address into existing address
		if ($this->_is_loggedin() ) {
			$buyer_id = PaycartFactory::getUser()->get('id');
			
			//if user selected address into existing address then get buyer address data and set into cart param 
			$billingaddress_id	=	isset($form_data['billingaddress_id']) ? (bool)$form_data['billingaddress_id'] : false;
			$shippingaddress_id	=	isset($form_data['shippingaddress_id']) ? (bool)$form_data['shippingaddress_id'] : false;
			
			//@PCTODO ::  Cross check this address binded with loggedin user id
			
			$data = Array();
			
			//get billing address data
			if ( $billingaddress_id ) {
				$data = PaycartBuyeraddress::getInstance($billingaddress_id)->toArray();
			}
			
			$billing_address_data = (object)($data);
			
			// get shipping address data
			if ( $shippingaddress_id ) {
				$data = PaycartBuyeraddress::getInstance($billingaddress_id)->toArray();
			}
			
			$shipping_address_data = (object)($data);
			
		}
		
		// if no need to copy from shipping to billing OR billing address data is not available in earliar processing
		// its means billing data is posted (in req data) 
		if ( !$shipping_to_billing || empty($billing_address_data) ) {
			// get billing address details
			$billing_address_data 				=	(object)$form_data['billing'];
			$billing_address_data->buyer_id		=	$this->cart->getBuyer();
		}

		// if no need to copy from billing to shipping OR shipping address data is not available in earliar processing
		// its means shipping data is posted (in req data)
		if ( !$billing_to_shipping || empty($shipping_address_data) ) {
			// get billing address details
			$shipping_address_data 				=	(object)$form_data['shipping'];
			$shipping_address_data->buyer_id	=	$this->cart->getBuyer();
		}
		
		//copy address data one entity to another entity
		if ($billing_to_shipping) {
			$shipping_address_data	= 	clone $billing_address_data;
		} else if ($shipping_to_billing) {
			$billing_address_data	=	clone $shipping_address_data;
		}
		
		//set address on cart-param 
		$this->cart->setParam('billing_address', 		$billing_address_data);
		$this->cart->setParam('shipping_address', 		$shipping_address_data);
		$this->cart->setParam('billing_to_shipping', 	$billing_to_shipping);
		$this->cart->setParam('shipping_to_billing', 	$shipping_to_billing);
		
		return true;
	}
	
	/**
	 * Step Confirm execute here.
	 * 		- create Invoice here
	 * @param Array $form_data Post data 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return true , if successfully process
	 */
	protected function step_confirm(Array $form_data)
	{	
		try{
			$this->cart->confirm();
		}catch (Exception $ex) {
			$this->message 		= $ex->getMessage();
			$this->messageType 	= Paycart::MESSAGE_TYPE_ERROR;
			return false;
		}
		
		return true;
	}
	
	/**
	 * Step Payment execute here.
	 * 		- Process Payment  
	 * 	
	 * @param Array $form_data Post data 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return true , if successfully process
	 */
	protected function step_payment(Array $form_data)
	{	
		return true;
	}
	
	
	public function goBack()
	{
		$back_to = $this->input->get('back_to');
		
		switch ($back_to) 
		{
			case 'email_address':
				$this->step_current = Paycart::CHECKOUT_STEP_LOGIN;
				break;
				
			case 'billing_address':
			case 'shipping_address':
				$this->step_current = Paycart::CHECKOUT_STEP_ADDRESS;
			break;
			
			default:
				;
			break;
		} 
		
		
		$this->getView()->set('step_ready', $this->step_current);
		$this->getView()->set('cart', 		$this->cart);
			
		return true;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_AbstractController::getModel()
	 */
	public function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}
	
	/**	
	 * JSON Task. 
	 * @return : return Payment form html
	 */
	public function getPaymentFormHtml() 
	{
		$gateway_id	= $this->input->get('paymentgateway_id', 0 );
		
		if ( empty($gateway_id) ) {
			
			$this->getView()->set('message', 		JText::_('Processor-Required'));
			$this->getView()->set('message_type', 	Paycart::MESSAGE_TYPE_ERROR);
			
			return true;
		}
		
		$this->cart->updateInvoiceProcessor($gateway_id);
		
		$this->getView()->set('cart', 		$this->cart);
		
		return true;
	}
	
	/**	
	 * JSON Task. 
	 * @return 
	 */
	public function checkout() 
	{
		// @PCTODO :: need discussion, If any notice or warning apear then we can not parse json string on client end
		// and we are unable to take payment .
		try {
			
			//register user, if guest checkout  
			if ($this->cart->getIsGuestCheckout()) {
				$this->_do_emailCheckout();
			}
			
			// store all params data
			$this->cart->checkout();
			
		} catch (Exception $ex) {
			
			$this->getView()->set('message', 		$ex->getMessage());
			$this->getView()->set('message_type', 	Paycart::MESSAGE_TYPE_ERROR);
			return true;
		}
		
		$this->getView()->set('cart', 		$this->cart);
		
		return true;
	}
	
	/**
	 * Email Checkout by user email-id
	 *  - Create new account if user is not exist
	 *  - Or get User id from existing db if user already register
	 *
	 * @since	1.0
	 * @author	Manish
	 * @return 	true if buyer successfully registered 
	 */
	protected function _do_emailCheckout()
	{
		$buyer = $this->cart->getParam('buyer', new stdClass());
		
		/* @var PaycartHelperBuyer */
		$buyer_helper = PaycartFactory::getHelper('buyer');
		
		//check user already exist or not
		$username	= $buyer_helper->getUsername($buyer->email);
		
		if($username) {
			//user already exist
			$user_id = JUserHelper::getUserId($username);
		} else {
			// Create new account
			$user_id = $buyer_helper->createAccount($buyer->email);
		}
		
		// fail to get user-id
		if (!$user_id) {
			throw new RuntimeException(JText::_('COM_PAYCART_CHECKOUT_FAIL_TO_PROCESS_EMAIL_CHECKOUT'));
		}
		
		$buyer->id 		= $user_id;

		// set buyer 
		$this->cart->setParam('buyer', $buyer);
			
		return true;
	}
	

}