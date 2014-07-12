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
 * attribute type color picker
 * @author rimjhim
 */

class PaycartAttributeColor extends PaycartAttribute
{
	public $type = 'color';
	
	/**
	 *  return edit html that will be displayed on product edit screen
	 */
	function getEditHtml($attribute, $value = null)
	{
		$html 	 = '';
		$colors  = PaycartFactory::getModel('color')->loadOptions($attribute->getLanguageCode());
		
		if( !empty($colors)){
			$html .= "<select id='attribute".$attribute->getId()."' name='paycart_form[attributes][".$attribute->getId()."]'>";
			
			foreach($colors as $color){
				$selected = ($color['color_id'] == $value) ? "selected='selected'":'';
				$html .= "<option value='".$color['color_id'] ."'".$selected.">".$color['title']."</option>";
			}
			$html.= '</select>';
		}
				
		return $html;
	}
	/**
	 * get config Html
	 */
	function getConfigHtml($attribute)
	{
		$html   = '';
		$type   = $attribute->getType();
				
		$html = '<div id="paycart-attribute-config">';
		
		$html .= '<button id="paycart-attribute-option-add" type="button" class="btn" onClick="paycart.admin.attribute.addOption(\''.$type.'\')">'.JText::_("Add Option").'</button>'; 
		
		$colors = PaycartFactory::getModel('color')->loadOptions($attribute->getLanguageCode());
		$count  = (count($colors) > 0)?count($colors):1;
		
		for($i=0; $i < $count ; $i++){
			$html .= $this->buildCounterHtml($i, $type, $colors);
		}
		
		$html .= "</div>";
		
		return $html;
	}
	
	/**
	 * 
	 * Bulid html for a specific counter
	 * @param $counter : array index to be used for creating html
	 * @param $type : type of attribute
	 * @param $options : Array containing values of attribute options
	 */
	function buildCounterHtml($counter, $type, $options=array())
	{		
		ob_start();
			?>	
			<div id="option_row_<?php echo $counter?>">
				 <div class="control-group">
					 <div class='control-label'><label id='hashcode_<?php echo $counter?>_lbl' title=''><?php echo Rb_Text::_("COM_PAYCART_ATTRIBUTE_COLOR_HASHCODE_LABEL") ?></label></div>
					
					 <div class='controls'>
					 		<input type='text' name='options[<?php echo $counter?>][hash_code]' id='hash_code_<?php echo $counter?>'  class='wheel-color' placeholder='#rrggbb' data-control="wheel"
					      	value='<?php echo (isset($options[$counter]['hash_code'])?$options[$counter]['hash_code']:'')?>'/>
					      	<button class="btn" id="paycart-attribute-option-remove" type="button" onClick="paycart.admin.attribute.removeOption('<?php echo $type?>','<?php echo $counter;?>');">
								<?php echo JText::_('Delete');?>
				 			</button>
					 </div>
				 </div>
				 
				 <div class="control-group">
					 <div class='control-label'><label id='title_<?php echo $counter?>_lbl' title=''><?php echo Rb_Text::_("COM_PAYCART_ATTRIBUTE_COLOR_TITLE_LABEL") ?></label></div>
					
					 <div class='controls'>
					 		<input type='text' name='options[<?php echo $counter?>][title]' id='title_<?php echo $counter?>' value='<?php echo (isset($options[$counter]['title'])?$options[$counter]['title']:'')?>'/>
					 </div>
				 </div>
				 
				 <input type='hidden' name='options[<?php echo $counter?>][color_id]' id='color_id_<?php echo $counter?>'  
						  value='<?php echo (isset($options[$counter]['color_id'])?$options[$counter]['color_id']:0) ?>' />
						  
				 <input type='hidden' name='options[<?php echo $counter?>][color_lang_id]' id='color_lang_id_<?php echo $counter?>'  
						  value='<?php echo (isset($options[$counter]['color_lang_id'])?$options[$counter]['color_lang_id']:0) ?>' />
				 
			</div>
			<?php 
			
			$html = ob_get_contents();
			ob_clean();
			
			return $html;
	}
	
	/**
	 * script to be added while ajax request
	 */
	function getScript()
	{
		return 'paycart.admin.attribute.addColorScript';
	}
	
	/**
	 * build attribute specific data
	 * it will be required while saving
	 */
	function buildOptions($attribute, $data)
	{	
		$colors = (isset($data['_options'])) ? $data['_options']: array();
		
		if(empty($colors) && $attribute->getId()){
			$colors = PaycartFactory::getModel('color')->loadOptions($attribute->getLanguageCode());
		}
		
		$result = array();
		foreach ($colors as $id => $color){
			$result[$id] = new stdClass();
			$result[$id]->title 	= $color['title'];
			$result[$id]->hash_code	= $color['hash_code'];
			$result[$id]->color_id  = $color['color_id'];
			$result[$id]->color_lang_id = $color['color_lang_id'];
		}
		return $result;
	}
	
	/**
	 * Save attribute specific data
	 * @param	$attribute : instance of attribute lib 
	 */
	function saveOptions($attribute)
	{ 
		$options = $attribute->getOptions();
		
		if(empty($options)){
			return false;
		}
		
		foreach ($options as $option){
			$data = array();
			
			//save option data
			$data['hash_code'] = $option->hash_code;
			$colorModel 	   = PaycartFactory::getModel('color');
			$colorId 		   = $colorModel->save($data, $option->color_id);
			if(!$colorId){
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $colorModel->getError());
			}
			//save langauge specific data of options
			$data = array();
			$data['color_id']  = $colorId;
			$data['lang_code'] = $attribute->getLanguageCode();
			$data['title']	   = $option->title;
			$colorLangModel    = PaycartFactory::getModelLang('color');
			$colorLangId 	   = $colorLangModel->save($data,$option->color_lang_id );
			if(!$colorLangId){
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $colorLangModel->getError());
			}
		}
		return true;
	}
	
	/**
	 * delete attribute specific data 
	 */
	function deleteOptions($attributeId=null, $optionId=null)
	{
		return false;
	}
	
	/**
	 * Returns html that will be used for selectors
	 * 
	 * @param $attribute : Instance of PaycartProductAttribute 
	 * @param $optionMapping : Array contains following value
	 * 						    'optionid' => array (
	 * 										    'url' , 'value'
	 *                                        ) 
	 * @param $options : comma separaterd string containing optionids that would be considered in filters
	 * @param $selectedOption : Option that should be selected by default
	 */
	function getSelectorHtml($attribute, $optionMapping, $options = '', $selectedOption = '')
	{
		$suffix   = '';		
		$colors   = PaycartFactory::getModel('color')->loadOptions($attribute->getLanguageCode(),$options);
		
		if(empty($colors)){
			return '';
		}
		
		$html 	= '<div><select name="color'.$attribute->getId().'" onchange="location = this.options[this.selectedIndex].value;">';
		
		//build option html
		foreach ($colors as $color){
			$option = $optionMapping[$color['color_id']];
			$url	= $option['url'];
			$selected = '';
			
			if(!empty($selectedOption) && $selectedOption == $option['value']){
				$selected = 'selected="selected"';
				$suffix   = '<span class="pc-attribute-color" style="background-color:'.$color['hash_code'].'" title="'.$color['title'].'"></span>';
			}
			
			$html  .= '<option value="'.$url.' " ' .$selected.' >'.$color['title'].'</option>' ;
		}
		
		$html .= '</select>'.$suffix.'</div>';
		return $html;
	}
}