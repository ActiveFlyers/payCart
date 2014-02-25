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
 * attribute type color picker
 * @author rimjhim
 */

class PaycartAttributeColor extends PaycartAttribute
{
	public $type = 'color';
	
	/**
	 *  return display html that will be displayed on product edit screen
	 */
	function renderDisplayHtml($attribute, $value = null)
	{
		$html 	 = '';
		$colors  = PaycartFactory::getModel('color')->getColors($attribute->getLanguageCode());
		
		if( !empty($colors)){
			$html .= "<div class='control-label'><label id='attribute".$attribute->getId()."_lbl' title=''>".$attribute->getTitle()."</label></div>";
			$html .= "<div class='controls'><select id='attribute".$attribute->getId()."' name='attributes[".$attribute->getId()."]'>";
			
			foreach($colors as $color){
				$selected = ($color['color_id'] == $value) ? "selected='selected'":'';
				$html .= "<option value='".$color['color_id'] ."'".$selected.">".$color['title']."</option>";
			}
			$html.= '</select></div>';
		}
				
		return $html;
	}

	/**
	 * get config Html
	 */
	function renderConfigHtml($attribute)
	{
		$html   = '';
		
		$colors = getColors($attribute->getLanguageCode());
		$count  = (count($colors) > 0)?count($colors):1;

		for($i=0; $i < $count ; $i++){
			$html .= "<div class='control-label'><label id='hashcode_".$i."_lbl' title=''>".Rb_Text::_("COM_PAYCART_ATTRIBUTE_COLOR_HASHCODE_LABEL")."</label></div>";
			$html .= "<div class='controls'><input type='color' id='hashcode_".$i."' name='colors[$i][hashcode]'  
					  value='".isset($colors[$i]['hash_code'])?$colors[$i]['hash_code']:''."'/></div>";
			
			$html .= "<div class='control-label'><label id='title_".$i."_lbl' title=''>".Rb_Text::_("COM_PAYCART_ATTRIBUTE_COLOR_TITLE_LABEL")."</label></div>";
			$html .= "<div class='controls'><input type='text' id='title_".$i."' name='colors[$i][title]' 
				     value='".isset($colors[$i]['title'])?$colors[$i]['title']:''."'/></div>";
			
			$html .= "<input type='hidden' name='colors[$i][color_id]' value='".isset($colors[$i]['color_id'])?$colors[$i]['color_id']:'0'."' />";
			
			$html .= "<input type='hidden' name='colors[$i][color_lang_id]' id='color_lang_id".$id."'
			          value='".isset($colors[$i]['color_lang_id'])?$colors[$i]['color_lang_id']:'0'."' />";
			//PCTODO: append button to add new and delete existing html 
		}
		
		return $html;
	}
	
	/**
	 * bind attribute specific data 
	 */
	function buildConfig($attribute, $data)
	{	
		$colors = (isset($data['colors'])) ? $data['colors']: array();
		
		if(empty($colors) && $attribute->getId()){
			$colors = PaycartFactory::getInstance('color')
					                           ->getColors($attribute->getLangugeCode());
		}
		
		$params = array();
		foreach ($colors as $id => $color){
			$params[$id]->title 	= $color['title'];
			$params[$id]->hash_code	= $color['hash_code'];
			$params[$id]->color_id = $option['color_id'];
		}
		return $params;
	}
	
	/**
	 * Save attribute specific data
	 * @param	$attribute : instance of attribute lib
	 * @param	$params : array of stdclass having attribute specif data 
	 */
	function saveConfig($attribute)
	{ 
		$params = $attribute->getParams();
		
		if(empty($params)){
			return false;
		}
		
		$colorModel 	 = PaycartFactory::getInstance('color', 'model');
		$colorLangModel  = PaycartFactory::getInstance('colorlang', 'model');
		
		foreach ($params as $param){
			$data = array();
			
			//save option data
			$data['hash_code'] = $param->hash_code;
			$colorid = $colorModel->save($data, $param->color_id);
			
			//save langauge specific data of options
			$data = array();
			$data['color_id']  = $colorid;
			$data['lang_code'] = $attribute->getLanguageCode();
			$data['title']	   = $param->title;
			$colorLangModel->save($data,$param->color_lang_id );
		}
		return true;
	}
	
	/**
	 * delete attribute specific data 
	 */
	function deleteConfig($attribute)
	{
		//PCTODO: wether to delete data from color table or not
		return true;
	}
}