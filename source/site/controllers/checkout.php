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
	protected $step_next	=	Paycart::CHECKOUT_STEP_LOGIN;

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
		//@TODO :: count number of particular
		// if cart is not exist or cart is empty then intimate to end user 
		if ( !($this->cart instanceof PaycartCart) || $this->cart->getCartparticulars()) {
			//@TODO :: cart is empty
		} 
		
		//	check user is logged-in or guest.
		if ($this->_is_loggedin()) {
			//next step get form $this->step_next
			$this->step_next	=	$this->step_sequence[$this->step_next];
		}
		
		// @PCTODO:: Check  minimum condition for Checkout-flow like minimum amount, mimimum product. 
		
		$this->getView()->set('step_ready', $this->step_next);
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
		
		//@FIXME :: cart should be save after processing

		// if step successfully process
		if ($is_processed) {
			$this->step_next	=	$this->step_sequence[$this->step_current];
			
			$this->getView()->set('step_ready', $this->step_next);
			return true;
		}
		
		//error handling
		$ajax_response = PaycartFactory::getAjaxResponse();
		$response_data = Array();
		$response_data['messgae'] 		= $this->message;
		$response_data['messgae_type'] 	= $this->message_type;

		$ajax_response->addScriptCall('paycart.checkout.notification', json_encode($response_data));
		
		// return false no need to execute view 
		return false;
	}
	
	/**
	 * Pre-Process current step
	 * @param array $form_data
	 * 
	 * @since	1.0
	 * @author 	Manish
	 * 
	 * @return bool true if successfully validate
	 */
	protected function preProcess(Array $form_data )
	{
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
		
		if ($form_data['emailcheckout'] ) {
			// email checkout
			$is_processed = $this->_do_emailCheckout($form_data);
		} else {
			//checkout by login
			$is_processed = $this->_do_login($form_data);
		}
		
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
		
		return PaycartCart::getInstance(0, $cart_data);
	}
	
	/**
	 * Login user by their username and pwd
	 * @param array $form_data = Array('email' => _EMAIL_ID_, 'password'=> _PASSWORD_ )
	 * 
	 * @since 	1.0
	 * @author 	Manish
	 * 
	 * @return true if successfully login
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
		
		//@TODO:: user-id set on cart
		
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
	 * @return true if successfully registered
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
		
		//@TODO:: user-id set on cart
		
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
		// store shipping address
		$shipping_address_data 				=	$form_data['shipping'];
		$shipping_address_data['buyer_id']	=	$this->cart->getBuyer();
		
		// shave buyer-address
		$shipping_address_lib 	= 	PaycartBuyeraddress::getInstance(0, $shipping_address_data)->save();
		$shipping_address_id	=	$shipping_address_lib->getId();
		//@PCFIXME : set shipping address on cart
		//$this->cart->setShippingAddressId($shipping_address_id);
		
		// get shipping to billing checkbox value if same address 
		
		if ((bool)$form_data['shipping_to_billing']) {
			$billing_address_id = $shipping_address_id;
		} else {
			// store billing address
			$billing_address_data 				=	$form_data['billing'];
			$billing_address_data['buyer_id']	=	$this->cart->getBuyer();
			
			$billing_address_lib	= PaycartBuyeraddress::getInstance(0, $billing_address_data)->save();
			$billing_address_id		= $billing_address_lib->getId();
		}
		
		//@PCFIXME :: set billing address on cart
		//$this->cart->setBillingAddressId($billing_address_id);
		
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
		// 
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