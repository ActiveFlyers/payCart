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
		
		// validation will be done on Model
		$entity =  $this->getModel()->save($post, $this->_getId());

		//@PCTODO : dont use hardcoded entry
		$country_id	=	$post['country_id'];
		
		//perform redirection
		$redirect  = "index.php?option=com_paycart&view=country&task=edit&id={$country_id}&lang_code=".PaycartFactory::getPCCurrentLanguageCode();
		
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
		$redirect  = "index.php?option=com_paycart&view=country&task=edit&id={$country_id}&lang_code=".PaycartFactory::getPCCurrentLanguageCode();
		
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
	
	
/**
	 * 
	 * Ajax Task
	 * @return html which contain all states options
	 * Similar to PaycartSiteControllerState->getOptions
	 */
	public function getOptions() 
	{
		$country_id	=	$this->input->get('country_id', 0, 'array');
		if(is_array($country_id) && count($country_id) == 1){
			$country_id = array_shift($country_id);
		}
		
		$default_selected_state_id	=	$this->input->get('default_state', array(), 'Array');

		// get raw string without any filter
		$selector	=	$this->input->get('state_selector', NULL, 'RAW');
		
		$ajax_response = PaycartFactory::getAjaxResponse();
		
		if(!$selector) {
			$ajax_response->addScriptCall
					(	'console.log', 
						Array('message' 		=> 	JText::_('State selector is not available here'),
							  'message_type'	=>	Paycart::MESSAGE_TYPE_ERROR )
					);
			return false;
		}
		
		if(!$country_id) {
			PaycartFactory::getAjaxResponse()->addScriptCall('paycart.address.state.html', Array('state_selector' => $selector, 'state_option_html' => ''));
			return false;
		}	
		
		//@PCTODO:: Sorting required 
		// limit must be cleaned other wise only specific number of record will fetch
		if(is_array($country_id)){
			$filter['country_id'] = array(array('IN', '("'.implode('","', $country_id).'")')); 
					}
		else{
			$filter['country_id'] = $country_id;
		}
		
		$states = PaycartFactory::getModel('state')->loadRecords($filter, Array('limit'));
		
				
		$country_states = array();		
		foreach ($states as $state_id => $state_detail) {
			if(!isset($country_states[$state_detail->country_id])){
				$country_states[$state_detail->country_id] = '';
			}
			
			$selected = '';
			if (in_array($state_id, $default_selected_state_id)) {
				$selected = 'selected="selected"';
			}
			$country_states[$state_detail->country_id] .= "<option value='{$state_detail->state_id}'  $selected > {$state_detail->title} </option>";
		}
		
		$html = '';
		if(count($country_states) > 1){
			$formatter = PaycartFactory::getHelper('format');
			foreach($country_states as $country_id => $stateshtml){
				$html .= '<optgroup label="'.$formatter->country($country_id).'">'.$stateshtml.'</optgroup>';
			}
		}
		else{
			$html = $country_states[$country_id];
		}
		
		PaycartFactory::getAjaxResponse()->addScriptCall('paycart.address.state.html', Array('state_selector' => $selector, 'state_option_html' => $html));
		
		return false;
	}
}