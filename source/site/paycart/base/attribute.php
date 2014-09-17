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

	public function getEditHtml($attribute,$selectedValue ='', Array $options = array())
	{
		return true;
	}

	static public function getInstance($type)
	{
		$type = JString::strtolower($type);
		
		//if already there is an object and check for static cache
		if(isset(self::$instance[$type])){
			return self::$instance[$type];
		}
		
		$className = 'paycartattribute'.$type;
		self::$instance[$type] = new $className;
		
		return self::$instance[$type];
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
		$options = (isset($data['_options'])) ? $data['_options']: array();
		
		if(empty($options) && $attribute->getId()){
			$options = PaycartFactory::getModel('productattributeoption')
					                           ->loadOptions($attribute->getId(), $attribute->getLanguageCode());
		}
		
		$result = array();
		foreach ($options as $id => $option){
			$result[$id] = new stdClass();
			$result[$id]->option_ordering = $option['option_ordering'];
			$result[$id]->title			  = $option['title'];
			$result[$id]->productattribute_option_id = $option['productattribute_option_id'];
			$result[$id]->productattribute_option_lang_id = $option['productattribute_option_lang_id'];
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
		
		$attrOptionModel 	 = PaycartFactory::getModel('productattributeoption');
		
		foreach ($options as $option){
			$data = array();
			
			//save option data
			$data['option_ordering'] = $option->option_ordering;
			$data['productattribute_id'] = $attribute->getId();
			$data['lang_code'] = $attribute->getLanguageCode();
			$data['productattribute_option_lang_id'] = $option->productattribute_option_lang_id;
			$data['title']	   = $option->title;
			
			$optionId = $attrOptionModel->save($data, $option->productattribute_option_id);
			if(!$optionId){
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $attrOptionModel->getError());
			}
		}
		return true;
	}
	
	/**
	 * get config Html
	 */
	function getConfigHtml($attribute,$selectedValue ='', Array $options = array())
	{
		$type = $attribute->getType();
		
		$html = '<button id="paycart-attribute-option-add" type="button" class="btn" onClick="paycart.admin.attribute.addOption(\''.$type.'\')">'.JText::_("Add Option").'</button>'; 
		
		$options = $this->getOptions($attribute);
		$count	 = (count($options) >= 1)?count($options):1;

		//needed to reset keys for proper counter management
		$options = array_values($options);		

		//add global javascript to maintain counter 
		ob_start();
		?>	
			<script type="text/javascript">
				var attributeCounter = <?php echo (!empty($options))?(max(array_keys($options))):'1';?>;
			</script>
		<?php 
		$html .= ob_get_contents();
		ob_clean();
		
		for($i=0; $i < $count ; $i++){
			$html .= $this->buildCounterHtml($i, $type, $options);
		}
		
		return $html;
	}
	
	/**
	 * 
	 * Bulid html for a specific counter
	 * @param $counter : array index to be used for creating html
	 * @param $type : type of attribute
	 * @param $options : Array containing values of attribute options
	 */
	function buildCounterHtml($counter, $type, $options = array())
	{
		ob_start();
		?>	
			<div id="option_row_<?php echo $counter?>">
				 <div class="control-group">
					 <div class='control-label'><label id='title_<?php echo $counter?>_lbl' title=''><?php echo Rb_Text::_("COM_PAYCART_ATTRIBUTES_OPTION_LABEL") ?></label></div>
					
					 <div class='controls'>
					 		<input type='text' name='options[<?php echo $counter?>][title]' id='title_<?php echo $counter?>' 
					      	value='<?php echo (isset($options[$counter]['title'])?$options[$counter]['title']:'')?>'/>
					      	<button class="btn" id="paycart-attribute-option-remove" type="button" onClick="paycart.admin.attribute.removeOption('<?php echo $type?>','<?php echo $counter;?>');">
								<?php echo JText::_('Delete');?>
				 			</button>
					</div>
				</div>
				 
				<input type='hidden' name='options[<?php echo $counter?>][option_ordering]' id='option_ordering_<?php echo $counter?>'  
						  value='<?php echo (isset($options[$counter]['option_ordering'])?$options[$counter]['option_ordering']:$counter) ?>' />
						  
				<input type='hidden' name='options[<?php echo $counter?>][productattribute_option_id]' id='productattribute_option_id_<?php echo $counter?>'  
						  value='<?php echo (isset($options[$counter]['productattribute_option_id'])?$options[$counter]['productattribute_option_id']:0) ?>' />
	
				<input type='hidden' name='options[<?php echo $counter?>][productattribute_option_lang_id]' id='productattribute_option_lang_id_<?php echo $counter?>'  						  
						  value='<?php echo (isset($options[$counter]['productattribute_option_lang_id'])?$options[$counter]['productattribute_option_lang_id']:0) ?>' />
				 
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
		return '';
	}
	
	/**
	 * delete options data from both option and option_lang table
	 */
	function deleteOptions($attributeId=null, $optionId=null)
	{
		$attrOptionModel = PaycartFactory::getModel('productattributeoption');
		
		return $attrOptionModel->deleteOptions($attributeId, $optionId);
	}
	
	/**
	 * format data before saving
	 */
	function formatValue($data)
	{
		return $data;
	}
	
	/**
	 * Returns html that will be used for selector
	 * 
	 * @param $attribute : Instance of PaycartProductAttribute 
	 * @param $selectedOption : Option that should be selected by default
	 * @param $options : comma separaterd string containing optionids that would be considered in filters
	 */
	function getSelectorHtml($attribute,  $selectedOption = '', Array $options = array())
	{
		$options  = PaycartFactory::getModel('productattributeoption')->loadOptions($attribute->getId(), $attribute->getLanguageCode(),$options);
		if(empty($options)){
			return '';
		}	
		
		$html = '<select id="pc-attr-'.$attribute->getId().'" name="attributes['.$attribute->getId().']" onchange="paycart.product.selector.onChange(this)">';
		
		foreach ($options as $option){
			$selected = '';
			if(!empty($selectedOption) && $selectedOption == $option['productattribute_option_id']){
				$selected = "selected='selected'";
			}
			$html  .= '<option value="'.$option['productattribute_option_id'].'" '.$selected.' >'.$option['title'].'</option>' ;
		}
		
		$html .= '</select>';
		return $html;
	}
}