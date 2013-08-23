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

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};

	(function($)
		{
			$(document).ready(function()
			{

				var attributeId = $('#paycart_form_attribute_id').val();
				paycart.admin.attribute.getTypeConfig($('#paycart_form_attribute_type').val(),attributeId);

				$('#paycart_form_attribute_type').change(function()
				{
					paycart.admin.attribute.getTypeConfig($(this).val(), attributeId); 
				});
			}); 
		}
	)(paycart.jQuery);
	
</script>

<form action="<?php echo Rb_Route::_('index.php?option=com_paycart&view=attribute'); ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
<div class="row-fluid">
	<div class=" span12 form-horizontal">
		
		<div class="control-group hidden">
			<div class="control-label">
				<?php echo $form->getLabel('attribute_id'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('attribute_id'); ?>	
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('title'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('title'); ?>	
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('type'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('type'); ?>
			</div>
		</div>
		
		<div class="paycart-attribute-type-elements" id="paycart-attribute-type-elements"></div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('default'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('default'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<?php echo $form->getLabel('published'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('published'); ?>
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
				<?php echo $form->getLabel('visible'); ?>
			</div>
			<div class="controls">
				<?php echo $form->getInput('visible'); ?>
			</div>
		</div>
		
	</div>	
</div>

<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="apply" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />	
</form>
