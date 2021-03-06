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
 * Admin json view of config
 * 
 * @since 1.0.0
 *  
 * @author Rimjhim Jain
 */
require_once dirname(__FILE__).'/view.php';

class PaycartAdminJsonViewConfig extends PaycartAdminBaseViewConfig
{	
	public function deleteCompanyLogo()
	{		
		if($this->get('success', false)){			
			$response = array('success' => true);
			$response['message'] = JText::_('COM_PAYCART_ADMIN_PRODUCT_IMAGE_DELETE_SUCCESS');
		}
		else{
			$response = array('success' => false);				
			$response['message'] = JText::_('COM_PAYCART_ADMIN_PRODUCT_IMAGE_DELETE_ERROR');
		}
		$this->assign('json', $response);
		
		return true;	
	}
}