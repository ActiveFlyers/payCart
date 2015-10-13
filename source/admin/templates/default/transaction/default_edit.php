<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Paycart
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<div class="pc-transaction row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=transaction'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
     
<fieldset class="form-horizontal">
	<div class="row-fluid">
	
	<?php 
	if(!$isRefundDone):
		 if($refundable): ?>
						<a href="#" onclick="paycart.admin.transaction.refund.confirm('<?php echo $transaction['invoice_id']?>')" class="btn btn-success pull-right"><?php echo JText::_('COM_PAYCART_ADMIN_INVOICE_REFUND');?></a>			
					
		<?php  else :?>
			    <a href="#rbWindowContent" role="button" class="btn btn-success pull-right" data-toggle="modal"><?php  echo JText::_('COM_PAYCART_ADMIN_INVOICE_REFUND');?></a>
			   
			    <div id="rbWindowContent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 500px; margin-left: -400px; display: block;">
			    <?php echo Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);?>
			     <div id="rbWindowTitle">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				        <h3  >Refund not supported</h3>
				      </div>
			     </div>
			      
			      <div id="rbWindowBody">
				      <div class="modal-body" >
				        <p><?php echo JText::_("COM_PAYCART_ADMIN_INVOICE_REFUND_NOT_SUPPORTED_MESSAGE");?> 
				      </div>
			      </div>
			      <div id="rbWindowFooter">
			        <div class="modal-footer" >
			        	<a href="#" class="btn btn-primary" onclick="paycart.admin.transaction.refund.request('<?php echo $transaction['invoice_id']?>')"><?php echo JText::_("COM_PAYCART_ADMIN_INVOICE_REFUND");?></a>
					    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>				    
					</div>
				 </div>
			    </div>
		<?php endif;?>
		<?php endif;?>
	</div>
	
  	<div class="row-fluid"> 
	    	<div class="span6">
			     <h2><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_DETAILS' ); ?></h2><hr>
		      	<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_ID' ); ?></div>
					<div class="controls"><?php echo $transaction['transaction_id']; ?></div>	
				 </div>		
				 
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_CART_ID' ); ?> </div>
					<div class="controls"><?php echo PaycartHtml::link('index.php?option=com_paycart&view=cart&task=edit&id='.$cart->getId(),$cart->getId());?></div>
				
				</div>	
			
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_BUYER' ); ?> </div>
					<div class="controls"><?php echo PaycartHtml::link('index.php?option=com_paycart&view=buyer&task=edit&id='.$transaction['buyer_id'], $transaction['buyer_id'].'('.$buyer->getUsername().')'); ?></div>	
				 </div>	
								   
			    <div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_METHOD' ); ?> </div>
					<div class="controls"><?php echo $transaction['processor_type']; ?></div>	
			    </div>
	
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_PAYMENT_STATUS' ); ?> </div>
					<div class="controls"><?php echo JText::_($statusList[$transaction['payment_status']]);?></div>	
			    </div>	
	
	 			<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_GATEWAY_TXN_ID' ); ?> </div>
					<div class="controls"><?php echo $transaction['gateway_txn_id']; ?></div>	
			    </div>	
		
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_GATEWAY_PARENT_TXN' ); ?> </div>
					<div class="controls"><?php echo $transaction['gateway_parent_txn']; ?></div>	
				 </div>	
				 
				 <div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_GATEWAY_SUBSCRIPTION_ID' ); ?> </div>
					<div class="controls"><?php echo $transaction['gateway_subscr_id']; ?></div>	
				 </div>
				 
				 <div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_AMOUNT' ); ?> </div>
					<div class="controls"><?php echo $transaction['amount']; ?></div>	
				 </div>
	
				<div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_CREATED_DATE' ); ?> </div>
					<div class="controls"><?php echo $transaction['created_date']; ?></div>	
				 </div>
				 
				 <div class="control-group">
					<div class="control-label"><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_MESSAGE' ); ?> </div>
					<div class="controls"><?php echo JText::_($transaction['message']); ?></div>	
				 </div>
			</div>
			
			<div class="span6">
				<h2><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_PARAMS'); ?></h2><hr>
				<?php foreach ($transaction['params'] as $key => $value):?>
					 <div class="control-group">
						<div class="control-label"><?php echo $key;?> </div>
						<div class="controls pc-word-break" ><?php echo is_array($value)?print_r($value):$value; ?></div>	
					 </div>
				<?php endforeach;?>
			</div> 
		</div>
</fieldset>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="1" />
</form>
</div>
</div>
<?php 		
