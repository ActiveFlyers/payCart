<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/** 
 * cart Base View 
 */
class PaycartSiteBaseViewAccount extends PaycartView
{	
	protected function _basicFormSetup($task)
	{
		if($this->getModel()){
			return parent::_basicFormSetup($task);
		}
		
		return true;
	}
}