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
	<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_DEFAULT_LANGUAGE');?></h3>
</div>

<div class="modal-body">
	<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_DEFAULT_LANGUAGE_INIT_CONFIRM');?>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_DEFAULT_LANGUAGE_INIT_TASK');?>		 
		<ul>
			<li><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_DEFAULT_LANGUAGE_INIT_TASK_COPY_DATA');?></li>
			<li><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_DEFAULT_LANGUAGE_INIT_TASK_WILL_NOT_COPY_DATA');?></li>						
		</ul>
	</div>
	<p>&nbsp;</p>
	<?php $field = $form->getField('localization_default_language') ?>	
	<div class="form-horizontal">
		<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer text-center">
	<button class="btn" onclick="return false;" data-dismiss="modal"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CANCEL')?></button>
	<button class="btn btn-primary" onclick="paycart.admin.config.changeDefaultLanguage.confirmed(); return false;"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_NEXT')?></button>												
</div>

<?php 