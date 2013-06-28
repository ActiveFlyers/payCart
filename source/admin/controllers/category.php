<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Category Controller
 * @author Manish Trivedi
 */

class PaycartAdminControllerCategory extends PaycartController {
	
	function save()
	{
		// Handle 		
		if(RB_REQUEST_DOCUMENT_FORMAT == 'ajax') {
			$post['name'] = $this->input->get('category_name');
			// PCTODO:: move into view
			$ajax = Rb_Factory::getAjaxResponse();
			$entity = $this->_save($post); 
			if($entity) {
				// PCTODO:: save success, send new cat_id as response
				$ajax->addRawData('response', 'true');
				
			}else {
				//PCTODO::error msg
			}
			//set ajax response and return it
			$ajax->sendResponse();
		}

		parent::save();
	}
		
}