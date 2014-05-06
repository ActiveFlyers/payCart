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
		$post = $this->input->post->get('paycart_form', array(), 'array');
		
		// language data
		$post['lang_code'] = PaycartFactory::getLanguage()->getTag();
		
		// validation will be done on Model
		$entity =  $this->getModel()->save($post, $this->_getId());
		
		$response = Array('message' => '');
		
		$ajax = Rb_Factory::getAjaxResponse();
	
		// default callback method
		$response['message'] =	'//PCTODO: Oops!! state fail to save. :(';
		$callback 			 =	'paycart.admin.state.add.error';
		$redirect			 =	'';

		//@PCTODO : dont use hardcoded entry
		$country_id	=	$post['country_id'];
		
		// if buyeraddress succesfully save  
		if($entity) {			
			$response['message']	= '//PCTODO: GOOD!! State successfully save. Now you need to fetch state html and append into state template ';
			$callback 				= 'paycart.admin.state.add.success';
	
			//perform redirection
			$redirect  = "index.php?option=com_paycart&view=country&task=edit&id={$country_id}";
			
		}
		
		// set call back function
		$ajax->addScriptCall($callback, json_encode($response)); 
		
		$this->setRedirect( $redirect , $this->getMessage(), $msgType);

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
		$response = Array('message' => '');
		
		$ajax = Rb_Factory::getAjaxResponse();
	
		// default callback method
		$response['message'] =	'//PCTODO: Oops!! state fail to remove. :(';
		$callback 			 =	'paycart.admin.state.remove.error';
		$redirect			 =	'';

		$state_id		=	$this->_getId();
		$state_details 	=	$this->getModel()->loadrecords();
		$country_id		=	$state_details[$state_id]->country_id;
		
		// if buyeraddress succesfully save  
		if($this->getModel()->delete($this->_getId())) {			
			$response['message']	= '//PCTODO: GOOD!! State successfully deleted. ';
			$callback 				= 'paycart.admin.state.remove.success';
	
			//perform redirection
			$redirect  = "index.php?option=com_paycart&view=country&task=edit&id={$country_id}";
			
		}
		
		// set call back function
		$ajax->addScriptCall($callback, json_encode($response)); 
		
		$this->setRedirect( $redirect);
		
		return false;
	}
}