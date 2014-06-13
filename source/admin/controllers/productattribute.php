<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Product Controller
 * @author Manish Trivedi
 */

class PaycartAdminControllerProductAttribute extends PaycartController 
{
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['_options'] = $this->input->get('options', false, 'ARRAY');
		return parent::_save($data, $itemId, $type);
	}
			
	/**
	 * 
	 * Ajax call : Get elements of Attribute Configuration
	 */
	public function getTypeConfig()
	{
		$attributeId	=  $this->input->get('productattribute_id',0);
		$data 			= array();
		$data['type']	=  $this->input->get('attributeType',0);
		
		$attribute		=  PaycartProductAttribute::getInstance($attributeId)->bind($data);
		
		$instance = PaycartAttribute::getInstance($data['type']);
		$html = $instance->getConfigHtml($attribute);
		$js	  = $instance->getScript(); 

		// replace specific div html and call script
		$ajaxResponse = PaycartFactory::getAjaxResponse();
		
		$ajaxResponse->addScriptCall('paycart.jQuery("#paycart-attribute-config").replaceWith', $html);

		if(!empty($js)){		
			$ajaxResponse->addScriptCall($js);
		}
		return false;
	}
	
	/**
	 * Ajax Call: create new attribute from product 
	 */
	public function create() 
	{
		$attribute 		= parent::save();

		//PCTODO: If product is not saved then show proper error message
		$productId 		= $this->input->get('productId',0); 
		
		$this->setRedirect( 'index.php?option=com_paycart&view=product&task=edit&product_id='.$productId);

		return false;
	}
	
	/**
	 * Ajaxified task to remove any existing option
	 */
	public function removeOption()
	{
		$type      = $this->input->get('attributeType');
		$counter   = $this->input->get('counter');
		$optionId  = $this->input->get('optionId',0);
		
		if(PaycartAttribute::getInstance($type)->deleteOptions($attribute, $optionId)){
			$ajaxResponse = PaycartFactory::getAjaxResponse();
			$ajaxResponse->addScriptCall('paycart.jQuery("#option_row_'.$counter.'").remove');
		}	
		return false;
	}
	
	/**
	 * Ajaxified task to add a new option to an attribute
	 */
	public function addOption()
	{
		$type      = $this->input->get('attributeType');
		$totalRows = $this->input->get('totalRows',0);
		
		$instance = PaycartAttribute::getInstance($type);
		$html = $instance->buildCounterHtml($totalRows, $type);
		$js	  = $instance->getScript(); 
		
		$ajaxResponse = PaycartFactory::getAjaxResponse();
		$ajaxResponse->addScriptCall('paycart.jQuery("#paycart-attribute-config").append',$html);
		
		if(!empty($js)){		
			$ajaxResponse->addScriptCall($js);
		}
		return false;
	}
	
	public function getEditHtml()
	{
		return true;
	}
}