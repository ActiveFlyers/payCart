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
 * attribute type media
 */

class PaycartAttributeMedia extends PaycartAttribute
{
	public $type = 'media';
	
	/**
	 *  return edit html that will be displayed on product edit screen
	 */
	function getEditHtml($attribute, $value= null)
	{
		$media = array();
		$media['media_id']         = 0;
		$media['title']            = '';
		$media['path']             = '';
		$media['media_lang_id']    = 0;
		$media['is_free']		   = 1;
		$media['metadata_title']   = '';
		$media['metadata_keyword'] = '';
		$media['metadata_description'] = '';
		
		$attrId = $attribute->getId();
		if(!is_null($media)){
			$media = PaycartFactory::getModel('media')->loadRecord($attribute->getLanguageCode(),$value);
		}
		
		$html  = '';
		
		$html .= '<div class="control-label">
					<label id="title'.$attrId.'_lbl" title="">'.Rb_Text::_('COM_PAYCART_MEDIA_TITLE_LABEL').'</label>
				 </div>
				 <div class="controls">
					<input type="text" name="attributes['.$attrId.'][title]" value="'.$media['title'].'"/>
				 </div>';
		
		$html .= '<div class="control-label">
					<label id="path'.$attrId.'_lbl" title="">'.Rb_Text::_('COM_PAYCART_MEDIA_PATH_LABEL').'</label>
				 </div>
				 <div class="controls">
					<input type="file" name="attributes['.$attrId.'][path]" value="'.$media['path'].'"/>
				 </div>';
		
		$html .= '<div class="control-label">
					<label id="isfree'.$attrId.'_lbl" title="">'.Rb_Text::_('COM_PAYCART_MEDIA_IS_FREE_LABEL').'</label>
				 </div>
				 <div class="controls">'.
					'<input type="radio" name="attributes['.$attrId.'][is_free]" value="1"'.($media['is_free'] == $value['is_free'])?"checked='checked'":''.'/>'.Rb_Text::_('COM_PAYCART_YES')
				   .'<input type="radio" name="attributes['.$attrId.'][is_free]" value="0"' .($media['is_free'] == $value['is_free'])?"checked='checked'":''.'/>'.Rb_Text::_('COM_PAYCART_NO').
				 '</div>';
		
		$html .= '<div class="control-label">
					<label id="metatitle'.$attrId.'_lbl" title="">'.Rb_Text::_('COM_PAYCART_MEDIA_META_TITLE_LABEL').'</label>
				 </div>
				 <div class="controls">
					<input type="text" name="attributes['.$attrId.'][metadata_title]" value="'.$media['metadata_title'].'"/>
				 </div>';
		
		$html .= '<div class="control-label">
					<label id="metakeyword'.$attrId.'_lbl" title="">'.Rb_Text::_('COM_PAYCART_MEDIA_META_KEYWORD_LABEL').'</label>
				 </div>
				 <div class="controls">
					<input type="text" name="attributes['.$attrId.'][metadata_keyword]" value="'.$media['metadata_keyword'].'"/>
				 </div>';
		
		$html .= '<div class="control-label">
					<label id="metadescription'.$attrId.'_lbl" title="">'.Rb_Text::_('COM_PAYCART_MEDIA_META_DESCRIPTION_LABEL').'</label>
				 </div>
				 <div class="controls">
					<input type="text" name="attributes['.$attrId.'][metadata_description]" value="'.$media['metadata_description'].'"/>
				 </div>';
		
		$html .= '<input type="hidden" name="attributes['.$attrId.'][media_id]" value="'.isset($media['media_id'])?$media['media_id']:'0'.'" />';
		$html .= '<input type="hidden" name="attributes['.$attrId.'][media_lang_id]" value="'.isset($media['media_lang_id'])?$media['media_lang_id']:'0'.'" />';
		
		return $html;
	}
	
	/**
	 * get config html
	 */
	function getConfigHtml($attribute)
	{
	 	return '';
	} 
	
	function buildOptions($attribute, $data)
	{
		return array(); 
	}
	
	/**
	 * return mediaid after saving data in media table
	 */
	function save($data, $file)
	{
		$media = array();
		$media['media_id'] = $data['media_id'];
		$media['is_free']  = $data['is_free'];
		
		//upload file at proper location and get details like path and type
		$details = PaycartHelperMedia::upload($file['path']);
		$media['path']	   = isset($details['path'])?$details['path']:'';
		$media['type']     = isset($details['extension'])?$details['extension']:'';

		$mediaModel = PaycartFactory::getInstance('media','model');
		$mediaId 	= $mediaModel->save($media,$media['media_id']);
		if(!$mediaId){
			throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $mediaModel->getError());
		}
		
		$media = array();
		$media['media_lang_id'] = $data['media_lang_id'];
		$media['media_id']		= $mediaId;	
		$media['title']			= $data['title'];
		$media['lang_code'] 	= PaycartFactory::getLanguageTag();
		$media['metadata_title'] 	   = $data['metadata_title'];
		$media['metadata_keyword'] 	   = $data['metadata_keyword'];
		$media['metadata_description'] = $data['metadata_description'];
		
		$langModel = PaycartFactory::getInstance('medialang','model');
		$mediaLangId = $langModel->save($media,$media['media_lang_id']);
		if(!$mediaLangId){
			throw new RuntimeException(Rb_Text::_("COM_PAYCART_UNABLE_TO_SAVE"), $mediaModel->getError());
		}
		
		return $mediaId;
	}
}