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
 * attribute type select
 */

class PaycartAttributeSelect extends PaycartAttribute
{
	public $type = 'select';
	
	/**
	 *  return edit html that will be displayed on product edit screen
	 */
	function getEditHtml($attribute,$value = null)
	{
		$html 	 = '';
		$options = parent::getOptions($attribute);
		
		if( !empty($options)){
			$html .= "<select id='attribute".$attribute->getId()."' name='paycart_form[attributes][".$attribute->getId()."]'>";
			
			foreach($options as $option){
				
				$selected = ($option['productattribute_option_id'] == $value) ? "selected='selected'":'';
				
				$html 	 .= "<option value='".$option['productattribute_option_id'] ."'". $selected .">".$option['title']."</option>";
			}
			$html	.= '</select>';
		}
				
		return $html;
	}
}