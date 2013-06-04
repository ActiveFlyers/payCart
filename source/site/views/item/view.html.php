<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Item Html View
* @author Team Readybytes
 */
require_once dirname(__FILE__).'/view.php';
class PaycartSiteViewItem extends PaycartSiteBaseViewItem
{	
	public function display()
	{		
		return true;
	}

	public function _basicFormSetup()
	{
		return true;
	}
}
