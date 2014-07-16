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
	 						<?php echo JText::_('Product Summary'); ?>
	 					</a>
	 				</div>
	 		
			 		<div id="pc-confirm-products-summary" class="accordion-body in collapse"">
			 			<div class="accordion-inner">
			 			<?php 
			 				$products_total 	= 0;
			 				$products_quantity	= 0;
			 				
			 				foreach ($product_particular as $particular) :
			 					$products_total 	+=	$particular->total;
			 					$products_quantity 	+=	$particular->quantity;
			 				?>
			 				<div class="row-fluid">
				 				<div class="span4"><?php echo $particular->title; ?> </div>
				 				<div class="span3"><?php echo JText::_('Quantity'); ?> : <input type="number" class="input-mini" value="<?php echo $particular->quantity; ?>" /></div>  
				 				<div class="span3"><?php echo JText::_('Price'); ?> : <?php echo $currency_html; ?><?php echo $particular->total; ?></div>
				 				<div class="span1"><a href='#'><?php echo JText::_('Remove') ?></a></div>
				 			</div>
				 			<hr />
			 			<?php 
			 				endforeach;
			 			?>
			 				<div class="row-fluid">
				 				<span class="pull-right"><?php echo JText::_("Product's Total") ?> : <?php echo $currency_html?><?php echo $products_total; ?> </span>
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
			 							<td><?php echo $products_quantity;?></td>
			 						</tr>
			 					</thead>
			 					
			 					<tbody>
			 						<tr>
			 							<td><?php echo JText::_('Cart Total'); ?></td>
			 							<td><?php echo $currency_html.$products_total; ?></td>
			 						</tr>
			 						<?php
			 								$shipping_total	=	0;
			 								
			 								foreach ($shipping_particular as $particular) :
			 									$shipping_total	+=	$particular->total;
			 								endforeach;
			 						 ?>
			 						 <tr>
			 							<td><?php echo JText::_('Shipping'); ?></td>
			 							<td><?php echo $currency_html.$shipping_total; ?></td>
			 						</tr>
			 						<?php
			 								$duties_total	=	0;
			 								
			 								foreach ($duties_particular as $particular) :
			 									$duties_total	+=	$particular->total;
			 								endforeach;
			 						 ?>
			 						<tr>
			 							<td><?php echo JText::_('Tax'); ?></td>
			 							<td><?php echo $currency_html.$duties_total; ?></td>
			 						</tr>
			 						<?php
			 								$promotion_total	=	0;
			 								
			 								foreach ($promotion_particular as $particular) :
			 									$promotion_total	+=	$particular->total;
			 								endforeach;
			 						 ?>
			 						<tr>
			 							<td><?php echo JText::_('Discount'); ?></td>
			 							<td><?php echo $currency_html.$promotion_total; ?></td>
			 						</tr>
			 						
			 						<tr>
			 							<td><?php echo JText::_('TOTAL'); ?></td>
			 							<td><?php echo $currency_html.($products_total+$promotion_total+$duties_total); ?> </td>
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
			 	<button type="button" class="btn btn-primary btn-block btn-large" onClick="return paycart.checkout.confirm.process(); " ><?php echo JText::_('Proceed to Payment'); ?></button>
			 </div>
			 
			 <input	type="hidden"	name='step_name' value='confirm' />
		 	
	 	</div>

	 </div>	 
</div>

<script>
(function($){

			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
</script>
<?php

