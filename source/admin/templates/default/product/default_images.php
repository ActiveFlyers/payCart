<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		rimjhim
*/

/**
 * 
 * Image Gallery
 * 
 */
defined('_JEXEC') or die(); ?>

			<div class="control-group">
					<div class="control-label">
					<label id="title_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_TITLE_LABEL')?></label>
				 </div>
				 <div class="controls">
					<input type="text" name="paycart_form[images][title]" value=""/>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-group">
					<div class="control-label">
						<label id="path_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_DESCRIPTION_LABEL') ?></label>
					 </div>
					 <div class="controls">
						<textarea name="paycart_form[images][description]"></textarea>
					 </div>
				</div>
			</div>
		
			<div class="control-group">
				<div class="control-group">
					<div class="control-label">
						<label id="path_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_PATH_LABEL') ?></label>
					 </div>
					 <div class="controls">
						<input type="file" name="paycart_form[images]" value=""/>
					 </div>
				</div>
			</div>
			
			<div class="control-group">			 		
				<div class="control-label">
					<label id="metatitle_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_META_TITLE_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<input type="text" name="paycart_form[images][metadata_title]" value=""/>
				 </div>
			</div>
				
			<div class="control-group">
				<div class="control-label">
					<label id="metakeyword_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_META_KEYWORD_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<textarea name="paycart_form[images][metadata_keywords]"></textarea>
				 </div>
			</div>
			
			<div class="control-group">	
				<div class="control-label">
					<label id="metadescription_lbl" title=""><?php echo Rb_Text::_('COM_PAYCART_MEDIA_META_DESCRIPTION_LABEL') ?></label>
				 </div>
				 <div class="controls">
					<textarea name="paycart_form[images][metadata_description]"></textarea>
				 </div>
			</div>
		
		<input type="hidden" name="paycart_form[images][media_id]" value="0" />
		<input type="hidden" name="paycart_form[images][media_lang_id]" value="0" />
		
<?php 