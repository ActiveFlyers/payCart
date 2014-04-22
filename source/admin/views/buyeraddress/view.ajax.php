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
 * Buyeraddress Ajax View
 * @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewBuyeraddress extends PaycartAdminBaseViewBuyeraddress
{	
	public function edit($tpl=null)
	{
		$buyer_address_id	=  $this->getModel()->getState('id');
		
		// Tile of Modal
		$title = 'COM_PAYCART_BUYERADDRESS_ADD_NEW';
		if($buyer_address_id) {
			$title = 'COM_PAYCART_BUYERADDRESS_EDIT';
		}

		// Set window title 
		$this->_setAjaxWinTitle(JText::_($title));
		
		// add footer buttons
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_BUTTON_SAVE'), null, "btn", 'id="paycart_buyeraddress_add"');
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_BUTTON_CANCLE'), "rb.ui.dialog.autoclose(1)");
		$this->_setAjaxWinAction();

		return  parent::edit($tpl);
	}
	
}