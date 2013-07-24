<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		Puneet Singhal 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

PaycartHtml::_('behavior.formvalidation');
?>

<?php 
$fieldsets = $form->getFieldsets();
?>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">

	<div class="row-fluid">
		<fieldset class="span6 form-horizontal">
			<!-- @PCTODO: Cross-check which is correct method -->
			<legend><?php $fieldset = array_shift($fieldsets); echo JText::_($fieldset->label);	?></legend>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('cart_id'); ?> </div>
				<div class="controls"><?php echo $form->getInput('cart_id'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('buyer_id'); ?> </div>
				<div class="controls"><?php echo $form->getInput('buyer_id'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('status'); ?> </div>
				<div class="controls"><?php echo $form->getInput('status'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('subtotal'); ?> </div>
				<div class="controls"><?php echo $form->getInput('subtotal'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('modifiers'); ?> </div>
				<div class="controls"><?php echo $form->getInput('modifiers'); ?></div>								
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('total'); ?> </div>
				<div class="controls"><?php echo $form->getInput('total'); ?></div>								
			</div>
			
			
		</fieldset>
		
		
		<fieldset class="span6 form-horizontal">
			<legend><?php $fieldset = array_shift($fieldsets); echo JText::_($fieldset->label);	?></legend>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('created_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('created_date'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('modified_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('modified_date'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('checkout_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('checkout_date'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('paid_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('paid_date'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('complete_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('complete_date'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('cancellation_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('cancellation_date'); ?></div>								
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $form->getLabel('refund_date'); ?> </div>
				<div class="controls"><?php echo $form->getInput('refund_date'); ?></div>								
			</div>
			
		</fieldset>
	</div>
	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />

</form>