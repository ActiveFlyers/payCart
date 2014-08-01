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
	function getEditHtml($attribute, $value= '', Array $options = array())
	{
		$media = array();
		$media['media_id']         = 0;
		$media['title']            = '';
		$media['path']             = '';
		$media['media_lang_id']    = 0;
		$media['is_free']		   = 1;
		$media['description']	   = '';
		$media['metadata_title']   = '';
		$media['metadata_keywords'] = '';
		$media['metadata_description'] = '';
		
		$attrId = $attribute->getId();
		if(!is_null($value)){
			$record = PaycartFactory::getModel('media')->loadRecords(array('media_id' => $value));
			$media  = array_shift($record);
			$media = (array)$record;
		}
		
		$html  = '';

		ob_start();
		?>
			<div class="control-group">
					<div class="control-label">
					<label id="title<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_TITLE_LABEL')?></label>
				 </div>
				 <div class="controls">
					<input type="text" name="paycart_form[attributes][<?php echo $attrId ?>][title]" value="<?php echo $media['title'] ?>"/>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-group">
					<div class="control-label">
						<label id="path<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_DESCRIPTION_LABEL') ?></label>
					 </div>
					 <div class="controls">
						<textarea name="paycart_form[attributes][<?php echo $attrId ?>][description]"><?php echo $media['description'] ?></textarea>
					 </div>
				</div>
			</div>
		
			<div class="control-group">
				<div class="control-group">
					<div class="control-label">
						<label id="path<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_PATH_LABEL') ?></label>
					 </div>
					 <div class="controls">
						<input type="file" name="paycart_form[attributes][<?php echo $attrId ?>][path]" value="<?php echo $media['path'] ?>"/>
					 </div>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<label id="isfree<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_IS_FREE_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<input type="radio" name="paycart_form[attributes][<?php echo $attrId ?>][is_free]" value="1" <?php echo (($media['is_free'] == 1)?"checked='checked'":'') ?> /> <?php echo Rb_Text::_('COM_PAYCART_YES')?>
				   <input type="radio" name="paycart_form[attributes][<?php echo $attrId ?>][is_free]" value="0"<?php echo (($media['is_free'] == 0)?"checked='checked'":'' )?> /> <?php echo Rb_Text::_('COM_PAYCART_NO')?>
				 </div>
			</div>
			
			<div class="control-group">			 		
				<div class="control-label">
					<label id="metatitle<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_META_TITLE_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<input type="text" name="paycart_form[attributes][<?php echo $attrId ?>][metadata_title]" value="<?php echo $media['metadata_title'] ?>"/>
				 </div>
			</div>
				
			<div class="control-group">
				<div class="control-label">
					<label id="metakeyword<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_META_KEYWORD_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<textarea name="paycart_form[attributes][<?php echo $attrId ?>][metadata_keywords]"><?php echo $media['metadata_keywords'] ?></textarea>
				 </div>
			</div>
			
			<div class="control-group">	
				<div class="control-label">
					<label id="metadescription<?php echo $attrId ?>_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_META_DESCRIPTION_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<textarea name="paycart_form[attributes][<?php echo $attrId ?>][metadata_description]"><?php echo $media['metadata_description']?></textarea>
				 </div>
			</div>
		
		<input type="hidden" name="paycart_form[attributes][<?php echo $attrId ?>][media_id]" value="<?php echo (isset($media['media_id'])?$media['media_id']:'0')?>" />
		<input type="hidden" name="paycart_form[attributes][<?php echo $attrId ?>][media_lang_id]" value="<?php echo (isset($media['media_lang_id'])?$media['media_lang_id']:'0')?>" />
		<?php 
		$html .= ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
	 * get config html
	 */
	function getConfigHtml($attribute,$selectedValue ='', Array $options = array())
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
	function formatValue($data)
	{
		//if data contain value directly then return data as it is 
		if(is_array($data) && !isset($data['media_id'])) {
			return $data;
		}
		
		$media = array();
		$media['media_id'] = $data['media_id'];
		$media['is_free']  = $data['is_free'];
		
		//if file exist then upload it at proper location and get details like path and type
		if(isset($data['path']) && is_array($data['path']) && !empty($data['path']['tmp_name'])){
			$details 		   = PaycartHelperMedia::upload($data['path']);
			$media['path']	   = isset($details['path'])?$details['path']:'';
			$media['type']     = isset($details['type'])?$details['type']:'';
		}
	
		$media['media_lang_id'] = $data['media_lang_id'];			
		$media['title']			= $data['title'];
		$media['description']	= $data['description'];	
		$media['lang_code'] 	= PaycartFactory::getLanguage()->getTag();
		$media['metadata_title'] 	   = $data['metadata_title'];
		$media['metadata_keywords']    = $data['metadata_keywords'];
		$media['metadata_description'] = $data['metadata_description'];
				
		$mediaModel = PaycartFactory::getInstance('media','model');
		$mediaId 	= $mediaModel->save($media,$media['media_id']);
		
		if(!$mediaId){
			throw new RuntimeException(Rb_Text::_("COM_PAYCART_ADMIN_ERROR_ITEM_SAVE"), $mediaModel->getError());
		}
		
		return $mediaId;
	}
}