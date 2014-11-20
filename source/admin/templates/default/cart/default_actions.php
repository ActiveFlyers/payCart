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
	<div data-ng-controller='pcngCartNextActionCtrl'
		id="pcngCartNextActionCtrl"
	>
	
	 <div	
            class="modal hide fade pc-cart-next-action-modal"
            id="pc-cart-next-action-modal" 
            tabindex="-1" 
            role="dialog"
            aria-hidden="true"
        >
            <div class="modal-header pc-cart-next-action-modal-title">
                <h3 id="myModalLabel">
                    <?php  echo JText::_('COM_PAYCART_ADMIN_CART_ACTIONS'); ?>
                    &nbsp;<span class=" fa fa-forward"></span>
                </h3>
            </div>

            <div class="modal-body pc-cart-next-action-modal-body" id="rbWindowBody">
            
            <form id="pc-cart-action-form" name="pc-cart-action-form" action="index.php?option=com_paycart&view=cart" method="post">	
	
<!-- Cart Approve task	-->
				<div 	class="row-fluid <?php echo ($cart->isApproved()) ? 'muted disabled' : '' ?>"
						data-ng-show=" 'approve' == selected  || !selected "  
					>
					
					<div class="span1 text-center">
						<input 	name="action" 
								value="approve" <?php echo ($cart->isApproved()) ? 'disabled= "disabled"' : ''; ?> 
								data-ng-click="onActionSelection('approve');" type="radio" />
					</div>
					
					<div class="span10 ">
						<strong ><?php echo JText::_('COM_PAYCART_APPROVE'); ?></strong><br>
						<p><small ><?php echo JText::_('COM_PAYCART_ADMIN_APPROVE_SHORT_DESCRIPTION'); ?></small></p>
						
						<div data-ng-show=" 'approve' == selected ">
							<?php echo JText::_('COM_PAYCART_ADMIN_APPROVE_DESCRIPTION'); ?>
						</div>
					</div>
				</div>

<!-- Cart pay Task (By any medium) -->
				<div	class="row-fluid "
						data-ng-show=" 'pay_by_anymean' == selected  || !selected "
				>
					 
					<div class="span1 text-center">
						<input name="action" value="pay_by_anymean" data-ng-click="onActionSelection('pay_by_anymean');" type="radio">
					</div>
					
					<div class="span10 ">
					
						<strong ><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_ACCEPTED_BY_ANY_MEAN'); ?></strong><br>
						<p><small ><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_ACCEPTED_BY_ANY_MEAN_SHORT_DESCRIPTION'); ?></small></p>
						<div data-ng-show=" 'pay_by_anymean' == selected ">
							<div><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_ACCEPTED_BY_ANY_MEAN_DESCRIPTION'); ?></div>
							<div class="control-group">
								<div class="control-label">
									<label for="paycart_form_cart_notes" id="paycart_form_cart_notes-lbl">
										<strong><?php echo JText::_('COM_PAYCART_NOTES'); ?></strong>
										<span class="star">&nbsp;*</span>
									</label>
								</div>		
								<div class="controls">
									<textarea id="pc-cart-action-note" name="notes"  
											required="('pay_by_anymean' == selected) ? 'required': false " 
											onblur="paycart.formvalidator.initialize('#pc-cart-action-form');" 
											></textarea>
									
									<div class="pc-error" for="pc-cart-action-note">
										<?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_NOTE');?>
									</div>
								</div>
							</div>
						</div>
					</div>					
				</div>
				
<!-- Cart pay Task (Payment remotely accepted)	-->
				<div  	class="row-fluid "
						data-ng-show=" 'pay_by_transaction_id' == selected  || !selected "
				>
					<div class="span1 text-center">
						<input name="action" value="pay_by_transaction_id" data-ng-click="onActionSelection('pay_by_transaction_id');" type="radio"
							onblur="paycart.formvalidator.initialize('#pc-cart-action-form');" 
						/>
					</div>
					
					<div class="span10 ">
					
						<strong ><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_REMOTELY_ACCEPTED'); ?></strong><br>
						<p><small ><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_REMOTELY_ACCEPTED_SHORT_DESCRIPTION'); ?></small></p>
						
						<div data-ng-show=" 'pay_by_transaction_id' == selected ">
							<div><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENT_REMOTELY_ACCEPTED_DESCRIPTION'); ?></div>						
							<br>
							<div class="control-group" data-ng-show=" 'pay_by_transaction_id' == selected ">
								<div class="control-label">
									<label for="paycart_form_cart_notes" id="paycart_form_cart_notes-lbl">
										<strong><?php echo JText::_('COM_PAYCART_GATEWAYTRANSACTION_ID'); ?></strong>
										<span class="star">&nbsp;*</span>
									</label>
								</div>
								<div class="controls">
									<input 	required="('pay_by_transaction_id' == selected) ? 'required': false " 
											name="gatewaytransaction_id" type="text"
											id="pc-cart-action-gateway-txnid" 
									/>
									<div class="pc-error" for="pc-cart-action-gateway-txnid">
										<?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_GATEWAYTRANSACTION_ID');?>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<input type="hidden" name="cart_id" value="<?php echo $record_id; ?>"  />
				<input type="hidden" name="task"	value="{{ task }}"  />

			</form>
           </div>
            
            <div class="modal-footer pc-cart-next-action-modal-footer">
            	
            	<button type="button" class="btn btn-primary pull-left" 
            			data-ng-click="onActionSelection(null)"
            			data-ng-show=" selected " 
            	><?php echo JText::_('COM_PAYCART_BACK'); ?>
                </button>
              	<button type="button" class="btn btn-success pull-right" data-ng-show=" selected" data-ng-click="onActionExecute();">
	            	{{task_value}}
	            </button>
                <button type="button" class="btn btn-primary " data-dismiss="modal" aria-hidden="true"  data-ng-show="!selected">
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
			$(document).ready(function() 
					{
						paycart.formvalidator.initialize('#pc-cart-action-form');
					});

			// on acttion model hide, unchecked radio button
		    $('#pc-cart-next-action-modal').on('hidden', function () {

		    	var scope = angular.element(document.getElementById('pcngCartNextActionCtrl')).scope();
		    	scope.onActionSelection(null);
		    	scope.$apply();
		    });
						
		})(paycart.jQuery)
</script>



