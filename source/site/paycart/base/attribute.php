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
 * Attribute base
 */

class PaycartAttribute
{
	static $instance = array();

	abstract function getEditHtml($attribute, $value=null);

	static public function getInstance($type)
	{
		//if already there is an object and check for static cache
		if(isset(self::$instance[$type])){
			return self::$instance[$type];
		}
		
		$className = JString::strtolower('PaycartAttribute'.$type);
		return self::$instance[$type] = new $className;
	}
	
	function getOptions($attribute)
	{
		return PaycartFactory::getInstance('productattributeoption', 'model')->loadOptions($attribute->getId(), $attribute->getLanguageCode());
	}
	
	/**
	 * build attribute specific options 
	 */
	function buildOptions($attribute, $data)
	{	
		$options = (isset($data['options'])) ? $data['options']: array();
		
		if(empty($options) && $attribute->getId()){
			$options = PaycartFactory::getInstance('productattribute_option')
					                           ->loadOptions($attribute->getId(), $attribute->getLangugeCode());
		}
		
		$result = array();
		foreach ($options as $id => $option){
			$result[$id]->option_ordering = $option['option_ordering'];
			$result[$id]->title			  = $option['title'];
			$result[$id]->productattribute_option_id = $option['productattribute_option_id'];
		}
		return $result;
	}
	
	/**
	 * Save attribute specific options
	 * @param	$attribute : instance of attribute lib
	 */
	function saveOptions($attribute)
	{ 
		$options = $attribute->getOptions();
		
		if(empty($options)){
			return false;
		}
		
		$attrOptionModel 	 = PaycartFactory::getInstance('productattributeoption', 'model');
		$attrOptionLangModel = PaycartFactory::getInstance('productattributeoptionlang', 'model');
		
		foreach ($options as $option){
			$data = array();
			
			//save option data
			$data['option_ordering'] = $option->option_ordering;
			$data['productattribute_id'] = $option->getId();
			$optionId = $attrOptionModel->save($data, $option->productattribute_option_id);
			if(!$optionId){
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $attrOptionModel->getError());
			}
			
			//save langauge specific data of options
			$data = array();
			$data['productattribute_option_id'] = $optionId;
			$data['lang_code'] = $attribute->getLanguageCode();
			$data['title']	   = $param->title;
			$optionLangId = $attrOptionLangModel->save($data,$param->productattribute_option_lang_id );
			if(!$optionLangId){
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $attrOptionLangModel->getError());
			}
		}
		return true;
	}
	
	/**
	 * get config Html
	 */
	function getConfigHtml($attribute)
	{
		$html = '';
		
		$options = $this->getOptions($attribute);
		$count	 = (count($options) >= 1)?count($options):1;
		
		for($i=0; $i < $count ; $i++)
		{
			$html .= "<div class='control-label'><label id='title_".$i."_lbl' title=''>".Rb_Text::_("COM_PAYCART_ATTRIBUTES_OPTION_LABEL")."</label></div>";
			
			$html .= "<div class='controls'><input type='text' name='options[$i][title]' id='title_".$i."' 
			         value='".isset($options[$i]['title'])?$options[$i]['title']:''."' /></div>";
			
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
	function deleteOptions($attribute)
	{
		$attrOptionModel = PaycartFactory::getInstance('productattributeoption', 'model');
		return $attrOptionLangModel->deleteOptions($attribute->getid());
	}
	
	/**
	 * save attribute specific data after/before saving product
	 */
	function save($data)
	{
		return $data;
	}
}