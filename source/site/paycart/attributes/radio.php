<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
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
	 *  return display html that will be displayed on product edit screen
	 */
	function renderDisplayHtml($attribute,  $value = null)
	{
		$html    = '';
		$options = parent::getOptions($attribute);
		
		if( !empty($options)){
			$html .= "<div class='control-label'><label id='attribute".$attribute->getId()."_lbl' title=''>".$attribute->getTitle()."</label></div>";
			$html .= "<div class='controls'>";
			foreach($options as $option){
				$checked = ($option['productattribute_option_id'] == $value) ? "checked='checked'":'';
				$html    .= "<input type='radio' name='attributes[".$attribute->getId()."]' value='".$option['productattribute_option_id'] ."'".$checked.">".$option['title'];
			}
			$html .= "</div>";
		}
				
		return $html;
	}
}