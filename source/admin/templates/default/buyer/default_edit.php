<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal , Manish TRivedi, rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

PaycartHtml::_('behavior.formvalidation');
?>
<div class="pc-buyer-wrapper clearfix">
<div class="pc-buyer row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=buyer'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">

	<?php echo PaycartHtml::_('bootstrap.startTabSet', 'buyer', array('active' => 'details')); ?>
	<!--	 Account Details Tab		-->
	<?php echo PaycartHtml::_('bootstrap.addTab', 'buyer', 'details', Rb_Text::_('COM_PAYCART_ADMIN_BUYER', true)); ?>
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">
			<div class="row-fluid">
				<div class="span3">
					<h2><?php echo JText::_('COM_PAYCART_ADMIN_BUYER_DETAILS_HEADER');?></h2>
					<div>
						<?php echo JText::_('COM_PAYCART_ADMIN_BUYER_DETAILS_HEADER_MSG');?>
					</div>
				</div>
				<div class="span9">
					<fieldset class="form">
						<div class="row-fluid">			
							<div class="span6">
								<?php $field = $form->getField('buyer_id') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<?php $field = $form->getField('realname')?>
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
							</div>
						</div>
					
						<div class="row-fluid">			
							<div class="span6">
								<?php $field = $form->getField('username') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<?php $field = $form->getField('email')?>
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
							</div>
						</div>
						
						<div class="row-fluid">	
							<div class="span6">
								<?php $field = $form->getField('usertype') ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls"><?php echo $field->input; ?></div>								
								</div>
							</div>
							<div class="span6"></div>
						</div>
								
					</fieldset>
				</div>
			</div>
	
		<input type="hidden" name="task" value="save" />
		<input type='hidden' name='id' value='<?php echo $record_id;?>' />

	</form>
	<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
			
<!--	 Address Details Tab		-->
	<?php echo PaycartHtml::_('bootstrap.addTab', 'buyer', 'address', Rb_Text::_('COM_PAYCART_ADDRESS', true)); ?>
		
	<?php echo $this->loadTemplate('address'); ?>

	<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
	
</div>
</div>
</div>
<?php 