<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
PaycartHtml::_('behavior.framework', true);
// validation for required fields
PaycartHtml::_('behavior.formvalidation');
?>
<script src="<?php echo Rb_HelperTemplate::mediaURI(PAYCART_PATH_CORE.'/attributes/color/jquery.minicolors.js',false)?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo Rb_HelperTemplate::mediaURI(PAYCART_PATH_CORE.'/attributes/color/jquery.minicolors.css',false);?>"/>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.id('paycart_productattribute_form'))) {
			Joomla.submitform(task, document.getElementById('paycart_productattribute_form'));
		}
	};

	(function($)
		{
			$(document).ready(function()
			{
				paycart.form.validation.init('#paycart_productattribute_form');
				paycart.admin.attribute.addColorScript();
			});
			
			$(document).on('change','#paycart_form_attribute_type',function()
			{
				var attributeId = $('#paycart_form_productattribute_id').val();
				paycart.admin.attribute.getTypeConfig($(this).val(), attributeId);
			});

			//load required script for radio buttons
			paycart.radio.onLoad();
			
	})(paycart.jQuery);
	
</script>

<form action="<?php echo Rb_Route::_('index.php?option=com_paycart&view=productattribute'); ?>" method="post" name="adminForm" id="paycart_productattribute_form" class="rb-validate-form">
<div class="row-fluid">
	<div class=" span6 form-horizontal">
		<?php $language = $form->getFieldset('language');?>
		<?php $field    = $language['paycart_form_language_title']?>
		<div class="control-group">
			<div class="control-label"><?php echo $field->label; ?> </div>
			<div class="controls"><?php echo $field->input; ?></div>								
		</div>

		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('status'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('status'); ?>	
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('filterable'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('filterable'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('searchable'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('searchable'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('css_class'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('css_class'); ?>
			</div>
		</div>		
	</div>
	
	<div class="span6 form-horizontal">
			<div class="control-group">
				<div class="control-label">
					<?php echo $form->getLabel('type'); ?>
				</div>
				<div class="controls">
					<?php echo $form->getInput('type'); ?>
				</div>
			</div>
			
			<?php echo $attributeHtml;?>
	</div>	
</div>

<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="apply" />
	<?php echo $form->getInput('productattribute_id');?>
	<?php echo $form->getInput('productattribute_lang_id');?>
</form>
