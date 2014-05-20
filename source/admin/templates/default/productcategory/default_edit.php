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

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
	
<fieldset>

	<div class="span6">
		<div class="control-group">
			<?php echo $form->getLabel('productcategory_id'); ?>
			<div class="controls">
				<?php echo $form->getInput('productcategory_id'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $form->getLabel('status'); ?>
			<div class="controls">
				<?php echo $form->getInput('status'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $form->getLabel('parent_id'); ?>
			<div class="controls">
				<?php echo $form->getInput('parent_id'); ?>
			</div>
		</div>
		
		<fieldset class="form-horizontal">	
				<?php foreach ($form->getFieldset('language') as $field):?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>								
					</div>
				<?php endforeach;?>
		</fieldset>
	</div>
	
	<div class="span6">
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
	</div>
	
	
</fieldset>

<!--========	Hiddens variables	========-->	
	<input type="hidden" name="task" value="save" />
	<?php echo $form->getInput('productcategory_id');?>
</form>
