<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Checkout Base View
 * @author Manish
 */
class PaycartSiteBaseViewCheckout extends PaycartView
{
	protected $step_ready = 'login';
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function init()
	{
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function process()
	{		
		$this->setTpl($this->step_ready);
		
		return true;
	}
	
	protected function _basicFormSetup($task)
	{
		//setup basic stuff like steps		
		$this->assign('step_ready', $this->step_ready);
		 
		return true;
	}

}