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
 * Admin Base View for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author rimjhim Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewConfig extends PaycartAdminBaseViewConfig
{
	function display($tpl=null)
	{
		//now record is always an array, pick all records
		$modelform  = PaycartFactory::getInstance('config', 'Modelform');
		$form		= $modelform->getForm();
		
		$data 		= PaycartFactory::getConfig();
		$origin_address = json_decode($data->get('localization_origin_address'));
		$form->bind($data);
		
		$logo = $data->get('company_logo');
		if(!empty($logo)){
			$logo = PaycartMedia::getInstance($logo)->toArray();
		}
		
		$this->assign('origin_address',$origin_address);
		$this->assign('logo', $logo);
		$this->assign('form',$form);
		return $this->setTpl('edit');
	}

	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::apply();
	}
}