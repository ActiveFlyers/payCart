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
 * Attribute base
 */

class PaycartAttribute
{
	static $instance=array(); 

	static public function getInstance($type)
	{
		//generate class name
		$className	= 'PaycartAttribute'.$type;

		//if already there is an object and check for static cache
		if(isset($instance[$type])){
			return $instance[$type];
		}
	}
	
	/**
	 * get attribute's options
	 */
	function getOptions($attribute)
	{
		return PaycartFactory::getInstance('productattributeoption', 'model')->loadOptions($attribute->getId(), $attribute->getLanguageCode());
	}
	
	/**
	 * bind attribute specific data 
	 */
	function bindConfig($attribute, $data)
	{	
		$options = (isset($data['options'])) ? $data['options']: array();
		
		if(empty($options) && $attribute->getId()){
			$options = PaycartFactory::getInstance('productattribute_option')
					                           ->loadOptions($attribute->getId(), $attribute->getLangugeCode());
		}
		
		$params = array();
		foreach ($options as $id => $option){
			$params[$id]->option_ordering = $option['option_ordering'];
			$params[$id]->title			  = $option['title'];
			$params[$id]->productattribute_option_id = $option['productattribute_option_id'];
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
		
		$attrOptionModel 	 = PaycartFactory::getInstance('productattributeoption', 'model');
		$attrOptionLangModel = PaycartFactory::getInstance('productattributeoptionlang', 'model');
		
		foreach ($params as $param){
			$data = array();
			
			//save option data
			$data['option_ordering'] = $param->option_ordering;
			$data['productattribute_id'] = $attribute->getId();
			$optionid = $attrOptionModel->save($data, $param->productattribute_option_id);
			
			//save langauge specific data of options
			$data = array();
			$data['productattribute_option_id'] = $optionid;
			$data['lang_code'] = $attribute->getLanguageCode();
			$data['title']	   = $param->title;
			$attrOptionLangModel->save($data,$param->productattribute_option_lang_id );
		}
		return true;
	}
	
	/**
	 * get config Html
	 */
	function renderConfigHtml($attribute)
	{
		$html = '';
		
		$options = $this->getOptions($attribute);
		$count	 = (count($options) >= 1)?count($options):1;
		
		for($i=0; $i < $count ; $i++)
		{
			$html .= "<div class='control-label'><label id='title_".$i."_lbl' title=''>".Rb_Text::_("COM_PAYCART_ATTRIBUTES_OPTION_LABEL")."</label></div>";
			
			$html .= "<div class='controls'><input type='text' name='options[$i][title]' id='title_".$i."' 
			         value='".isset($options[$i]['title'])?$options[$i]['title']:''."' /></div>";
			//$html .= "<div class='control-label'><label id='option_ordering_".$i."_lbl' title=''>".Rb_Text::_("COM_PAYCART_ATTRIBUTES_OPTION_ORDERING_LABEL")."</label></div>";
			$html .= "<input type='hidden' name='options[$i][option_ordering]' id='option_ordering_".$i."'  
					  value='".isset($options[$i]['option_ordering'])?$options[$i]['option_ordering']:$i."' />";
			
			$html .= "<input type='hidden' name='options[$i][productattribute_option_id]' id='productattribute_option_id_".$id."'
			          value='".isset($options[$i]['productattribute_option_id'])?$options[$i]['productattribute_option_id']:'0'."' />";
			
			$html .= "<input type='hidden' name='options[$i][productattribute_option_lang_id]' id='productattribute_option_lang_id_".$id."'
			          value='".isset($options[$i]['productattribute_option_lang_id'])?$options[$i]['productattribute_option_lang_id']:'0'."' />";
			
			//PCTODO: append button to add new and delete existing html 
		}  
		
		return $html;
	}
	
	/**
	 * delete options data from both option and option_lang table
	 */
	function deleteConfigData($attribute)
	{
		$attrOptionModel = PaycartFactory::getInstance('productattributeoption', 'model');
		return $attrOptionLangModel->deleteOptionData($attribute->getid());
	}
	
	/**
	 * set value on saving product
	 */
	function setValue($data)
	{
		return $data;
	}
}