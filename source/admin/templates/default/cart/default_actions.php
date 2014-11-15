<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

//@PCTODO: mention all populated variables

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );


?>
<div
   
            data-ng-controller='pcngCartNextActionCtrl'
>
	 <div	
            class="modal hide fade pc-cart-next-action-modal"
            id="pc-cart-next-action-modal" 
            tabindex="-1" 
            role="dialog"
            aria-hidden="true"
           
        >
            <div class="modal-header pc-cart-next-action-modal-title">
                <h3 id="myModalLabel" class= "rb-icon-login">
                    <?php
                        echo JText::_('COM_PAYCART_CONFIRM');
                    ?>
                </h3>
            </div>

            <div class="modal-body pc-cart-next-action-modal-body" id="rbWindowBody">
            
            <form id="pc-cart-action-form" name="pc-cart-action-form" action="index.php?option=com_paycart&view=cart" method="post">	
	
				<div  class="row-fluid <?php echo ($cart->isApproved()) ? 'muted disabled' : '' ?>" >
					
					<div class="span1 text-center">
						<input 	name="action" 
								value="approve" <?php echo ($cart->isApproved()) ? 'disabled= "disabled"' : ''; ?> 
								data-ng-click="onActionSelection('approve');" type="radio" />
					</div>
					
					<div class="span8 ">
						<strong ><?php echo JText::_('COM_PAYCART_APPROVE'); ?></strong><br>
						<p><small ><?php echo JText::_('COM_PAYCART_APPROVE_DESCRIPTION'); ?></small></p>
					</div>
					
				</div>
				
				<div  class="row-fluid ">
					 
					<div class="span1 text-center">
						<input name="action" value="paid_by_anymean" data-ng-click="onActionSelection('paid_by_anymean');" type="radio">
					</div>
					
					<div class="span8 ">
					
						<strong ><?php echo JText::_('COM_PAYCART_PAYMENT_ACCEPTED_BY_MEAN'); ?></strong><br>
						<p><small ><?php echo JText::_('COM_PAYCART_PAYMENT_ACCEPTED_BY_MEANDESCRIPTION'); ?></small></p>
						
						
						<div class="control-group" data-ng-show=" 'paid_by_anymean' == selected ">
							<div class="control-label">
								<label for="paycart_form_cart_notes" id="paycart_form_cart_notes-lbl">
									<?php echo JText::_('COM_PAYCART_NOTES'); ?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>										
							<div class="controls">
								<textarea data-ng-class="('paid_by_anymean' == selected) ? 'required': '' " name="notes"></textarea>
							</div>
						</div>
					
						
					</div>
					
				</div>
				
				<div  class="row-fluid ">
					 
					<div class="span1 text-center">
						<input name="action" value="paid_by_transaction_id" data-ng-click="onActionSelection('paid_by_transaction_id');" type="radio">
					</div>
					
					<div class="span8 ">
					
						<strong ><?php echo JText::_('COM_PAYCART_PAYMENT_ACCEPTED_BY_REMOTELY'); ?></strong><br>
						<p>
							<small ><?php echo JText::_('COM_PAYCART_PAYMENT_ACCEPTED_BY_REMOTELY_DESCRIPTION'); ?></small>
						</p>
						
						<div class="control-group" data-ng-show=" 'paid_by_transaction_id' == selected ">
							<div class="control-label">
								<label for="paycart_form_cart_notes" id="paycart_form_cart_notes-lbl">
									<?php echo JText::_('COM_PAYCART_GATEWAYTRANSACTION_ID'); ?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>										
							<div class="controls">
								<input 	data-ng-class="('paid_by_transaction_id' == selected) ? 'required': '' " 
										name="gatewaytransaction_id" type="text" />
							</div>
						</div>
						
					</div>
					
				</div>
				
				<input type="hidden" name="cart_id" value="<?php echo $record_id; ?>"  />
				<input type="hidden" name="task"	value="{{ task }}"  />



				</form>
            </div>
            
            <div class="modal-footer pc-cart-next-action-modal-footer">
             
                <button type="button" class="btn" data-ng-click="onActionExecute();">
                    <?php
                        echo JText::_('COM_PAYCART_OK');
                    ?>
                </button>
                
                <button type="button" class="btn " data-dismiss="modal" aria-hidden="true">
                    <?php
                        echo JText::_('COM_PAYCART_BUTTON_CANCEL');
                    ?>
                </button>
                
            </div>
            </div>
        </div>

<script>
	(function($)
		{
			$(document).ready(function($) 
					{
						//paycart.formvalidator.initialize('#pc-cart-action-form');
					});
		
				
		})(paycart.jQuery)
</script>



