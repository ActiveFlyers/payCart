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

class PaycartAttributeRadio extends PaycartAttribute
{
	public $type = 'radio';
	
	/**
	 *  return edit html that will be displayed on product edit screen
	 */
	function getEditHtml($attribute,  $value = null)
	{
		$html    = '';
		$options = parent::getOptions($attribute);
		
		if( !empty($options)){
			foreach($options as $option){
				$checked = ($option['productattribute_option_id'] == $value) ? "checked='checked'":'';
				$html    .= "<input type='radio' name='paycart_form[attributes][".$attribute->getId()."]' value='".$option['productattribute_option_id'] ."'".$checked.">".$option['title'];
			}
		}
				
		return $html;
	}
}