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
	 * Saves an item (new or old)
	 */
	public function _save(array $data, $itemId=null)
	{
		// language data
		$data['lang_code'] = PaycartFactory::getLanguageTag();
		
		// validation will be done on Model
		return  $this->getModel()->save($data, $data['country_id']);
	}
	
	/**
	 * We are using char in primary key
	 * @see /plugins/system/rbsl/rb/rb/Rb_AbstractController::_getId()
	 */
	public function _getId() 
	{
		$id = parent::_getId();
		
		if ( -1 === $id ) 
		{
			$id = $this->input->get('country_id', null);
			if(!$id) {
				$id = $this->input->get('id', null);
			}
		}
		
		return $id;
		;
	}
}