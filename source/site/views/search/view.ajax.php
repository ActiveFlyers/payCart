<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Search ajax View
* @author rimjhim
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewSearch extends PaycartSiteBaseViewSearch
{	
	protected function _basicFormSetup($task)
	{
		return true;
	}
	
	public function filter()
	{
		//add data to the given div
		$this->_renderOptions = array('domObject'=>'pc-product-search-content','domProperty'=>'innerHTML');
		return true;
	}
}