<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Attribute Ajax View
 * @author mManishTrivedi
 */

require_once dirname(__FILE__).'/view.php'; 
class PaycartAdminViewAttribute extends PaycartAdminBaseViewAttribute
{	
	protected $_response = null;
	
	function __construct($config = array() ) 
	{
		//@PCTODO :: Dont use response on $this
		$this->_response = PaycartFactory::getAjaxResponse();
		return parent::__construct($config);
	}

	/**
	 * 
	 * return Attribute type configuration
	 */
	public function getTypeConfig()
	{
		$attributeId	=  $this->input->get('attribute_id',0);
		$data['type']	=  $this->input->get('type',0);
		
		$attribute		=  PaycartAttribute::getInstance($attributeId, $data);
		
		$this->assign('form',  $attribute->getModelform()->getForm());
		$this->setTpl('typeconfig');
		
		// change specific div html
		$this->_renderOptions = array('domObject'=>'paycart-attribute-type-elements','domProperty'=>'innerHTML');
		
		return true;
	}
	
	public function create()
	{
		$attributeId	=	$this->getModel()->getId();
		$attribute		=	PaycartAttribute::getInstance($attributeId);
		
		$this->_response->addRawData('response',$attribute->toArray());
		//set ajax response and return it
		$this->_response->sendResponse();
	}
}