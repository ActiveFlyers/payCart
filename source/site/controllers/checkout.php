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
	
	protected $step_current	=	'login';
	protected $step_next	=	'login';
	protected $message		=	'';
	protected $message_type	=	'';
	
	/**
	 * 
	 * Checkout Process initiate. 
	 */
	public function init()
	{
		//	check user is logged-in or guest.
		$this->step_next	=	'login'; 
		
		if ($this->_is_loggedin()) {
			//@PCTODO::
			$this->step_next	=	'address';
		}
		
		//	get cart on user bases (form user-id or session-id).
		
		
		// @PCTODO:: Check  minimum condition for Checkout-flow like minimum amount, mimimum product. 
		
		//	initiate steps on user bases.
		
		$this->getView()->set('step_ready', $this->step_next);
		return true;
	} 
	
	/**
	 * 
	 * Enter description here ...
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
	 * 
	 * Process whole chekout flow by this method
	 */
	public function process()
	{
		//1#.	Pre-Process
		$form_data = $this->input->get('paycart_form',Array(), 'ARRAY');
		
		$this->step_current = $this->input->get('step_name');

		//@TODO :: event fire
		
		//2#. 	Steps process
		$is_processed = false;
		
		switch ($this->step_current) 
		{
			case 'login':
				$is_processed = $this->step_login($form_data);
				break;
			
			default:
				// unknown step. 
				// @PCTODO:: Generate error
				return false;
			
		}
		
		//3#.	Post-Processing

		// if step successfully process
		if ($is_processed) {
			$this->getView()->set('step_ready', $this->step_next);
			return true;
		}
		
		//@PCTODO:: Distinct warning and error
		//error handling
		$ajax_response = PaycartFactory::getAjaxResponse();
		$response_data = Array();
		$response_data['messgae'] = $this->message;

		$ajax_response->addScriptCall('paycart.checkout.submit.error', json_encode($response_data));
		
		// return false no need to execute view 
		return false;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	protected function step_login(Array $form_data)
	{
		$func = '_do_login';
		
		// Checkout by email
		if ($form_data['emailcheckout'] ) {
			$func = '_do_emailCheckout';
		}
		
		$this->step_next = 'address';
		
		if ( !$this->$func($form_data) ) {
			$this->step_next = 'login';
			
			return false;
		}
		
		$this->step_next = 'address';
		
		return true;
	}
	
	
	protected function _get_cart() 
	{
		// get current session id
		$session_id = PaycartFactory::getSession()->getId();
		;
	}
	
	protected function _do_login(Array $form_data)
	{
		$email = $form_data['email'];
		
		// @PCTFIXME :: move to buyer helper
		$db = PaycartFactory::getDbo();
		
		$query = new Rb_Query();
		$query->select('username')
			  ->from('#__users')
			  ->where('`email` = '.$db->quote($email), 'OR');
//			  ->where('`username` = '.$db->quote($email));
			  
		$username = $query->dbLoadQuery()->loadResult();
		
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
		
		return true;
	} 
	
	protected function _do_emailCheckout()
	{
		;
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