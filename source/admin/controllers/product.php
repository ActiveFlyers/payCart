<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Product Controller
 * @author Manish Trivedi
 */

class PaycartAdminControllerProduct extends PaycartController 
{
	
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['upload_files'] = PaycartFactory::getApplication()->input->files->get('paycart_form', false);
		return parent::_save($data, $itemId, $type);
	}
		
}