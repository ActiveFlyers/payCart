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
?>
<script src="<?php echo Rb_HelperTemplate::mediaURI(PAYCART_PATH_CORE.'/attributes/color/jquery.minicolors.js',false)?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo Rb_HelperTemplate::mediaURI(PAYCART_PATH_CORE.'/attributes/color/jquery.minicolors.css',false);?>"/>

<script type="text/javascript">
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
			paycart.radio.init();
			
	})(paycart.jQuery);
	
</script>

<div class="pc-product-wrapper clearfix">
<div class="pc-product row-fluid">
	<form action="index.php" onSubmit="return fasle;" method="post" data="pc-json-attribute-edit">
	<div class=" span6">
		<div class="control-group">
			<div class="control-label"><?php echo $form->getLabel('title'); ?> </div>
			<div class="controls"><?php echo $form->getInput('title'); ?></div>								
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
	
	<div class="span6">
			<div class="control-group">
				<div class="control-label">
					<?php echo $form->getLabel('type'); ?>
				</div>
				<div class="controls">
					<?php echo $form->getInput('type'); ?>
				</div>
			</div>
			<div id="paycart-attribute-config">
				<?php echo $attributeHtml;?>
			</div>
	</div>	
	
	<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="apply" />
	<?php echo $form->getInput('productattribute_id');?>
	<?php echo $form->getInput('productattribute_lang_id');?>
	
	</form>
</div>
</div>



