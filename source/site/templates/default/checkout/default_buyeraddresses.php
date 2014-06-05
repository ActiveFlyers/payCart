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
?>
<!-- @PCTODO :: move style to proper location -->
<style>
.paycart .pc-transperent{
	position: absolute;
	top: 0;
	left :0;
	right : 0;
	bottom:0;
	background-color: rgba(255, 255, 255, 0.9);
}

.paycart .pc-position-relative 
{
	position: relative;
}
</style>

	<div data-pc-checkout-buyeraddresses-title="show">
	</div>

	<div class="pc-checkout-buyeraddress row-fluid" >
		
		<!--	Span for copy-->
		<div class="span4 accordion pc-checkout-buyeraddress-new-created" id="pc-checkout-buyeraddress-accordion-0">
			<div class="accordion-group">
	 			
	 			<div class="accordion-heading">
	 				<a class="accordion-toggle" >
	 					<?php echo JText::_('COM_PAYCART_SAME_ADDRESS_TITLE') ; ?>
	 				</a>
	 			</div>
	 		
	 			<div id="pc-checkout-buyeraddress-0" class="accordion-body "">
			 		<div class="accordion-inner">
			 			<div class="pc-position-relative " >
				 			<address>
							</address>
						
							<div class="pc-transperent text-center text-success pc-checkout-transperent-billing-to-shiping"  >
							
				 				<i class="fa fa-files-o fa-3x"></i>
								<p>
									<?php echo JText::_('COM_PAYCART_SAME_ADDRESS_TEXT') ; ?>
								</p>
								<a href='#' onclick="return paycart.checkout.buyeraddress.view_address('.pc-checkout-transperent-billing-to-shiping');" ><i class="fa fa-search"></i>View Billing Address</a>
							</div>
						</div>
						
						<div>
							<hr />
							<button type="button" class="btn btn-info btn-large btn-block" onClick="paycart.checkout.buyeraddress.copy()">
								<?php echo JText::_('COM_PAYCART_BUTTON_YES_AND_CONTINUE') ; ?>
							</button>
						</div>
		 			</div>
		 		</div>
	 		</div>
		</div>
			
		<?php 
		foreach ($buyer_addresses as $buyeraddress_id => $buyeraddress_details) :
		?>
			<div class="span4 accordion"  id="pc-checkout-buyeraddress-accordion-<?php echo$buyeraddress_id?>" >
				<div class="accordion-group">
	 				<div class="accordion-heading">
	 					<a class="accordion-toggle">
	 						<?php echo $buyeraddress_details->to; ?>
	 					</a>
	 				</div>
	 		
			 		<div id="pc-checkout-buyeraddress-<?php echo$buyeraddress_id?>" class="accordion-body "">
			 			<div class="accordion-inner">
			 				<address>
								<?php echo $buyeraddress_details->address; ?><br>
								<?php echo "{$buyeraddress_details->city}-{$buyeraddress_details->zipcode}"; ?><br>
								<?php echo "{$buyeraddress_details->state}"; ?><br>
								<?php echo "{$buyeraddress_details->country}"; ?><br><br>
								<abbr title="Phone"><i class="fa fa-phone"></i></abbr> <?php echo "{$buyeraddress_details->phone1}"; ?><br>
							</address>
						
							<div>
		 						<hr />
								<button type="button" class="btn btn-info btn-large btn-block" onClick="paycart.checkout.buyeraddress.selected(<?php echo$buyeraddress_id?>)">
									<?php echo JText::_('COM_PAYCART_BUTTON_SELECT') ; ?>
								</button>
		 					</div>
			 			</div>
			 		</div>
	 			</div>
			</div>
		<?php 
		endforeach;
		?>	
		
		
	 	<div class="span4  pc-checkout-buyeraddress-addnew well" onClick="paycart.checkout.buyeraddress.addNew()" >
	 		
	 		<div class="row-fluid text-center muted" style="border:1px dotted;">
				<div style="padding: 10% 25%;" >
					<i class="fa fa-plus-circle fa-3x"></i>
				</div>
			</div>
			<br>
		 	
		 	<div>
		 		<button class="btn btn-primary btn-large btn-block" type="button">
					<?php echo JText::_('COM_PAYCART_BUTTON_ADD_NEW_ADDRESS') ; ?>
				</button>
			</div>
					
		</div>
		
		<!--	Billing Address -->
		<div class="span4 pc-checkout-billingaddress-addnew-html" >
			<div class="accordion-group ">
				<div class="accordion-heading">
	 				<a class="accordion-toggle"	 href="#">
	 					<?php echo JText::_('COM_PAYCART_ADD_BILLING_ADDRESS_TITLE') ; ?>
	 				</a>
	 			</div>
	 		
		 		<div  class="accordion-body "">
		 			<div class="accordion-inner">
		 				<?php echo $this->loadTemplate('billingaddress'); ?>
			 			<div class="clearfix">
					 		<button class="btn btn-large pull-left " type="button" onClick="paycart.checkout.buyeraddress.onCancel()">
								<?php echo JText::_('COM_PAYCART_BUTTON_CANCEL') ; ?>
							</button>
							
					 		<button class="btn btn-primary btn-large pull-right" type="button" onClick="paycart.checkout.buyeraddress.create()">
								<?php echo JText::_('COM_PAYCART_BUTTON_CONTINUE') ; ?>
							</button>
							
					 	</div>
					 </div>
		 		</div>
 			</div>
		</div>
	
		<!--	Shipping Address	-->
		<div class="span4 pc-checkout-shippingaddress-addnew-html" >
			<div class="accordion-group">
				<div class="accordion-heading">
	 				<a class="accordion-toggle" href="#">
	 					<?php echo JText::_('COM_PAYCART_ADD_SHIPPING_ADDRESS_TITLE') ; ?>  
	 				</a>
	 			</div>
	 		
		 		<div  class="accordion-body">
		 			<div class="accordion-inner">
		 				<?php echo $this->loadTemplate('shippingaddress'); ?>
			 			<div class="clearfix">
					 		<button class="btn btn-large pull-left " onClick="paycart.checkout.buyeraddress.onCancel()" type="button">
								<?php echo JText::_('COM_PAYCART_BUTTON_CANCEL') ; ?>
							</button>
							
					 		<button class="btn btn-primary btn-large pull-right" type="button" onClick="paycart.checkout.buyeraddress.create()">
								<?php echo JText::_('COM_PAYCART_BUTTON_CONTINUE') ; ?>
							</button>
							
					 	</div>
					 </div>
		 		</div>
 			</div>
		</div>
		
		<!-- hidden element		-->
		<input	type="hidden"	id="billingaddress_id"		name='paycart_form[billingaddress_id]'		value='0' />
		<input	type="hidden"	id='shippingaddress_id'		name='paycart_form[shippingaddress_id]'		value='0' />
		<input	type="hidden"	id="billing_to_shipping"	name='paycart_form[billing_to_shipping]'	value='0' />
		<input	type="hidden"	id="shipping_to_billing"	name='paycart_form[shipping_to_billing]'	value='0' />
		
		
	</div>

		
		
	<script>
			
		(function($) {

			//Default first we will ask billing address.
			paycart.checkout.buyeraddress.visible_address = paycart.checkout.buyeraddress.billing_address_info;

			paycart.checkout.buyeraddress.init();	
			
		})(paycart.jQuery);
	
	</script>	 

<?php

