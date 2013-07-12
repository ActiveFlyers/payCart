<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );
/** 
 * Product Html View
* @author Team Readybytes
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewProduct extends PaycartAdminBaseViewProduct
{	
	
	/**
	 * Task for all ajax call with action. 
	 */
	
	public function go() 
	{
		$method = $this->input->get('method');
		$ajax = PaycartFactory::getAjaxResponse();
		
		if (!method_exists($this, $method)) {
			throw new Exception(Rb_Text::sprintf('COM_PAYCART_INVALID_POST_DATA', '$method must be required'));
		}
		
		$this->$method($ajax);
		//set ajax response and return it
		$ajax->sendResponse();
		return true;
	}
	
	public function getAlias($ajax)
	{
		$title = $this->input->get('title');
		if(!$title) {
			throw new Exception(Rb_Text::sprintf('COM_PAYCART_INVALID_POST_DATA', '$title missing'));
		}
		
		$alias = PaycartFactory::getInstance('product','model')->getTable()->getUniqueAlias($title);
		
		$ajax->addRawData('row',$alias);
	}
}