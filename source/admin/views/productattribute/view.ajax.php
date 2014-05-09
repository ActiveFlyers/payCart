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
class PaycartAdminViewProductAttribute extends PaycartAdminBaseViewProductAttribute
{	
	public function edit($tpl = null)
	{
		$this->_setAjaxWinTitle(JText::_('COM_PAYCART_PRODUCT_CREATE_NEW_ATTRIBUTE'));
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_PRODUCT_CREATE_NEW_ATTRIBUTE'),'paycart.admin.product.attribute.create()','btn btn-primary');
		$this->_addAjaxWinAction(XiText::_('COM_PAYCART_AJAX_CANCEL_BUTTON'), 'rb.ui.dialog.close();');
		$this->_setAjaxWinAction();
				
		parent::_assignTemplateVars();
		$content = $this->loadTemplate('edit');
			
		$this->_setAjaxWinBody($content);
		return false;	
	}
	
	public function getEditHtml()
	{
		$productAttributeIds = $this->input->get('productattributeIds',null,'ARRAY');
		
		$this->assign('productAttributeIds', $productAttributeIds);
		$html = $this->loadTemplate('addattribute');
		
		$ajaxResponse = PaycartFactory::getAjaxResponse();
		$ajaxResponse->addScriptCall('paycart.jQuery(".paycart-product-applied-attributes").append',$html);
		return false;
	}
}