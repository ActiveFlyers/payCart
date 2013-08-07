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
	protected $_response = null;
	
	function __construct($config = array() ) 
	{
		$this->_response = PaycartFactory::getAjaxResponse();
		return parent::__construct($config);
	}
	
	public function getAlias($ajax)
	{
		$title  = $this->input->get('title');
		$id 	= $this->input->get('product_id',0);
		
		if(!$title) {
			throw new Exception(Rb_Text::sprintf('COM_PAYCART_INVALID_POST_DATA', '$title missing'));
		}
		$alias = PaycartFactory::getInstance('product','model')->getTable()->getUniqueAlias($title, $id);
		//set ajax response and return it
		$this->_response->addRawData('row',$alias);
		$this->_response->sendResponse();
		
	}
}