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
 * Admin Ajax View for Acymailing
 * 
 *  
 * @author Garima Agal
 */
require_once dirname(__FILE__).'/view.php';

class PaycartAdminJsonViewAcymailing extends PaycartAdminBaseViewAcymailing
{	
	
	public function saveCategoryList()
	{
		if($this->get('success', false)){			
			$response = array('success' => true);
			$response['message'] = JText::_('PLG_PAYCART_ACYMAILING_LIST_SAVE_SUCCESS');
		}
		else{
			$response = array('success' => false);				
			$response['message'] = JText::_('PLG_PAYCART_ACYMAILING_LIST_SAVE_ERROR');
		}
		$this->assign('json', $response);
		
		return true;	
	}
}