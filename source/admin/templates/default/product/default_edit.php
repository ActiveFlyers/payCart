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

	
	(function($){

		// Change Attributes on bases of product type		
		typeAttributes = function(type) {
				switch(type) 
				{//@PCTODO :: use constant
					case '10':	// Physical type
						$('.paycart_product_digital_file').hide();
						$('.paycart_product_quantity').show();
						break;
					case '20' :	// Digital Type
						$('.paycart_product_digital_file').show();
						$('.paycart_product_quantity').hide();
						break;
				}
			};	 	
	})(paycart.jQuery);
	

	paycart.jQuery(document).ready(function($){
		<!-- Callback function when Alias successfully generated				-->
		var callbackOnSuccess = function(data)
		{	// Add alias to element
			$('#paycart_form_alias').val(data[0][1]);
		};
		
		<!-- Callback function when error occur during category adding operation	-->
		var callbackOnError = function ()
		{
			//@PCTODO :: Proper Error-handling 
			alert('error in alias generating');
		};
	
		$('#paycart_form_title').blur( function()
		{
			var title = $(this).val();
	
			// if title empty or alias pre-define
			if (!title || $('#paycart_form_alias').val()) {
				return true;
			}
	
			paycart.alias.add(title, callbackOnSuccess, callbackOnError);
			
		});

		typeAttributes($('#paycart_form_type').val());

		$('#paycart_form_type').change(
						function() {
							typeAttributes($(this).val());
						});
		
		
	});
</script>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data" >
	<div class="row-fluid">
		<div class="<?php echo count($variants)? 'span10' : 'span12'; ?> form-horizontal">
			<?php echo PaycartHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
<!--========	Product Basic Attributes	========-->
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'basic', Rb_Text::_('COM_PAYCART_PRODUCT_BASIC_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
				
				<div class="span6">
					<?php $field = $form->getField('title') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
					
					<?php $field = $form->getField('alias') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>	
					
					<?php $field = $form->getField('category_id') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('amount') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('sku') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('teaser') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('description') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('cover_media') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
	
					<?php $field = $form->getField('featured') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('published') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>	
					
					<?php $field = $form->getField('publish_up') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('publish_down') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				</div>	
				
				<div class="span6">
					
					<?php $field = $form->getField('type') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('quantity') ?>
					<div class="control-group paycart_product_quantity">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('digital_file') ?>
					<div class="control-group paycart_product_digital_file">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				</div>
				
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
			
<!--========	Product Custom Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'custom', Rb_Text::_('COM_PAYCART_PRODUCT_CUSTOM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>				
				<?php 
						echo $this->loadtemplate('customattribute');
				?>
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>

<!--========	Product System Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'system', Rb_Text::_('COM_PAYCART_PRODUCT_SYSTEM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
			
				<div class="span6">
					<?php $field = $form->getField('product_id') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					
					<?php $field = $form->getField('created_date') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
					<?php $field = $form->getField('modified_date') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>		
				</div>
				<!--	Product Meta Data			-->
				<div class="span6">
					<fieldset class="form-horizontal">	
<!--					<legend> <?php //echo Rb_Text::_('COM_PAYCART_PRODUCT_META_DATA_ATTRIBUTES_FIELDSET_LABEL' ); ?> </legend>-->
					<?php $fieldSets = $form->getFieldsets('meta_data');?>
						<?php foreach ($fieldSets as $name => $fieldSet) :?>
							<?php foreach ($form->getFieldset($name) as $field):?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
							<?php endforeach;?>
						<?php endforeach;?>
					</fieldset>
				</div>
				
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>	
				
<!--========	Product Custom Attributes	========-->			
			<!--<?php // echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'log', Rb_Text::_('COM_PAYCART_PRODUCT_SYSTEM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
				<?php //@PCTODO:: ?>
			<?php //echo PaycartHtml::_('bootstrap.endTab'); ?>-->
			
		<?php echo PaycartHtml::_('bootstrap.endTabSet'); ?>
			

<!--========	Product Variants	========-->
		<?php if (count($variants)) { ?>
		<div class="span2 ">
			<h4><?php echo Rb_Text::_('COM_PAYCART_PRODUCT_VARIANT' ); ?> </h4> <hr>
			<fieldset class="form-vertical">	
				Product Variant
			</fieldset>
		</div>		
		<?php }?>	

		</div>
	</div>
	
<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />	
</form>
