<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		mManish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerState extends PaycartController 
{	
	/**
	 * Ajax Call create new state
	 * @see /plugins/system/rbsl/rb/rb/Rb_Controller::save()
	 */
	public function save() 
	{
		$post = $this->input->post->get('paycart_state_form', array(), 'array');
		
		// language data
		$post['lang_code'] = PaycartFactory::getLanguage()->getTag();
		
		// validation will be done on Model
		$entity =  $this->getModel()->save($post, $this->_getId());

		//@PCTODO : dont use hardcoded entry
		$country_id	=	$post['country_id'];
		
		//perform redirection
		$redirect  = "index.php?option=com_paycart&view=country&task=edit&id={$country_id}";
		
		// if buyeraddress isn't saved succesfully  
		if(!$entity) {			
			$ajax 	  = Rb_Factory::getAjaxResponse();
			$response = Array('message' => '');
			$response['message'] =	'State was failed to save';
			$callback 			 =	'paycart.admin.state.add.error';
			$redirect			 =	'';
			
			// set call back function
			$ajax->addScriptCall($callback, json_encode($response));
		}
				
		$this->setRedirect( $redirect , $this->getMessage(), Paycart::MESSAGE_TYPE_WARNING);

		// no need to move at view
		return false;
	}
	
	/**
	 * Ajax Call to edit state
	 * @see /plugins/system/rbsl/rb/rb/Rb_Controller::edit()
	 */
	public function edit() 
	{
		// Id required in View
		$this->getModel()->setState('country_id', $this->input->get('country_id'));
		return true;
	}
	
	/**
	 * Ajax Call to delete state
	 * @see /plugins/system/rbsl/rb/rb/Rb_Controller::remove()
	 */
	public function remove()
	{
		$state_id		=	$this->_getId();
		$state_details 	=	$this->getModel()->loadrecords();
		$country_id		=	$state_details[$state_id]->country_id;
		
		//perform redirection
		$redirect  = "index.php?option=com_paycart&view=country&task=edit&id={$country_id}";
		
		// if buyeraddress is not successfully removed 
		if(!$this->getModel()->delete($this->_getId())) {	
			$response = Array('message' => '');
			$ajax     = Rb_Factory::getAjaxResponse();
		
			// default callback method
			$response['message'] =	'State was failed to remove';
			$callback 			 =	'paycart.admin.state.remove.error';
			$redirect			 =	'';
			
			//set call back function
			$ajax->addScriptCall($callback, json_encode($response));
		}
		
		$this->setRedirect( $redirect);
		
		return false;
	}
}