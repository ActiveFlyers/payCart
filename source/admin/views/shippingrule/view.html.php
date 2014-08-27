<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Admin Html View for Shipping Rules
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewShippingRule extends PaycartAdminBaseViewShippingRule
{	
	/**
	 * @var PaycartHelperShippingRule
	 */
	protected $_helper = null;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->_helper = PaycartFactory::getHelper('shippingrule');
	}
	
	public function edit($tpl=null) 
	{	
		$itemId	=  $this->getModel()->getId();
		$item	=  PaycartShippingRule::getInstance($itemId);
		$form 	= $item->getModelform()->getForm($item);
		
		$this->assign('form', $form );
		
		return parent::edit($tpl);
	}
}