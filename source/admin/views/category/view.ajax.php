<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
* @author		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );
/** 
 * Category Html View
* @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewCategory extends PaycartAdminBaseViewCategory
{	
	protected $_response = null;
	
	function __construct($config = array() ) 
	{
		$this->_response = PaycartFactory::getAjaxResponse();
		return parent::__construct($config);
	}
	
	public function create()
	{
		$categoryId	=	$this->getModel()->getId();
		$category	=	PaycartCategory::getInstance($categoryId);
		
		$this->_response->addRawData('response',$category->toArray());
		//set ajax response and return it
		$this->_response->sendResponse();
	}
}