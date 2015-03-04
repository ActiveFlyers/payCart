<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

//@PCTODO: mention all populated variables

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
<style>
	/* To load popup in proper position */
	#paycart div.modal {
	    margin-left: -200px;
	}
</style>

<script>
(function($)
{
	$(document).ready(function()
	{
		paycart.formvalidator.initialize('#paycart_state_form');
		
		//load required script for radio buttons
		paycart.radio.init(); 
	});
})(paycart.jQuery);
</script>

<!-- LANGUAGE SWITCHER -->
<?php 
	if(PAYCART_MULTILINGUAL){
		$lang_code = PaycartFactory::getPCCurrentLanguageCode();
		$flag = '<span class="pull-left pc-language">'.PaycartHtmlLanguageflag::getFlag($lang_code).' &nbsp; '.'</span>';
	}
	else{
		$flag = '';
	}
?>
	
<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo JText::_($model_title); ?></h3>
	</div>
</div>

<div id="rbWindowBody">
	<div class="modal-body form-horizontal">
		<!--  New state creation body		-->
		<form method="post"  id="paycart_state_form" class="pc-form-validate">
			<?php $field = $form->getField('title') ?>
			<div class="control-group">
				<div class="control-label"><?php echo $flag; ?><?php echo $field->label; ?> </div>
				<div class="controls"><?php echo $field->input; ?>
					<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>								
			</div>

			<?php $field = $form->getField('isocode') ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?> </div>
				<div class="controls"><?php echo $field->input; ?>
					<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>								
			</div>
					
			<?php $field = $form->getField('country_id') ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?> </div>
				<div class="controls"><?php echo $field->input; ?></div>								
			</div>
				
			<?php $field = $form->getField('published') ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?> </div>
				<div class="controls"><?php echo $field->input; ?></div>								
			</div>
		 	
		 	<?php echo $form->getInput('lang_code'); ?>
		 	<?php echo $form->getInput('state_lang_id'); ?>			
			<input type="hidden" name="task" value="save" />
			<input type='hidden' name='id' value='<?php echo $record_id;?>' />			
		</form>
	</div>
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
		<button class="btn btn-primary " onClick="paycart.admin.state.add.go();"> 
			<?php echo JText::_('COM_PAYCART_ADMIN_SAVE'); ?> </button>
		<button class="btn" data-dismiss="modal" aria-hidden="true" ><?php echo JText::_('COM_PAYCART_ADMIN_CANCEL'); ?> </button>
	</div>
</div>






