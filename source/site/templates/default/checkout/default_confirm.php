<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

	// Promotion msg
	$promotion_message = '';	
 	foreach ($promotion_usage as $usages ) :
 		foreach ($usages as $usage) :
 			if ($usage->rule_type == Paycart::PROCESSOR_TYPE_DISCOUNTRULE)
 				$promotion_message .= $usage->message;
 		endforeach;
  	endforeach;
  	
  	// Duties msg
	$duties_message = '';
 	foreach ($duties_usage as $usage ) :
 		foreach ($usages as $usage) :
 			if ($usage->rule_type == Paycart::PROCESSOR_TYPE_TAXRULE)
 				$duties_message .= $usage->message;
 		endforeach;
  	endforeach;
  	
?>


<div class='pc-checkout-wrapper clearfix row-fluid'>
	
	 <div class="pc-checkout-confirm" id="accordion-parent" >
<!--	 -->
	 	<div class="span8">
	 		
	 		<!-- Email Block		-->
	 		<div class="row-fluid">
				<div class="accordion-group">
			 		<div class="accordion-heading">
			 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-email">
			 				<?php echo JText::_("COM_PAYCART_EMAIL_TITLE"); ?>
			 			</a>
			 		</div>
			 		
			 		<div id="pc-confirm-email" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
			 				<p><?php echo $buyer->email; ?></p>
			 				
			 				<?php if ($cart->is_guestcheckout) :?>
			 				<div>
			 					<a href="#" onclick="return paycart.checkout.confirm.edit.email();"> <i class="fa fa-edit"></i> <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
			 				</div>
			 				<?php endif; ?>
			 			</div>
			 		</div>
			 	</div>
		 	</div>
			
			<!-- Addresses Block		 	-->
		 	<div class="row-fluid">
		 	
		 	<!--	Billing Address	 		-->
		 		<div class="span6">				
		 			<div class="accordion-group">
		 				<div class="accordion-heading">
		 					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-billing-address">
		 						<?php echo JText::_("COM_PAYCART_BILLING_ADDRESS_TITLE"); ?>
		 					</a>
		 				</div>
		 		
				 		<div id="pc-confirm-billing-address" class="accordion-body in collapse"">
				 			<div class="accordion-inner">
				 				<?php
				 					$layout = new JLayoutFile('paycart_buyeraddress_display', PAYCART_LAYOUTS_PATH);
									echo $layout->render($billing_address); 
				 				?>
				 											
								<div>
			 						<a href="#" onclick="return paycart.checkout.confirm.edit.address();"> <i class="fa fa-edit"></i>  <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
			 					</div>
			 					
				 			</div>
				 		</div>
		 			</div>
		 		</div>
		 		
		 		<!-- Shipping Address 		-->
		 		<div class="span6">
		 			<div class="accordion-group">
		 				<div class="accordion-heading">
		 					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-shipping-address">
		 						<?php echo JText::_("COM_PAYCART_SHIPPING_ADDRESS_TITLE"); ?>
		 					</a>
		 				</div>
				 		<div id="pc-confirm-shipping-address" class="accordion-body in collapse"">
				 			<div class="accordion-inner">
				 				<?php
				 					if ( @$billing_to_shipping ) {
				 						echo '<i class="fa fa-clipboard"></i> ' . JText::_('Same as a Billing Address');
				 					} else {
				 						$layout = new JLayoutFile('paycart_buyeraddress_display', PAYCART_LAYOUTS_PATH);
										echo $layout->render($shipping_address);
				 					} 
				 				?>	
								
								<div>
			 						<a href="#"  onclick="return paycart.checkout.confirm.edit.address();"> <i class="fa fa-edit"></i>  <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
			 					</div>
								
				 			</div>
				 		</div>
		 			</div>
		 		</div>
		 	</div>
			
			<!-- Shipping Options		 	-->
		 	<div class="row-fluid">
		 		<div class="accordion-group">
		 			<div class="accordion-heading">
		 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-shipping-option">
	 						Shipping Option
	 					</a>
	 				</div>
	 		
			 		<div id="pc-confirm-shipping-option" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
<!--			 				<div class='pc-checkout-shipping-list'>-->
<!--				 				<select>-->
<!--				 					<option value='express'>Express (1 Business Day)</option>-->
<!--				 				</select>-->
<!--				 			</div>-->
<!--				 			<div class='pc-checkout-shipping-notes'>-->
<!--				 				<b>Price-$9.00</b><br/>-->
<!--				 				Estimated Delivery Date : 25Jun2014 <br />-->
<!--				 				<span class='text-error'>Your oreder may arrivein multiple package</span>				 				-->
<!--				 			</div> -->
								Coming Soon
			 			</div>
			 		</div>
	 			</div>
		 	</div>
		 	
			<!-- Product Summary		 	-->
		 	<div class="row-fluid">
		 		<div class="accordion-group">
		 			<div class="accordion-heading">
		 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-products-summary">
	 						<?php echo JText::_('Product Summary'); ?>
	 					</a>
	 				</div>
	 		
			 		<div id="pc-confirm-products-summary" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
			 			<?php 
			 				foreach ($product_particular as $particular) :
			 				?>
			 				<div class="row-fluid">
								
								<!-- Product Image			 				-->
			 					<div class="span3">
			 					 	<img class="img-polaroid " 
			 					 		 src="<?php echo @$product_media[$particular->particular_id]['thumbnail'];?>" 
			 					 		/>
			 					</div>
			 					
			 					<!-- Product Details			 				-->
				 				<div class="span5">
				 						<div>
				 							<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=product&product_id='.$particular->particular_id);?>" >
				 								<?php echo $particular->title; ?>
				 							</a>
				 						</div>
				 						
				 						<div>
				 							<?php echo JText::_('Unit-Price').':'.$formatter->amount($particular->unit_price, true, $currency_id);  ?>
				 						</div>
				 						
				 						<?php if ($particular->tax) : 
				 								echo '<div>'.JText::_('Tax').':'.$formatter->amount($particular->tax, true, $currency_id).'</div>';
				 							 endif;  
				 						?>
				 						<?php if ($particular->discount) : 
				 								echo '<div>'.JText::_('Discount').':'.$formatter->amount($particular->discount, true, $currency_id).'</div>';
				 							 endif;  
				 						?>
				 				</div>
				 				
				 				<!-- Product Price and quantity			 				-->
				 				<div class="span4">
				 					
				 					<div>
					 					<?php echo JText::_('Quantity'); ?> : 
					 					<input 
					 							type="number"   
					 							class="input-mini" 
					 							id='pc-checkout-quantity-<?php echo $particular->particular_id; ?>'
					 							value="<?php echo $particular->quantity; ?>"
					 							min="<?php echo isset($particular->min_quantity) ? $particular->min_quantity : 1; ?>" 	
					 						/>
					 						<a 	href="javascript:void(0);" 
					 							onClick="paycart.checkout.confirm.onChangeProductQuantity(<?php echo $particular->particular_id; ?>, this.value)"
					 							>
					 								<i class="fa fa-refresh"></i>
					 						</a>
					 					
					 				</div>
					 				
					 				<div>
					 					<h4><?php echo JText::_('Price'); ?> : <?php echo $formatter->amount($particular->total, true, $currency_id); ?></h4>
					 				</div>
													 				
				 				</div>

				 			</div>

				 			<div class="row-fluid">
				 				
				 				<div class="pull-right">
				 					<a 	class="muted" href="javascript:void(0)" 
										onClick="paycart.checkout.confirm.onRemoveProduct(<?php echo $particular->particular_id;?>)"><i class="fa fa-trash-o fa-lg">&nbsp;</i>
									</a>
								</div>
								
				 			</div>

				 			<hr />
			 			<?php 
			 				endforeach;
			 			?>
			 				<div class="row-fluid">
				 				<span class="pull-right"><?php echo JText::_("Product's Total") ?> : <?php echo $formatter->amount($product_total, true, $currency_id); ?> </span>
				 			</div>
				 			
			 			</div>
			 		</div>
	 			</div>
		 	</div>
		 	
	 	
	 		
	 	</div>
	 	
		
	 	<div class="span4">
	 		
	 		<!-- Order Summary	 	-->
	 		<div clss="row-fluid">
	 			<div class="accordion-group">
			 		<div class="accordion-heading">
			 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-order-summary">
			 				<?php echo JText::_('Order Summary'); ?>
			 			</a>
			 		</div>
			 		
			 		<div id="pc-confirm-order-summary" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
			 				<table class="table">
			 					<thead>
			 						<tr>
			 							<td><?php echo JText::_('Quantity'); ?></td>
			 							<td><?php echo $product_quantity;?></td>
			 						</tr>
			 					</thead>
			 					
			 					<tbody>
			 						<tr>
			 							<td><?php echo JText::_('Cart Total'); ?></td>
			 							<td><?php echo $formatter->amount($product_total, true, $currency_id); ?></td>
			 						</tr>
			 						
			 						<tr>
			 							<td><?php echo JText::_('Shipping'); ?></td>
			 							<td><?php echo $formatter->amount($shipping_total, true, $currency_id); ?></td>
			 						</tr>
			 						
			 						<tr>
										<td>
											<?php
			 									if(!empty($duties_message) ):
			 								?>
												  
												  <a 	href="javascript:void(0)"  
												  		class="pc-popover" 
												  		title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
												  		data-content="<?php echo $duties_message;?>" data-trigger="hover">
												  		
												 	 	<i class="fa fa-info-circle"></i>
												  </a>
												  
											<?php endif;?>
			 							<?php echo JText::_('Tax'); ?></td>
			 							<td><?php echo $formatter->amount($duties_total, true, $currency_id); ?></td>
			 						</tr>
			 						
			 						<tr>
			 							<td>
			 								<?php
			 										if(!empty($promotion_message) ):
			 								?>
												  
												  <a 	href="javascript:void(0)"  
												  		class="pc-popover" 
												  		title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
												  		data-content="<?php echo $promotion_message;?>" data-trigger="hover">
												  		
												 	 	<i class="fa fa-info-circle"></i>
												  </a>
												  
											<?php endif;?>
			 								<?php echo PaycartFactory::getHelper('buyer')->getClientIP().JText::_('Discount'); ?>
			 							</td>
			 							<td><?php echo $formatter->amount($promotion_total, true, $currency_id); ?></td>
			 						</tr>
			 						
			 						<tr>
			 							<td><?php echo JText::_('TOTAL'); ?></td>
			 							<td><?php echo $formatter->amount(($product_total+$promotion_total+$duties_total), true, $currency_id); ?> </td>
			 						</tr>
			 					</tbody>
			 				</table>	
			 			</div>
			 		</div>
			 	</div>
			 </div>
			 
			<!-- Cart Discount		 -->
			 <div class="row-fluid">
			 	<p><?php echo JText::_('COM_PAYCART_PROMOTION_CODE_LABEL')?></p>
			 	<div class="input-append" >
				  <input class="span9" id="paycart-promotion-code-input-id" type="text">
				  <button class="btn" type="button" onclick="paycart.checkout.confirm.onApplyPromotionCode()"><?php echo JText::_('COM_PAYCART_PROMOTION_CODE_APPLY')?></button>
				</div>
			 </div>
			 
			 <!-- Process ne		 -->
			 <div class="row-fluid">
			 	<button type="button" class="btn btn-primary btn-block btn-large" onClick="return paycart.checkout.confirm.process(); " ><?php echo JText::_('Proceed to Payment'); ?></button>
			 </div>
			 
			 <input	type="hidden"	name='step_name' value='confirm' />
		 	
	 	</div>

	 </div>	 
</div>

<script>
(function($){

			paycart.checkout.step.change('<?php echo json_encode($available_steps) ?>');				
			$(".pc-popover").popover();
		})(paycart.jQuery);
</script>
<?php

