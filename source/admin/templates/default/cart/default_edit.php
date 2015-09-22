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
echo $this->loadTemplate('edit_ng');
echo Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);


?>
<style>
	/* To load popup in proper position */
	#paycart div.modal {
	    margin-left: -40%;
	}
</style>

<div class="pc-cart-wrapper clearfix">
<div class="pc-cart row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cart'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10" data-ng-app="pcngCartApp">
	
	<?php echo PaycartHtml::_('bootstrap.startTabSet', 'cart', array('active' => 'basic')); ?>
	<!--	 Account Details Tab		-->
	<?php echo PaycartHtml::_('bootstrap.addTab', 'cart', 'basic', JText::_('COM_PAYCART_ADMIN_BASIC', true)); ?>
		<?php if(in_array($cart->getStatus(), array(Paycart::STATUS_CART_PAID, Paycart::STATUS_CART_CANCELLED)) && !$cart->isRefunded()):?>
		<?php $url = 'index.php?option=com_paycart&view=cart&task=cancelCart&cart_id='.$record_id;?>
			<div class="row-fluid">
			<div><a href="#pc-cancel-confirmation" class="pull-right btn btn-info muted" data-toggle="modal" onClick="paycart.ajax.go('<?php echo $url; ?>'); return false;"> <?php echo JText::_("COM_PAYCART_CANCEL");?></a></div>
								<div id="pc-cancel-confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:500px; margin-left:-400px;" data-backdrop="static" data-keyboard="false">
									&nbsp;
		</div></div>
		<?php endif;?>
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate" enctype="multipart/form-data">

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
							
							<div>
							
							<?php if ($cart->islocked() && !$cart->isPaid()) :?>
							<span>
								<a href="#pc-cart-next-action-modal" role="button" class="btn btn-success" data-toggle="modal">
									<?php echo JText::_('COM_PAYCART_ADMIN_CART_ACTIONS');?>
									<span class=" fa fa-forward"></span>
								</a>
							</span>
							<?php endif; ?>

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
									<?php 
											$billingAddress = $cart->getBillingAddress(true)->toArray() ;

											if (count(array_filter($billingAddress))) :
												$billingAddress = (object) $billingAddress;
												echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $billingAddress, PAYCART_LAYOUTS_PATH);
										  	else : 
										  		echo JText::_('COM_PAYCART_BUYERADDRESS_NOT_EXIT');
										  endif;
									?> 
								</div>								
							</div>
						</div>
						<div class="span6">
							<?php if($isShippableProductExist):?>
								<div class="control-group">
									<?php $field = $form->getField('shipping_address_id')?>
									<div class="control-label"><?php echo $field->label; ?> </div>
									<div class="controls">&nbsp;
										<?php $shippingAddress = $cart->getShippingAddress(true)->toArray() ;
										
												if (count(array_filter($shippingAddress))) :
													$billingAddress = (object) $shippingAddress;
													echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $shippingAddress, PAYCART_LAYOUTS_PATH);
											  		
											  	else : 
											  		echo JText::_('COM_PAYCART_BUYERADDRESS_NOT_EXIT');
											  endif;
										
										?> 
									</div>								
								</div>
							<?php endif;?>
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
	
	<?php if($cart->isApproved() && $isShippableProductExist):?>
		<!--	 Shipment Details Tab		-->
		<?php echo PaycartHtml::_('bootstrap.addTab', 'cart', 'shipment', JText::_('COM_PAYCART_ADMIN_SHIPMENTS', true)); ?>
			
		<?php echo $this->loadTemplate('shipments'); ?>
	
		<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
    <?php endif;?>   
    
    <?php if(!empty($transactions)):?>
    	<!--	 Transaction Details Tab		-->
    	<?php echo PaycartHtml::_('bootstrap.addTab', 'cart', 'transaction', JText::_('COM_PAYCART_ADMIN_TRANSACTIONS', true)); ?>
    
    	<?php echo $this->loadTemplate('transactions');?>	
    	
    	<?php echo PaycartHtml::_('bootstrap.endTab'); ?>
    <?php endif;?>
    
    <?php 
    
    if (!$cart->isPaid()) :
		echo $this->loadTemplate('actions');
	  endif;
	?>

</div>
</div>
</div>
<?php 