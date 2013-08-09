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
<div class="row-fluid">
	<div class=" span12 form-horizontal">
		
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
		
		<div class="paycart-attribute-type-element"></div>
	</div>	
</div>

<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="create" />
	<input type='hidden' name='id' value='<?php echo $form->getInput('attribut_id');?>' />	
</form>
