<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Base Controller
* @author Team Readybytes
 */
class PaycartController extends Rb_Controller
{
	public $_component = PAYCART_COMPONENT_NAME;	
	
	/**
	 * Saves an item (new or old)
	 * @TODO:: should be protected.
	 */
	public function _save(array $data, $itemId=null)
	{		
		if($this->__validate($data, $itemId) === false){
			return false;
		}
		
		// if validation is successfull then save the data
		return parent::_save($data, $itemId);
	}
	
	public function save()
	{
		$entity = parent::save();
		// Multilingual : ned to set current url once data is saved (apply)
		// no need to apply is for saveNclose and saveNnew
		if($this->input->get('task', '') == 'apply'){
			$redirect = $this->getRedirect().'&lang_code='.PaycartFactory::getPCCurrentLanguageCode();
			$this->setRedirect($redirect);
		}
		
		return $entity;
	}
	
	public function _populateModelState()
	{
		parent::_populateModelState();
		
		// set the current current language 
		// if it is in url then set it otherwise set joomla's language
		$this->setCurrentLanguage();
	}
	
	public function setCurrentLanguage()
	{
		static $done = false;
		if($done){
			return true;
		}
		
		$app 		= PaycartFactory::getApplication();
		
		//#1 : Get language from URL
		$lang_code 	= $app->input->get('lang_code', '');	
		
		//#2 : If lang_code is empty then get it from post data
		if(empty($lang_code)){
			$post = $this->input->post->get($this->getControlNamePrefix(), array(), 'array');
			if(isset($post['lang_code']) && !empty($post['lang_code'])){
				$lang_code = $post['lang_code'];
			}			
		}
		
		// Error if language is not supported
		$supported_lang = PaycartFactory::getPCSupportedLanguageCode();
		
		if(count($supported_lang) > 1){
			define('PAYCART_MULTILINGUAL', true);
		}
		else{
			define('PAYCART_MULTILINGUAL', false);
		}
		
		if($lang_code && !in_array($lang_code, $supported_lang)){
			// @PCTODO : should throw exception ??
		}
		
		
		//#3 : If lang_code is empty then get Joomla's default language
		if(empty($lang_code)){
			$lang_code = PaycartFactory::getJoomlaCurentLanguageCode();
		}
		
		//#4 : If lang_code is not supported, then get Paycart's default language
		if(!in_array($lang_code, $supported_lang)){
			$lang_code = PaycartFactory::getPCDefaultLanguageCode();
		}
					
		PaycartFactory::setPCCurrentLanguageCode($lang_code);		
		
		// set $done to true, so that it won't be processed more than one time
		$done = true;
	}
	
	public function getControlNamePrefix()
	{
		return $this->_component->getNameSmall().'_'.$this->getName().'_form';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_AbstractController::getView()
	 */
	public function getView($name='', $format='', $prefix = '', $config = array())
	{
		if(empty($name)){
			$name 	= $this->getName();
		}
		
		if(empty($prefix)){
			$prefix = $this->getPrefix();
		}
		
		if (!$format) {
			$format = RB_REQUEST_DOCUMENT_FORMAT;
		}
		
		$view = $format.'View';
		
		//get Instance from Factory
		$view = Rb_Factory::getInstance($name, $view, $prefix);	

		return $view;
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
	protected function _dologin(Array $form_data, &$errors = array())
	{
		if(isset($form_data['email'])){
			$input_username = $form_data['email'];
		}
		elseif(isset($form_data['username'])){
			$input_username = $form_data['username'];
		}
		else{
			$input_username = '';
		}
		
		// get user
		$user = PaycartFactory::getHelper('buyer')->getUser( $input_username, 'email');
		if(!$user){
			$user = PaycartFactory::getHelper('buyer')->getUser( $input_username, 'username');
		}
		
		if (!$user) {
			$error = new stdClass();
			$error->message 		= JText::_('COM_PAYCART_BUYER_IS_NOT_EXIT');
			$error->message_type	= Paycart::MESSAGE_TYPE_ERROR;
			$error->for				= 'email';
			$errors[] = $error;
			return false;			
		}
		
		// prepare credential data
		$credentials				=	Array();
		$credentials['username']	=	$user->username;
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
		
		return true; 
	}
}
