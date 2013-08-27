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
			<legend><?php $fieldset = array_shift($fieldsets); echo JText::_($fieldset->label);	?></legend>
			
			<?php foreach ($form->getFieldset('basic-user') as $field):?>
			
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?> </div>
				<div class="controls"><?php echo $field->input; ?></div>								
			</div>
			
			<?php endforeach;?>

		</fieldset>
		
		<fieldset class="span6 form-horizontal">
			<legend>
				<span><?php echo JText::_('COM_PAYCART_CART_EXTRA_USER_FIELDSET_LABEL');?></span>
				<span class="pull-right"><button class="btn btn-success" type="button">Add New</button></span>
			</legend>
			
			<div class="clearfix">
			<?php if(!empty($addresses)):?>
			<div class="pc-blank-heading">
					<?php $count = 1; ?>
					<?php foreach ($addresses as $address):?>
						<div class="pc-user-address row-fluid">
							<div class="span6">
								<address>
									<strong><?php echo JText::_('COM_PAYCART_ADDRESS_ADDRESS_ADDRESS');?> <?php echo $count;?> :</strong><br/>
										<?php echo $address->address; ?>, 	<br />
									  	<?php echo $address->city; ?>,		<br />
									  	<?php echo $address->state; ?>,		<br />
									  	<?php echo $address->country; ?>,	<br />
									  	<?php echo $address->zipcode; ?>
								</address>
								
								<address>
									<strong><?php echo JText::_('COM_PAYCART_ADDRESS_ADDRESS_LOGITUDE');?></strong>
									<?php echo $address->longitude; ?>
								</address>
								
								<address>
									<strong><?php echo JText::_('COM_PAYCART_ADDRESS_ADDRESS_LATITUDE');?></strong>
									<?php echo $address->latitude; ?>
								</address>
							</div>
							
							<div class="span3">
								<?php echo empty($address->preferred) ? '&nbsp;' : 1; ?>	
							</div>
							
							<div class="span3">
								<span class="pull-right">Edit</span>
							</div>
						</div>
						
						<hr>
						
						<?php ++$count;?>
					<?php endforeach;?>
				</div>
				<?php else :?>
			
				<div class="pc-blank-heading">
					<p class="muted"><?php echo JText::_('COM_PAYCART_USER_ADDRESS_LIST_EMPTY'); ?></p>
				</div>
	
				<?php endif;?>
			</div>
		</fieldset>
	</div>
	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />

</form>