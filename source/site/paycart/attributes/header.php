<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * attribute type checkbox
 */

class PaycartAttributeHeader extends PaycartAttribute
{
	public $type = 'header';
	
	/**
	 * get config Html
	 */
	function getConfigHtml($attribute,$selectedValue ='', Array $options = array())
	{
		return '';
	}
	
	/**
	 * Save attribute specific options
	 * @param	$attribute : instance of attribute lib
	 */
	function saveOptions($attribute)
	{ 
		$options = $attribute->getOptions();
		
		//save option data
		$data = array();
		$data['option_ordering'] = 0;
		$data['productattribute_id'] = $attribute->getId();
		$data['lang_code'] = $attribute->getLanguageCode();
		$data['productattribute_option_lang_id'] = $option->productattribute_option_lang_id;
		$data['title']	   = $attribute->getTitle();
		
		$id = 0;
		if(!empty($options)){
			$option = array_shift($options);
			$data['productattribute_option_lang_id'] = $option->productattribute_option_lang_id;
			$id = $option->productattribute_option_id;
		}
		
		$attrOptionModel 	 = PaycartFactory::getModel('productattributeoption');
		$optionId = $attrOptionModel->save($data, $id);
		if(!$optionId){
			throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $attrOptionModel->getError());
		}
		
		return true;		
	}
	
	/**
	 *  return edit html that will be displayed on product edit screen
	 */
	function getEditHtml($attribute, $selectedValue ='', Array $options = array())
	{
		$html    = '';
		$options = parent::getOptions($attribute);
		$option = array_shift($options);
			
		return "<hr /> <input type='hidden' name='paycart_product_form[attributes][".$attribute->getId()."]' value='".$option['productattribute_option_id'] ."'>";			
	}
}