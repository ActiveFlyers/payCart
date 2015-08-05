<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
<script type="text/javascript">
	(function($)
		{
			paycart.jQuery('.hasTooltip').tooltip();
		})
	(paycart.jQuery);	
</script>

<?php 
	if(PAYCART_MULTILINGUAL){
		$lang_code = PaycartFactory::getPCCurrentLanguageCode();
		$flag = '<span class="pull-left pc-language">'.PaycartHtmlLanguageflag::getFlag($lang_code).' &nbsp; '.'</span>';
	}
	else{
		$lang_code = PaycartFactory::getPCDefaultLanguageCode();
		$flag = '';
	}
?>
	<div id="rbWindowTitle">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_FILES_ADD_OR_EDIT'); ?></h3>
		</div>
	</div>
	
	<div id="rbWindowBody">
		<div class="modal-body form-horizontal" style="height:300px; width:500px">
		<!--  New state creation body		-->
				<div data-pc-seletor="digi-error-msg" class="pc-break-word hide alert alert-danger"></div>
				<form method="post" action="index.php?option=com_paycart&view=product&task=saveFile" id="paycart_product_file_form" class="pc-form-validate" enctype="multipart/form-data" encoding="multipart/form-data">
					<div class="control-group">
						<div class="control-label">
							<?php echo $flag;?><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_FILES_TITLE')?>
						</div>
						
						<div class="controls">
							<input type="text" class="required" name="paycart_product_file_form[digital][title]" id="paycart_product_file_form__digital_content_title" value="<?php echo $media['title']?>">
						</div>
					</div>
					
					<div class="pc-upload-limit-msg text-info muted"><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_MAX_UPLOAD_SIZE').' - '.PaycartFactory::getHelper('media')->getUploadLimit()/1048576 .' MB';?><br></div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_MAIN_FILE')?>
						</div>
						
						<div class="controls">
							<?php if(!empty($media['filename'])):?>
								<div><?php echo $media['filename']?></div>
							<?php endif;?>
							<input type="file" name="paycart_product_file_form[digital][main_file]" id="paycart_product_file_form__digital_content_main">
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_TEASER_FILE')?>
						</div>
						
						<div class="controls">
							<?php if(!empty($teaserMedia['filename'])):?>
								<div><?php echo $teaserMedia['filename']?></div>
							<?php endif;?>
							<input type="file" name="paycart_product_file_form[digital][teaser_file]" id="paycart_product_file_form__digital_content_teaser">
						</div>
					</div>
					
					
				
					<!--========	Hiddens variables	========-->	
					<input type="hidden" name="paycart_product_file_form[digital][media_id]" value="<?php echo $media['media_id']?>"/>
					<input type="hidden" name="paycart_product_file_form[digital][teaser_media_id]" value="<?php echo $teaserMedia['media_id']?>"/>					
					<input type="hidden" name="paycart_product_file_form[digital][media_lang_id]" value="<?php echo $media['media_lang_id']?>"/>
					<input type="hidden" name="paycart_product_file_form[digital][teaser_media_lang_id]" value="<?php echo $teaserMedia['media_lang_id']?>"/>					
					<input type="hidden" name="paycart_product_file_form[digital][lang_code]" value="<?php echo $media['lang_code']?>"/>	
				</form>
			</div>
		</div>
		
		<div class="modal-footer text-center">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CANCEL')?></button>
			<button class="btn btn-primary" onClick="paycart.admin.product.digital.save();"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_SAVE')?></button>												
		</div>
<?php 