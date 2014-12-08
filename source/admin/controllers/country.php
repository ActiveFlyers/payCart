<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		mManish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerCountry extends PaycartController 
{
	/**
	 * 
	 * @var overwrite
	 */
	protected $_id_data_type	=	'STRING';
	
	/**
	 * Saves an item (new or old)
	 */
	public function _save(array $data, $itemId=null)
	{		
		// validation will be done on Model
		return  $this->getModel()->save($data, $itemId);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Controller::_remove()
	 */
	public function _remove($itemId=null, $userId=null)
	{
		return $this->getModel()->delete($itemId);
	}
}