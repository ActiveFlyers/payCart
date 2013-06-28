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

PaycartHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<?php echo PaycartHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
<!--========	Product Basic Attributes	========-->
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'basic', Rb_Text::_('COM_PAYCART_PRODUCT_BASIC_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
				<?php $field = $form->getField('name') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				<?php $field = $form->getField('description') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
			
<!--========	Product Core Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'core', Rb_Text::_('COM_PAYCART_PRODUCT_CORE_ATTRIBUTES_FIELDSET_LABEL', true)); ?>				
				
				<?php $field = $form->getField('product_id') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $form->getField('category_id') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				<?php $field = $form->getField('quantity') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				<?php $field = $form->getField('featured') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				<?php $field = $form->getField('cover_image') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
			<?php echo PaycartHtml::_('bootstrap.endTab'); ?>

<!--========	Product Custom Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'custom', Rb_Text::_('COM_PAYCART_PRODUCT_CUSTOM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
				
				<?php $field = $form->getField('published') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php echo PaycartHtml::_('bootstrap.endTab'); ?>	
				
<!--========	Product Custom Attributes	========-->			
			<?php echo PaycartHtml::_('bootstrap.addTab', 'myTab', 'system', Rb_Text::_('COM_PAYCART_PRODUCT_SYSTEM_ATTRIBUTES_FIELDSET_LABEL', true)); ?>
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
				
				<fieldset class="form-horizontal">	
				<legend> <?php echo Rb_Text::_('COM_PAYCART_PRODUCT_META_DATA_ATTRIBUTES_FIELDSET_LABEL' ); ?> </legend>
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
				<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
				
			<?php echo PaycartHtml::_('bootstrap.endTabSet'); ?>
		</div>
		

<!--========	Product System Attributes	========-->
		<div class="span2">
		<h4><?php echo Rb_Text::_('COM_PAYCART_PRODUCT_DETAIL' ); ?> </h4>
			<hr>
			<fieldset class="form-vertical">	
				
				<?php $field = $form->getField('type') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $form->getField('amount') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $form->getField('alias') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $form->getField('sku') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $form->getField('published') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>	
			</fieldset>
		</div>		

	</div>
	
<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />	
</form>
