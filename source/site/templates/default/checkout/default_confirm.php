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
$currency_html = '<i class="fa fa-usd"></i>';
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
			 				<p><?php echo $buyer->get('email'); ?></p>
			 				
			 				<?php if ($cart->is_guestcheckout) :?>
			 				<div>
			 					<a href="#"> <i class="fa fa-edit"></i> <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
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
			 						<a href="#" onclick="paycart.checkout.confirm.edit.billing_address(event);"> <i class="fa fa-edit"></i>  <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
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
				 					$layout = new JLayoutFile('paycart_buyeraddress_display', PAYCART_LAYOUTS_PATH);
									echo $layout->render($shipping_address); 
				 				?>	
								
								<div>
			 						<a href="#"  onclick="paycart.checkout.confirm.edit.shipping_address(event);"> <i class="fa fa-edit"></i>  <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
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
			 				<div class='pc-checkout-shipping-list'>
				 				<select>
				 					<option value='express'>Express (1 Business Day)</option>
				 				</select>
				 			</div>
				 			<div class='pc-checkout-shipping-notes'>
				 				<b>Price-$9.00</b><br/>
				 				Estimated Delivery Date : 25Jun2014 <br />
				 				<span class='text-error'>Your oreder may arrivein multiple package</span>				 				
				 			</div> 

			 			</div>
			 		</div>
	 			</div>
		 	</div>
		 	
			<!-- Product Summary		 	-->
		 	<div class="row-fluid">
		 		<div class="accordion-group">
		 			<div class="accordion-heading">
		 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-products-summary">
	 						Product Summary
	 					</a>
	 				</div>
	 		
			 		<div id="pc-confirm-products-summary" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
			 				<div class="row-fluid">
				 				<div class="span4">Mobile (Nokia X) 10(items) </div>
				 				<div class="span3">Quantity : <input type="number" class="input-mini" /></div>  
				 				<div class="span3">Price : <?php echo $currency_html; ?>950</div>
				 				<div class="span1"><a href='#'>Remove</a></div>
				 			</div>
				 			<hr />
				 			<div class="row-fluid">
				 				<div class="span4">Mobile (Nokia X) 10(items) </div>
				 				<div class="span3">Quantity : <input type="number" class="input-mini" /></div>  
				 				<div class="span3">Price : <?php echo $currency_html; ?>950</div>
				 				<div class="span1"><a href='#'>Remove</a></div>
				 			</div>
				 			<hr />
				 			
				 			<div class="row-fluid">
				 				<div class="span4">Mobile (Nokia X) 10(items) </div>
				 				<div class="span3">Quantity : <input type="number" class="input-mini" /></div>  
				 				<div class="span3">Price : <?php echo $currency_html; ?>950</div>
				 				<div class="span1"><a href='#'>Remove</a></div>
				 			</div>
				 			<hr />
				 			
				 			<div class="row-fluid">
				 				<span class="pull-right"> Product's Total : <?php echo $currency_html?>2850 </span>
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
			 				Order Summary
			 			</a>
			 		</div>
			 		
			 		<div id="pc-confirm-order-summary" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
			 				<table class="table">
			 					<thead>
			 						<tr>
			 							<td>Quantity</td>
			 							<td>10(items)</td>
			 						</tr>
			 					</thead>
			 					
			 					<tbody>
			 						<tr><td>Cart Total</td>
			 							<td><?php echo $currency_html; ?>950</td>
			 						</tr>
			 						<tr><td>Shipping</td>
			 							<td><?php echo $currency_html; ?>70</td>
			 						</tr>
			 						<tr><td>Tax</td>
			 							<td><?php echo $currency_html; ?>30</td>
			 						</tr>
			 						<tr><td>Discount</td>
			 							<td>-<?php echo $currency_html; ?>50</td>
			 						</tr>
			 						<tr><td>TOTAL</td>
			 							<td><?php echo $currency_html; ?> 100</td>
			 						</tr>
			 					</tbody>
			 				</table>	
			 			</div>
			 		</div>
			 	</div>
			 </div>
			 
			<!-- Cart Discount		 -->
			 <div class="row-fluid">
			 	<p>Discount Code</p>
			 	<div class="input-append" >
				  <input class="span9" id="appendedInputButton" type="text">
				  <button class="btn" type="button">Apply</button>
				</div>
			 </div>
			 
			 <!-- Process ne		 -->
			 <div class="row-fluid">
			 	<button type="button" class="btn btn-primary btn-block btn-large" >Proceed to Payment</button>
			 </div>
		 	
	 	</div>

	 </div>	 
</div>

<script>
(function($){
			paycart.checkout.confirm = 
			{
				edit : 
				{	//@PCFIXME :: clean event code
					billing_address : function(event)
					{
						try {
							var data = {'back_to' : 'billing_address'}
							paycart.checkout.goback(data);
						}catch (e) {
							paycart.notification.console.log({'exception_was': e});	
						}

						event.preventDefault();
					},

					shipping_address : function(event)
					{
						try {
							var data = {'back_to' : 'shipping_address'}
							paycart.checkout.goback(data);
						}catch (e) {
							paycart.notification.console.log({'exception_was': e});	
						}

						event.preventDefault();
					}
				}
				
			};

			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
</script>
<?php

