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
 * state Controller
 * @author Manish
 *
 */

class PaycartSiteControllerState extends PaycartController 
{
	/**
	 * 
	 * Ajax Task
	 * @return html which contain all states options
	 */
	public function getOptions() 
	{
		$country_id	=	$this->input->get('country_id', 0, 'array');
		if(is_array($country_id) && count($country_id) == 1){
			$country_id = array_shift($country_id);
		}
		
		$default_selected_state_id	=	$this->input->get('default_state', array(), 'Array');

		// get raw strin without any filter
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
			$filter['country_id'] = array('IN', '('.implode(',', $country_id)); 
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