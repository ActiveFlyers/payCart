<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @author		mManishTrivedi
* @contact		support+paycart@readybytes.in
* 
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/**
 * 
 * Account Controller 
 *
 */

class PaycartSiteControllerAccount extends PaycartController 
{
	public function getModel($name = '', $prefix = '', $config = array())
	{
		if(in_array($this->getTask(), array('order', 'display'))){
			$this->_defaultOrderingDirection = 'DESC';
			return parent::getModel('cart');
		}
		
		return null;
	}
	
	private function __checkLogin()
	{
		$user_id = JFactory::getUser()->id;
		if(!$user_id){
			$this->setRedirect(JRoute::_('index.php?option=com_paycart&view=account&task=login'));
			return false;
		}
		
		return true;
	}
	
	function display($cachable = false, $urlparams = array())
	{	
		// need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		return true;
	}
	
	function order()
	{
		$id = $this->input->get('order_id', 0, 'INT');		
		$user = JFactory::getUser();
		
		// need not to do anything is user is not logged in and no $id is set
		if(!$id){
			if(!$user->id){
				$this->__checkLogin();
				return false;
			}
		}		
		else{
			$cart = PaycartCart::getInstance($id);
			if(!$cart->isLocked()){
				$msg = JText::_('COM_PAYCART_ACCOUNT_ORDER_NOT_PLACED');
				$this->setRedirect(JRoute::_('index.php?option=com_paycart&view=productcategory&task=display'), $msg, 'error');			
			}		
			
			if(!$user->id){				
				$cart_key = $cart->getSecureKey();
				$key = $this->input->get('key', 0, 'STRING');
		
				if($key !== $cart_key){
					$this->__checkLogin();
					$this->setMessage(JText::_('COM_PAYCART_ACCOUNT_ORDER_INVALID_SECURE_KEY'), 'error');
					return false;		
				}
			}
			else{
				if($cart->getBuyer() != $user->id){
					$msg = JText::_('COM_PAYCART_ACCOUNT_ORDER_UNAUTHORIZED');
					$this->setRedirect(JRoute::_('index.php?option=com_paycart&view=account&task=display'), $msg, 'error');
					return false;
				}
			}
		}
		
		
		$this->getView()->assign('order_id', $id);
		return true;
	}
	
	function setting()
	{
		// need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		return true;
	}
	
	public function update()
	{	
		// need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		$post 	= $this->input->post->get('paycart_account_form', array(), 'array');
		$entity = $this->input->get('entity', '');		
		
		$user 	= JFactory::getUser();
		
		$model = PaycartFactory::getInstance('buyer', 'model');
		
		if($entity == 'info'){
			$errorFields = $model->validateFormData($post, $user->id);
				
			$msg = JText::_('COM_PAYCART_ACCOUNT_INFORMATION_NOT_SAVED');
			$type= "error";
			if(!count($errorFields)){	
				if(PaycartBuyer::getInstance($user->id)->bind($post)->save()){
					$type= "success";
					$msg = JText::_('COM_PAYCART_ACCOUNT_INFORMATION_SAVED');
					if(isset($post['realname']) && !empty($post['realname'])){
						$user->name = $post['realname'];
						$user->save();
					}
				}			
			}	
		}
		elseif($entity == 'password'){			
			if(isset($post['current_password']) && !empty($post['current_password'])){
				$msg  = JText::_('COM_PAYCART_ACCOUNT_INFORMATION_PASSWORD_CHANGED');
				$type = "success";
				if(!JUserHelper::verifyPassword($post['current_password'], $user->password, $user->id)){
					$type= "error";
					$msg = JText::_('COM_PAYCART_ACCOUNT_PASSWORD_DO_NOT_MATCH');
				}
				else{
					$data = array();
					$data['password']  = $post['new_password'];
					$data['password2'] = $post['retype_new_password'];
					if(!$user->bind($data)){
						$errors = $user->getErrors();
						$app = PaycartFactory::getApplication();					
						$msg = array_shift($errors);
						$type = 'warning';
					}	
					else{
						$user->save();
					}	
				}
			}
			else{
				$msg  = JText::_('COM_PAYCART_ACCOUNT_INFORMATION_EMPTY_PASSWORD');
				$type = "success";	
			}
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_paycart&view=account&task=setting'), $msg, $type);
		return false;
	}
	
	function address()
	{
		// need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		return true;
	}
	
	public function addNewAddress()
	{
		// 	need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		return true;
	}
	
	public function setDefaultAddress()
	{
		// 	need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		$address_id = $this->input->get('address_id');
		$buyer_id 	= JFactory::getUser()->id;		
		$view 		= $this->getView();
		$view->assign('address_id', $address_id);
		
		$errors = array();
		$msg 	= '';
		$type 	= '';
		if($address_id){
			$buyer = PaycartBuyer::getInstance($buyer_id);
			$buyer->set('default_address_id', $address_id)->save();
			$msg = JText::_('COM_PAYCART_ACCOUNT_DEFAULT_ADDRESS_CHANGED');
			$type = "success";	
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_paycart&view=account&task=address'), $msg, $type);
		return false;
	}
	
	public function saveNewAddress()
	{
		// 	need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		$post 				= $this->input->post->get('paycart_buyeraddress_form', array(), 'array');
		$post['buyer_id'] 	= PaycartFactory::getUser()->id;
		
		//validate and filter the input data
		$model = PaycartFactory::getInstance('buyeraddress', 'model');
		// IMP : Need to merge the data, otherwise filter will remove the data which does not exist in form
		$post 		 = array_merge($post, $model->filterFormData($data, $itemId));
		$errorFields = $model->validateFormData($post, $itemId);
		
		if(!count($errorFields)){		
			if(PaycartBuyeraddress::getInstance()->bind($post)->save()){
				//perform redirection
				$redirect  = JRoute::_("index.php?option=com_paycart&view=account&task=address");
				$message = JText::_('COM_PAYCART_ACCOUNT_ADDRESS_ADDED');
				$this->setRedirect( $redirect , $message, 'success');
				return false;
			}
		}
		
		$response = Array('message' => '');
		$ajax     = Rb_Factory::getAjaxResponse();
		$response['message'] =	JText::_('COM_PAYCART_ADDRESS_NOT_ADDED');
		$callback 			 =	'paycart.account.address.error';
		$redirect			 =	'';
		// set call back function
		$ajax->addScriptCall($callback, json_encode($response));		
		// no need to move at view
		return false;		
	}
	
	public function removeAddress()
	{
		// need not to do anything is user is not logged in
		if(false === $this->__checkLogin()){
			return false;
		}
		
		$address_id = $this->input->get('address_id');
		$view = $this->getView();
		$view->assign('address_id', $address_id);
		
		$errors = array();
		if($address_id){
			$address = PaycartBuyeraddress::getInstance($address_id);
			if($address->set('is_removed', 1)->save()){
				return true;
			}
			else{			
				$error = new stdClass();
				$error->message 		= JText::_('COM_PAYCART_ACCOUNT_ADDRERSS_NOT_REMOVED');
				$error->message_type	= Paycart::MESSAGE_TYPE_WARNING;
				$error->for				= 'address';
				$errors[] = $error;
			}
		}
		else{			
			$error = new stdClass();
			$error->message 		= JText::_('COM_PAYCART_ACCOUNT_ADDRERSS_INVALID_ID');
			$error->message_type	= Paycart::MESSAGE_TYPE_WARNING;
			$error->for				= 'address';
			$errors[] = $error;
		}
		
		$view->assign('errors', $errors);
		return true;
	}
	
	public function login()
	{
		if(!PaycartFactory::getUser()->id){		
			$form_data = $this->input->get('paycart_account_loginform', Array(), 'ARRAY');
			if(empty($form_data)){
				$this->getView()->assign('action', $this->input->get('action', 'login'));
				return true;
			}
		
			$errors = array();
			$this->_dologin($form_data, $errors);
			// if errors
			if ( !empty($errors )) {
				$ajax = PaycartFactory::getAjaxResponse();
				$ajax->addScriptCall('paycart.account.login.error', $errors);
				$ajax->sendResponse();	
				return false;
			}		
		}
		
		$this->setRedirect(Jroute::_('index.php?option=com_paycart&view=account&task=display'));
		return false;
	} 
	
	public function guest()
	{
		$form_data = $this->input->get('paycart_account_guestform', Array(), 'ARRAY');
		
		$errors = array();
		if(!empty($form_data)){
			$email = $form_data['email'];
			$buyerhelper = PaycartFactory::getHelper('buyer');
			$buyer = $buyerhelper->getUser($email, 'email');
			
			if(!$buyer){
				$error = new stdClass();
				$error->message 		= JText::_('COM_PAYCART_ACCOUNT_ORDER_INVALID_BUYER');
				$error->message_type	= Paycart::MESSAGE_TYPE_WARNING;
				$error->for				= 'header';
				$errors[] = $error;
			}
			
			$cart_id = $form_data['order_id'];
			try{
				$cart = PaycartCart::getInstance($cart_id);
			}
			catch (Exception $e) {
				$error = new stdClass();
				$error->message 		= JText::_('COM_PAYCART_ACCOUNT_ORDER_INVALID_ID');
				$error->message_type	= Paycart::MESSAGE_TYPE_WARNING;
				$error->for				= 'header';
				$errors[] = $error;
			}
			
			if(empty($errors) && $cart->getBuyer() != $buyer->id){
				$error = new stdClass();
				$error->message 		= JText::_('COM_PAYCART_ACCOUNT_ORDER_INVALID_ID');
				$error->message_type	= Paycart::MESSAGE_TYPE_WARNING;
				$error->for				= 'header';
				$errors[] = $error;
			}
			
			$response = new stdClass();
			$response->isValid = true;
			if(!empty($errors)){
				$response->isValid = false;
				$response->errors = $errors;				
			}
			else{
				 //send notification 
	            $instance = PaycartNotification::getInstanceByEventname('onorderurlrequest', $cart->getLangCode());
	            if($instance instanceof PaycartNotification){
	            	$instance->sendNotification($cart);
	           	}           	
	           	
	           	$response->message = JText::_('COM_PAYCART_ACCOUNT_ORDER_URL_SENT');
			}
			
           	$ajax = PaycartFactory::getAjaxResponse();
			$ajax->addScriptCall('paycart.account.guest.response', $response);
			$ajax->sendResponse();
		}
		
		$ajax = PaycartFactory::getAjaxResponse();
		$ajax->sendResponse();
	}
	
	/**
     * Serve any file to download
     */
	function serveFile()
	{
		$cartId = $this->input->get('cart_id',0,'INT');
		$main    = $this->input->get('file_id',0,'STRING');
		
		if(!$cartId || !$main){
			JError::raiseError(404,"File doesn't exist");
		}
		$returnUrl = base64_decode($this->input->get('returnUrl'));
		$cart = PaycartCart::getInstance($cartId);
		if(!$cart->isLocked()){
			$this->setRedirect($returnUrl, JText::_('COM_PAYCART_ACCOUNT_ORDER_UNAUTHORIZED'), 'error');
			return false;
		}
		
		$user = JFactory::getUser();
		if(!$user->id){				
			$cart_key = $cart->getSecureKey();
			$key = $this->input->get('key', 0, 'STRING');
	
			if($key !== $cart_key){
				$this->setRedirect($returnUrl, JText::_('COM_PAYCART_ACCOUNT_ORDER_UNAUTHORIZED'), 'error');
				return false;			
			}
		}
		else{
			if($cart->getBuyer() != $user->id){
				$msg = JText::_('COM_PAYCART_ACCOUNT_ORDER_UNAUTHORIZED');
				$this->setRedirect(PaycartRoute::_('index.php?option=com_paycart&view=account&task=display'), $msg, 'error');
				return false;
			}
		}
		
		$mainId = (int)str_ireplace('file-', '', base64_decode($main));
		
		if(!$mainId){
			$this->setRedirect($returnUrl,  JText::_('COM_PAYCART_ACCOUNT_ORDER_UNAUTHORIZED'), 'error');
			return false;		
		}
		
		$media = PaycartMedia::getInstance($mainId);
		
		/* @var $helper PaycartHelperMedia */
		$helper = PaycartFactory::getHelper('media');
		$helper->download($media);		
	}
}