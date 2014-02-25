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
 * attribute type dropdown
 */

class PaycartAttributeSelect extends PaycartAttribute
{
	public $type = 'select';
	
	/**
	 *  return display html that will be displayed on product edit screen
	 */
	function renderDisplayHtml($attribute,$value = null)
	{
		$html 	 = '';
		$options = parent::getOptions($attribute);
		
		if( !empty($options)){
			$html .= "<div class='control-label'><label id='attribute".$attribute->getId()."_lbl' title=''>".$attribute->getTitle()."</label></div>";
			$html .= "<div class='controls'><select id='attribute".$attribute->getId()."' name='attributes[".$attribute->getId()."]'>";
			
			foreach($options as $option){
				
				$selected = ($option['productattribute_option_id'] == $value) ? "selected='selected'":'';
				
				$html .= "<option value='".$option['productattribute_option_id'] ."'". $selected .">".$option['title']."</option>";
			}
			$html.= '</select></div>';
		}
				
		return $html;
	}
}