<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal , rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

PaycartHtml::_('behavior.formvalidation');
?>

<div class="pc-cart-wrapper clearfix">
<div class="pc-cart row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cart'); 
                        
                        echo $this->loadTemplate('edit_js');

	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">
	
	<?php echo PaycartHtml::_('bootstrap.startTabSet', 'cart', array('active' => 'basic')); ?>
	<!--	 Account Details Tab		-->
	<?php echo PaycartHtml::_('bootstrap.addTab', 'cart', 'basic', JText::_('COM_PAYCART_ADMIN_BASIC', true)); ?>
	
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">

		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_CART_DETAILS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_CART_DETAILS_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<fieldset class="form">
					<div class="row-fluid">								
						<div class="span6">
							<?php $field = $form->getField('cart_id') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('currency')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
					
					<div class="row-fluid">								
						<div class="span6">
							<?php $field = $form->getField('status') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
						<div class="span6">
						</div>
					</div>
					
					<!--<div class="row-fluid">
						<div class="span6">
							<?php $field = $form->getField('reversal_for') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
						
						<div class="span6">
						</div>
					</div>
				--></fieldset>
			</div>
		</div>
		
		<hr/>
		
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
								<?php $field = $form->getField('ip_address')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
					
					<div class="row-fluid">								
						<div class="span6">
							<?php $field = $form->getField('billing_address_id') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls">&nbsp;
									<?php $billingAddress = (object)$cart->getBillingAddress(true)->toArray() ;?>
									<?php $layout = new JLayoutFile('paycart_buyeraddress_display', PAYCART_LAYOUTS_PATH);
										  echo $layout->render($billingAddress);
									?> 
								</div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('shipping_address_id')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls">&nbsp;
									<?php $shippingAddress = (object)$cart->getShippingAddress(true)->toArray() ;?>
									<?php $layout = new JLayoutFile('paycart_buyeraddress_display', PAYCART_LAYOUTS_PATH);
										  echo $layout->render($shippingAddress);
									?> 
								</div>								
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	
		<hr/>
		
		<div class="row-fluid">
			<div class="span3">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_DATE_DETAILS_HEADER');?></h2>
				<div>
					<?php echo JText::_('COM_PAYCART_ADMIN_DATE_DETAILS_HEADER_MSG');?>
				</div>
			</div>
			<div class="span9">
				<fieldset class="form">
					<div class="row-fluid">								
						<div class="span6">
							<?php $field = $form->getField('created_date') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('modified_date')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
					
					<div class="row-fluid">								
						<div class="span6">
							<?php $field = $form->getField('locked_date') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('paid_date')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
					
					<div class="row-fluid">								
						
                                                <div class="span6">
							<?php $field = $form->getField('approved_date') ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
                                            
						<div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('delivered_date')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>
                                    
                    <div class="row-fluid">								
						
                        <div class="span6">
							<div class="control-group">
								<?php $field = $form->getField('cancelled_date')?>
								<div class="control-label"><?php echo $field->label; ?> </div>
								<div class="controls"><?php echo $field->input; ?></div>								
							</div>
						</div>
					</div>                               
				</fieldset>
			</div>
		</div>
	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />

</form>

	<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
			
	<!--	 Invoice Details Tab		-->
	<?php echo PaycartHtml::_('bootstrap.addTab', 'cart', 'advance', Rb_Text::_('COM_PAYCART_ADMIN_ADVANCE', true)); ?>
		
	<?php echo $this->loadTemplate('particulars'); ?>

	<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
	
	<?php  // PCTODO: show shipment tab only if cart status is not drafted?>
	
	<!--	 Shipment Details Tab		-->
	<?php echo PaycartHtml::_('bootstrap.addTab', 'cart', 'shipment', Rb_Text::_('COM_PAYCART_ADMIN_SHIPMENTS', true)); ?>
		
	<?php echo $this->loadTemplate('shipments'); ?>

	<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
        
<!-- Confirm Model        -->
        <div	
            class="modal hide fade pc-confimbox"
            id="pc-confimbox-modal" 
            tabindex="-1" 
            role="dialog"
            aria-labelledby="Login-ModalLabel"
            aria-hidden="true"
        >
            <div class="modal-header pc-confimbox-title">
                <h3 id="myModalLabel" class= "rb-icon-login">
                    <?php
                        echo JText::_('COM_PAYCART_CONFIRM');
                    ?>
                </h3>
            </div>

            <div class="modal-body pc-confimbox-body">
                
            </div>
            
            <div class="modal-footer pc-confimbox-footer">
             
                <button type="button" class="btn pc-confimbox-ok">
                    <?php
                        echo JText::_('COM_PAYCART_OK');
                    ?>
                </button>
                
                <button type="button" class="btn pc-confimbox-close" data-dismiss="modal" aria-hidden="true">
                    <?php
                        echo JText::_('COM_PAYCART_BUTTON_CANCEL');
                    ?>
                </button>
                
                
            </div>
            
            
        </div>
</div>
</div>
</div>