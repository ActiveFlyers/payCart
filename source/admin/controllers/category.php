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
		// PCTODO:: should be move into front end controller with new ajaxTask  		
		if(RB_REQUEST_DOCUMENT_FORMAT == 'ajax') {
			$post['title'] = $this->input->get('category_name');
			// PCTODO:: move into view
			$ajax = Rb_Factory::getAjaxResponse();
			$category = $this->_save($post); 
			
			if($category) {
				// PCTODO:: save success, send new cat_id as response
				$ajax->addRawData('response',$category->toArray());
			} else {
				//PCTODO::error msg
			}
			//set ajax response and return it
			$ajax->sendResponse();
		}

		parent::save();
	}
		
}