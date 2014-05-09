<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Country Ajax View
 * @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewState extends PaycartAdminBaseViewState
{
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null)
	{
		$state_id		=  $this->getModel()->getId('id');
		$country_id		=  $this->getModel()->getState('country_id');
		
		$filter = Array('country_id' => $country_id, 'id' => $state_id  );

		$state_data = PaycartFactory::getModel('state')->loadRecords($filter);
		
		if(isset($state_data[$state_id])) {
			$state_data = (array) $state_data[$state_id];
		}
		
		// required when creating new record
		$state_data['country_id'] = $country_id;
		
		$this->assign('form',  PaycartFactory::getModelForm('state')->getForm($state_data, false));
		
		// Tile of Modal
		$title = 'COM_PAYCART_STATE_ADD_NEW';
		if($state_id) {
			$title = 'COM_PAYCART_STATE_EDIT';
		}

		// Set window title 
		$this->assign('model_title', $title);
		
		return  parent::edit($tpl);
	}
}