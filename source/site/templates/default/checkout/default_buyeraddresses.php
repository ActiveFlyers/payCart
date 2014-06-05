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
	 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-checkout-buyeraddress-0">
	 					
	 				</a>
	 			</div>
	 		
	 			<div id="pc-checkout-buyeraddress-0" class="accordion-body in collapse"">
			 		<div class="accordion-inner">
			 			<div class="pc-position-relative " >
				 			<address>
							</address>
						
							<div class="pc-transperent text-center text-success pc-checkout-transperent-billing-to-shiping"  >
							
				 				<i class="fa fa-files-o fa-3x"></i>
								<p>
									Shipping address same as 
									Billing address
								</p>
								<a href='#' onclick="return paycart.checkout.buyeraddress.view_address('.pc-checkout-transperent-billing-to-shiping');" ><i class="fa fa-search"></i>View Billing Address</a>
							</div>
						</div>
						
						<div>
							<hr />
							<button type="button" class="btn btn-info btn-large btn-block" onClick="paycart.checkout.buyeraddress.copy()">
								Yes & Continue
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
	 					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-checkout-buyeraddress-<?php echo$buyeraddress_id?>">
	 						<?php echo $buyeraddress_details->to; ?>
	 					</a>
	 				</div>
	 		
			 		<div id="pc-checkout-buyeraddress-<?php echo$buyeraddress_id?>" class="accordion-body in collapse"">
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
									Select
								</button>
		 					</div>
			 			</div>
			 		</div>
	 			</div>
			</div>
		<?php 
		endforeach;
		?>	
		
		
	 	<div class="span4  pc-checkout-buyeraddress-addnew well" >
	 		
	 		<div class="row-fluid text-center muted" style="border:1px dotted;">
				<div style="padding: 10% 25%;" >
					<i class="fa fa-plus-circle fa-4x"></i>
				</div>
			</div>
			<br>
		 	
		 	<div>
		 		<button class="btn btn-primary btn-large btn-block" type="button">
					Add New Address
				</button>
			</div>
					
		</div>
		
		<!--	Billing Address -->
		<div class="span4 pc-checkout-billingaddress-addnew-html" >
			<div class="accordion-group ">
				<div class="accordion-heading">
	 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#">
	 					Add Billing Address 
	 				</a>
	 			</div>
	 		
		 		<div id="" class="accordion-body in collapse"">
		 			<div class="accordion-inner">
		 				<?php echo $this->loadTemplate('billingaddress'); ?>
			 			<div class="clearfix">
					 		<button class="btn btn-large pull-left pc-checkout-buyeraddress-addnew-cancel" type="button">
								Cancel
							</button>
							
					 		<button class="btn btn-primary btn-large pull-right" type="button" onClick="paycart.checkout.buyeraddress.create()">
								Continue
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
	 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#">
	 					Add Shipping Address 
	 				</a>
	 			</div>
	 		
		 		<div id="" class="accordion-body in collapse"">
		 			<div class="accordion-inner">
		 				<?php echo $this->loadTemplate('shippingaddress'); ?>
			 			<div class="clearfix">
					 		<button class="btn btn-large pull-left pc-checkout-buyeraddress-addnew-cancel" type="button">
								Cancel
							</button>
							
					 		<button class="btn btn-primary btn-large pull-right" type="button" onClick="paycart.checkout.buyeraddress.create()">
								Continue
							</button>
							
					 	</div>
					 </div>
		 		</div>
 			</div>
		</div>
		
		<!-- hidden element		-->
		<input	type="hidden"	id="billingaddress_id"		name='paycart_form[billingaddress_id]'	value='0' />
		<input	type="hidden"	id='shippingaddress_id'		name='paycart_form[shippingaddress_id]'	value='0' />
		<input	type="hidden"	id="billing_to_shipping"	name='paycart_form[billing_to_shipping]'	value='0' />
		<input	type="hidden"	id="shipping_to_billing"	name='paycart_form[shipping_to_billing]'	value='0' />
		
		
	</div>

		
		
	<script>
			
		(function($) {

			paycart.checkout.buyeraddress = 
			{

				/**
				 *  Invoke to get billing address stuff
				 */			
				billing_address_info	: 
				{
						// Paycart form name where billing info save
						'name'				:	'billing',					
						// billing id set on this selector if select existing billing address
						'input_selector'	:	'#billingaddress_id',
						//copy selector
						'same_as_selector'	:	'#shipping_to_billing',
						// Div Title text 		
						'title_text'		:	'Select Billing Address',	
						// div will be visible when try to add new address
						'div_selector'		:	'.pc-checkout-billingaddress-addnew-html',
						// next move 
						'move_next_name'	:	'shippinging_address_info',		
						//next-move required (true then move to shipping address ) Otherwise move to next checkout step 
						'move_next'			:	true	
				},

				/**
				 *  Invoke to get shipping address stuff
				 */
				shippinging_address_info	: 
				{
						'name'				:	'shipping',
						'input_selector'	:	'#shippingaddress_id',
						'same_as_selector'	:	'#billing_to_shipping',
						'title_text'		:	'Select Shipping Address',
						'div_selector'		:	'.pc-checkout-shippingaddress-addnew-html',
						'move_next_name'	:	'billing_address_info',
						'move_next'			:	false
				},

				/**
				* Initial setup for buyer address
				*	- Which address will visible
				*	- According to visible address set other stuff like title, hide dummy span which contain previous selected address 
				*	
				*/
				init : function()
				{
					var buyer_address = paycart.checkout.buyeraddress.visible_address;
					
					// default both address disable {shipping and billing address}
					$('.pc-checkout-shippingaddress-addnew-html, .pc-checkout-billingaddress-addnew-html ').hide();

					// hide dummy span, Used for display new created address  
					$('.pc-checkout-buyeraddress-new-created').hide()
					
					// Show add-new buyer address span
					$('.pc-checkout-buyeraddress-addnew').show();

					// Current title
					$('[data-pc-checkout-buyeraddresses-title="show"]').html('<h3>'+buyer_address['title_text']+'</h3>');

				},
				
				/**
				 * After selecting any address invoke this function to execute further
				 * processing.
				 */
				selected : function (buyeraddress_id) 
				{
					var visible_buyeraddress = paycart.checkout.buyeraddress.visible_address;

					// set buyeraddress id on hidden input element
					$(visible_buyeraddress['input_selector']).val(buyeraddress_id);

					// if next step required 
					if( !visible_buyeraddress['move_next'] ) {
						paycart.checkout.address.do();
						return false;
					}

					// if first address is set then set next address

					//change visible address
					paycart.checkout.buyeraddress.visible_address = paycart.checkout.buyeraddress[visible_buyeraddress['move_next_name']];
					paycart.checkout.buyeraddress.init();

					// display span-0 (dummy span) which contain selected address
					if ( buyeraddress_id ) {	
						
						var to 				=	$('[href="#pc-checkout-buyeraddress-'+buyeraddress_id+'"]').html();
						var full_address 	=	$("#pc-checkout-buyeraddress-"+buyeraddress_id).find('address').html();
	
						paycart.checkout.buyeraddress.display_selected_address(to+'<br />'+full_address);

						// hide span
						$('#pc-checkout-buyeraddress-accordion-'+buyeraddress_id).hide();
					}

					return true;
										
				},

				/**
				 * Invoke to take action after creating new address like first put billing address then shipping then
				 *	- After billing address it will set "new craeted address" value into  span-0 (dummy span)
				 *    and will move to next action (fill shipping information)
				 *	- After Shipping information it will call to next checkout step (order review)
				 *  	 
				 */
				create : function()
				{

					var visible_buyeraddress = paycart.checkout.buyeraddress.visible_address;
					
					// next address show for next task
					if (paycart.checkout.buyeraddress.selected(0) === false) {
						// it means ready to next step
						return false;
					};

					var from_name 		=	visible_buyeraddress['name'];

					var to 			=	$('[name^="paycart_form['+from_name+'][to]"]').val();
					var address		=	$('[name^="paycart_form['+from_name+'][address]"]').val();
					var zipcode		=	$('[name^="paycart_form['+from_name+'][zipcode]"]').val();
					var country		=	$('[name^="paycart_form['+from_name+'][country]"]').val();
					var state		=	$('[name^="paycart_form['+from_name+'][state]"]').val();
					var city		=	$('[name^="paycart_form['+from_name+'][city]"]').val();
					var phone		=	$('[name^="paycart_form['+from_name+'][phone1]"]').val();

					var full_address	 = 		to+'<br/>'+address+'<br/>'+city+'-'+zipcode+'<br/>'+state+'<br/>'+country + '<br /> <br />';
						full_address	+=		'<abbr title="Phone"><i class="fa fa-phone"></i></abbr>' +  phone + '<br>';	
					
					
					paycart.checkout.buyeraddress.display_selected_address(full_address);
				},

				/**
				 * Display selected address into span0 (dummy span) 
				 */
				display_selected_address : function(full_address)
				{
					$('#pc-checkout-buyeraddress-accordion-0').show();

					//@FIXME :: clean it
					
					//set to for address
					$('[href="#pc-checkout-buyeraddress-0"]').html('Use Same as {previous} Address');
					
					// set rest element
					$('#pc-checkout-buyeraddress-0').find('address').html(full_address);

					// intimate to selected address (this span is always render first so we have initimate by Title)
					$('html, body').animate({scrollTop:$('.pc-checkout-state').position().top}, 'slow');
				},

				/**
				 * Always invoke on second move to say "Continue with perevious action" like first select billing then shippingf address
				 *	- If billing address selected into existing address then set same id into shipping address id
				 	- If new billing address is created then this billing address will be copied to shipping address.
				 *
				 */
				copy : function()
				{
					// if first address id (let say billing_address_id) set, It means buyer already choosed existing address
					// so we will copy this address id to next address id (it means, Copy billing_address_id to shipping_address_id)
					var visible_buyeraddress	=	paycart.checkout.buyeraddress.visible_address;
					var previous_buyeraddress	=	paycart.checkout.buyeraddress[visible_buyeraddress['move_next_name']];

					var previous_buyeraddress_id	=	$(previous_buyeraddress['input_selector']).val();

 
					// if create new address then move-next and click on same as previous action then need to set same as selector other wise new address will be created)
					// so set same as seletor
					$(visible_buyeraddress['same_as_selector']).val(true);
					
										
					if ( previous_buyeraddress_id ) {
						// address will be set and next action will be handled by this method
						paycart.checkout.buyeraddress.selected(previous_buyeraddress_id);		
						return false;				
					}

					// if previous address is new created (i.e. billing address)t
					// then copy previous address form data to new address form data (shipping address )
					paycart.checkout.address.copy(previous_buyeraddress['name'], visible_buyeraddress['name']);
					//take action after copy
					paycart.checkout.buyeraddress.selected(0);
				},

				/**
				* hide existing selector. 
				*/
				view_address : function(selector) 
				{
					$(selector).hide();
					return false;
				}
					
			};

			//Default first we will ask billing address.
			paycart.checkout.buyeraddress.visible_address = paycart.checkout.buyeraddress.billing_address_info;

			//@FIXME :: put it into checkout namespace 
			$('.pc-checkout-buyeraddress-addnew').on('click', function(){
				$(this).hide();
				var visible_buyeraddress = paycart.checkout.buyeraddress.visible_address;
				$(visible_buyeraddress['div_selector']).show();
				
			});

			//@FIXME :: put it into checkout namespace 
			$('.pc-checkout-buyeraddress-addnew-cancel').on('click', function(){
				// hide addnew-html
				$('.pc-checkout-shippingaddress-addnew-html, .pc-checkout-billingaddress-addnew-html').hide();

				// show add new button
				$('.pc-checkout-buyeraddress-addnew').show();

			});


			paycart.checkout.buyeraddress.init();	
			
		})(paycart.jQuery);
	
	</script>	 

<?php

