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
		$country_id	=	$this->input->get('country_id');
		// get raw strin without any filter
		$selector	=	$this->input->get('state_selector', NULL, 'RAW');
		
		$ajax_response = PaycartFactory::getAjaxResponse();
		
		if(!$country_id) {
			$ajax_response->addScriptCall
					(	'paycart.notification', 
						Array('message' 		=> 	JText::_('Country is not availble'),
							  'message_type'	=>	Paycart::MESSAGE_TYPE_ERROR )
					);
			return false;
		}
		
		if(!$selector) {
			$ajax_response->addScriptCall
					(	'paycart.notification', 
						Array('message' 		=> 	JText::_('State selector is not available here'),
							  'message_type'	=>	Paycart::MESSAGE_TYPE_ERROR )
					);
			return false;
		}
		
		//@PCTODO:: Sorting required 
		$states = PaycartFactory::getModel('state')->loadRecords(Array('country_id'=> $country_id));
		
		$html = '';
		
		foreach ($states as $state_id => $state_detail) {
			$html .= "<option value={$state_detail->isocode} > {$state_detail->title} </option>";
		}
		
		
		PaycartFactory::getAjaxResponse()->addScriptCall('paycart.address.state.html', Array('state_selector' => $selector, 'state_option_html' => $html));
		
		return false;
	}
}