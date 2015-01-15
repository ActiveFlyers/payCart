<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE');?></h3>
</div>

<div class="modal-body">
	<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE_INIT_MSG');?>	
		<p>&nbsp;</p>
		<p class="alert alert-info"><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE_CAN_NOT_REMOVE_DEFAULT_LANGUAGE');?></p>
	</div>
	<p>&nbsp;</p>
	<?php $field = $form->getField('localization_supported_language') ?>	
	<div class="form-horizontal" style="min-height:150px;">
		<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<select name="<?php echo $field->name;?>" id="<?php echo $field->id;?>" multiple="true">
					<?php foreach($languages as $code => $language) :?>
						<option 
							value="<?php echo $code;?>" 
							<?php echo in_array($code, $supported_language) ? 'selected="selected" ' : '';?>
							<?php echo $code === $default_language ? 'disabled=""' : '';?>>
							<?php echo empty($language->name) ? $language->element : $language->name;?>
						</option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer text-center">
	<button class="btn" onclick="return false;" data-dismiss="modal"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CANCEL')?></button>
	<button class="btn btn-primary" onclick="paycart.admin.config.updateSupportedLanguage.confirmed(); return false;"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_NEXT')?></button>												
</div>

<script>
(function($){		
	$("#<?php echo $field->id;?>").chosen({"disable_search_threshold":3});
})(paycart.jQuery);	
</script>
<?php 