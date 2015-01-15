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
 * Admin Ajax View for Media
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminJsonViewMedia extends PaycartAdminBaseViewMedia
{	
	public function save()
	{
		if($this->get('success', false)){			
			$response = array('success' => true);
			$response['message'] = JText::_('COM_PAYCART_ADMIN_SUCCESS_ITEM_SAVE');
		}
		else{
			$response = array('success' => false);				
			$response['message'] = JText::_('COM_PAYCART_ADMIN_ERROR_ITEM_SAVE');
		}
		$this->assign('json', $response);
		return true;
	}
}