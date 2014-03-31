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
			$html .= "<select id='attribute".$attribute->getId()."' name='attributes[".$attribute->getId()."]'>";
			
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
		static $js;
		$html   = '';
		
		//load required assets
		$document = Rb_Factory::getDocument(); 

		//PCTODO: add option to load minified js file 
		$document->addScript(PAYCART_PATH_CORE.'/attributes/color/jquery.minicolors.js' );
		$document->addStyleSheet(PAYCART_PATH_CORE.'/attributes/color/jquery.minicolors.css');
		
		// don't load it more than once
		if(!isset($js)){
			ob_start();
			?>	
			<script>
				$(document).ready( function() {
		            $('.wheel-color').each( function() {
						$(this).minicolors({
							control: $(this).attr('data-control') || 'hue',
							defaultValue: $(this).attr('data-defaultValue') || '',
							inline: $(this).attr('data-inline') === 'true',
							letterCase: $(this).attr('data-letterCase') || 'lowercase',
							opacity: $(this).attr('data-opacity'),
							position: $(this).attr('data-position') || 'bottom left',
							change: function(hex, opacity) {
								var log;
								try {
									log = hex ? hex : 'transparent';
									if( opacity ) log += ', ' + opacity;
									console.log(log);
								} catch(e) {}
							},
							theme: 'default'
						});
		                
		            });
					
				});
			</script>
			<?php 
			
			$js .= ob_get_contents();
			ob_end_clean();
		}
		
		$html  .= $js;
		
		$colors = PaycartFactory::getModel('color')->loadOptions($attribute->getLanguageCode());
		$count  = (count($colors) > 0)?count($colors):1;

		for($i=0; $i < $count ; $i++){
			$html .= "<div class='control-label'><label id='hashcode_".$i."_lbl' title=''>".Rb_Text::_("COM_PAYCART_ATTRIBUTE_COLOR_HASHCODE_LABEL")."</label></div>";
			$html .= "<div class='controls'><input type='text' id='hashcode_".$i."' name='colors[$i][hashcode]'  class='wheel-color' placeholder='#rrggbb'
					  value='".isset($colors[$i]['hash_code'])?$colors[$i]['hash_code']:'none'."'/></div>";
			
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
	 * build attribute specific data
	 * it will be required while saving
	 */
	function buildOptions($attribute, $data)
	{	
		$colors = (isset($data['colors'])) ? $data['colors']: array();
		
		if(empty($colors) && $attribute->getId()){
			$colors = PaycartFactory::getInstance('color','model')
					                           ->loadOptions($attribute->getLangugeCode());
		}
		
		$result = array();
		foreach ($colors as $id => $color){
			$result[$id]->title 	= $color['title'];
			$result[$id]->hash_code	= $color['hash_code'];
			$result[$id]->color_id  = $color['color_id'];
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
			$colorModel 	   = PaycartFactory::getInstance('color', 'model');
			$colorId 		   = $colorModel->save($data, $option->color_id);
			if(!$colorId){
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $colorModel->getError());
			}
			//save langauge specific data of options
			$data = array();
			$data['color_id']  = $colorId;
			$data['lang_code'] = $attribute->getLanguageCode();
			$data['title']	   = $option->title;
			$colorLangModel    = PaycartFactory::getInstance('colorlang', 'model');
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
	function deleteOptions($attribute)
	{
		return true;
	}
}