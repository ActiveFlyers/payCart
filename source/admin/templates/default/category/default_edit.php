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
	
<fieldset>
	<div class="control-group">
		<?php echo $form->getLabel('category_id'); ?>
		<div class="controls">
			<?php echo $form->getInput('category_id'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('name'); ?>
		<div class="controls">
			<?php echo $form->getInput('name'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('alias'); ?>
		<div class="controls">
			<?php echo $form->getInput('alias'); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo $form->getLabel('description'); ?>
		<div class="controls">
			<?php echo $form->getInput('description'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('parent'); ?>
		<div class="controls">
			<?php echo $form->getInput('parent'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('published'); ?>
		<div class="controls">
			<?php echo $form->getInput('published'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('cover_media'); ?>
		<div class="controls">
			<?php echo $form->getInput('cover_media'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('created_date'); ?>
		<div class="controls">
			<?php echo $form->getInput('created_date'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('modified_date'); ?>
		<div class="controls">
			<?php echo $form->getInput('modified_date'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<?php echo $form->getLabel('created_by'); ?>
		<div class="controls">
			<?php echo $form->getInput('created_by'); ?>
		</div>
	</div>
	
</fieldset>

<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />	
</form>
