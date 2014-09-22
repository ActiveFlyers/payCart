<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Base Controller
* @author Team Readybytes
 */
class PaycartController extends Rb_Controller
{
	public $_component = PAYCART_COMPONENT_NAME;	
	
	/**
	 * Saves an item (new or old)
	 * @TODO:: should be protected.
	 */
	public function _save(array $data, $itemId=null)
	{		
		if($this->__validate($data, $itemId) === false){
			return false;
		}
		
		// if validation is successfull then save the data
		return parent::_save($data, $itemId);
	}
}
