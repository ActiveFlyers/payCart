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

//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
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

	<!--========	Product Basic Attributes	========-->
	<div class="span5">
		<fieldset class="form-horizontal">	
			<legend> <?php echo Rb_Text::_('COM_PAYCART_PRODUCT_BASIC_ATTRIBUTES_FIELDSET_LABEL' ); ?> </legend>
			<?php $fieldSets = $form->getFieldsets();?>
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

	<!--========	Product Meta Data Attributes	========-->
	<div class="span5">
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
	</div>


	<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />				
</form>
