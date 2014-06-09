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
		// @NOTE:: Very necessary thing
		// If user is cuming from outside then ALWAYS call init function. Ok  
		// If user was doing guest chekout an movie anywhere on site then Checkout always start with First Step  
		//@PCFIXME :: Check cart exist or not
		$this->cart->setIsGuestCheckout(false);

		// if user login then move next step
		if ($this->_is_loggedin())  {
			$this->step_current = $this->step_sequence[$this->step_current];
		}
		
		if (!$this->preProcess()) {
			// @PCTODO :: Show error page 
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
		
		$is_processed = false;
		
		if ($this->preProcess($form_data)) {
			//2#. 	Steps process
			$is_processed	=	$this->stepProcess($form_data);
		}
		
		//3#.	Post-Processing
		
		// Cart should be save after processing
		$this->cart->save();

		// if step successfully process
		if ($is_processed) {
			$this->getView()->set('step_ready', $this->step_sequence[$this->step_current]);
			$this->getView()->set('cart', 		$this->cart);
			return true;
		}
		
		//error handling
		$ajax_response = PaycartFactory::getAjaxResponse();
		$response_data = Array();
		$response_data['messgae'] 		= $this->message;
		$response_data['messgae_type'] 	= $this->message_type;

		$ajax_response->addScriptCall('paycart.notification', $response_data);
		
		// return false no need to execute view 
		return false;
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
		if ( !($this->cart instanceof PaycartCart)  || count($this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT)) ) {
			$this->message			=	JText::_('COM_PAYCART_CART_NOT_EXIST');
			$this->message_type		=	Paycart::MESSAGE_TYPE_ERROR;
			//throw new RuntimeException(JText::_('COM_PAYCART_CART_NOT_EXIST'));
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
		
		$user_id = 0;
		
		if ($form_data['emailcheckout'] ) {
			// email checkout
			$user_id = $this->_do_emailCheckout($form_data);
		} else {
			//checkout by login
			$user_id = $this->_do_login($form_data);
		}
		
		// if do not get any user id 
		if ( !$user_id ) {
			return false;
		}
		
		$this->cart->setBuyer($user_id);	
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
		return PaycartCart::getInstance(1);
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
		
		//set it not guest checkout
		$this->cart->setIsGuestCheckout(false);
		
		return PaycartFactory::getUser()->get('id');
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
	protected function _do_emailCheckout(Array $form_data)
	{
		/**
		 * @var PaycartHelperBuyer
		 */
		$buyer_helper = PaycartFactory::getHelper('buyer'); 
		
		//check user already exist or not
		$username	= $buyer_helper->getUsername($form_data['email']);
		
		if($username) {
			//user already exist
			$user_id = JUserHelper::getUserId($username);
		} else {
			// Create new account 
			$user_id = $buyer_helper->createAccount($form_data['email']);
		}
		
		// fail to get user-id
		if (!$user_id) {
			$this->message	= 	JText::_('COM_PAYCART_CHECKOUT_FAIL_TO_PROCESS_EMAIL_CHECKOUT');
			$this->error	=	Paycart::MESSAGE_TYPE_ERROR;
			return false;	
		}
		
		$this->cart->setIsGuestCheckout(true);
		
		// we will always remove address when doing email checkout
		$this->cart->setBillingAddressId(0);
		$this->cart->setShippingAddressId(0);
		
		return $user_id;
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
		// Step 1# :: get Billing Address
		
		// copy variable
		$billing_to_shipping	=	isset($form_data['billing_to_shipping']) ? (bool)$form_data['billing_to_shipping'] : false;
		$shipping_to_billing	=	isset($form_data['shipping_to_billing']) ? (bool)$form_data['shipping_to_billing'] : false;
		
		// if select any billing address
		$billingaddress_id		=	isset($form_data['billingaddress_id']) ? $form_data['billingaddress_id'] : 0;
		
		
		// if billing address id is not available and not copy shipping to billing 
		// then I assume that buyer entered new address
		if (!$billingaddress_id && ! $shipping_to_billing ) {
			// store billing address
			$billing_address_data 				=	$form_data['billing'];
			$billing_address_data['buyer_id']	=	$this->cart->getBuyer();
			
			$billingaddress_lib	= PaycartBuyeraddress::getInstance(0, $billing_address_data)->save();
			$billingaddress_id		= $billingaddress_lib->getId();
		}
		
		// Step 2# :: Get Shipping Address
		
		// if select any Shipping address
		$shippingaddress_id		=	isset($form_data['shippingaddress_id']) ? $form_data['shippingaddress_id'] : 0;
		
		// if shipping address id is not available and not copy billing to shipping 
		// then I assume that buyer entered new address
		if (!$shippingaddress_id && !$billing_to_shipping ) {
			
			// store shipping address
			$shipping_address_data 				=	$form_data['shipping'];
			$shipping_address_data['buyer_id']	=	$this->cart->getBuyer();
			
			$shippingaddress_lib	= PaycartBuyeraddress::getInstance(0, $shipping_address_data)->save();
			$shippingaddress_id		= $shippingaddress_lib->getId();
		}
		
		
		// get shipping to billing checkbox value if same address 
		
		if ($shipping_to_billing) {
			$billingaddress_id = $shippingaddress_id;
		} 
		
		if ($billing_to_shipping) {
			$shippingaddress_id	=	$billingaddress_id;
		}
		
		// Set shipping address on cart
		$this->cart->setShippingAddressId($shippingaddress_id);
		
		// Set billing address on cart
		$this->cart->setBillingAddressId($billingaddress_id);
		
		return true;
	}
	
	/**
	 * Step Confirm execute here.
	 * 		- 
	 * @param Array $form_data Post data 
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return true , if successfully process
	 */
	protected function step_confirm(Array $form_data)
	{	
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

}