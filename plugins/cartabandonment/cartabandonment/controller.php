<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		Paycart
* @subpackage	cartabandonment
* @contact 		support@readybytes.in
* website		http://www.readybytes.net
*/
if(defined('_JEXEC')===false) die();

class PaycartadminControllercartabandonment extends PaycartController
{
	
		function display($cachable = false, $urlparams = array())
		{
			return true;
		}
		
	public function _save(array $data, $itemId=null)
	{		
		if(isset($data['when_to_email']) && !empty($data['when_to_email'])){
			$data['when_to_email'] = str_replace('NaN', '00', $data['when_to_email']);
		}
		
		// if validation is successfull then save the data
		return parent::_save($data, $itemId);
	}
}
