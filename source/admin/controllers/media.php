<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );
/**
 * Admin Controller for Media
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartAdminControllerMedia extends PaycartController 
{
	
	public function _save(array $data, $itemId=null)
	{
		$ret = parent::_save($data, $itemId);
		
		// if it is a json request
		// e.g. product media save request by angularjs
		if($this->input->get('format', 'html') == 'json'){
			if($ret){
				$response = array('success' => true);
				$response['message'] = JText::_('COM_PAYCART_ADMIN_SUCCESS_ITEM_SAVE');
			}
			else{
				$response = array('success' => false);				
				$response['message'] = JText::_('COM_PAYCART_ADMIN_ERROR_ITEM_SAVE');
			}
			echo json_encode($response);
			exit;
		}		
		
		return $ret;
	}
}