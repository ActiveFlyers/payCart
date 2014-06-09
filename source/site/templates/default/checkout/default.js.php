<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/

/**
 * @PCTODO: List of Populated Variables
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

$checkout_sequence 	= 	PaycartFactory::getHelper('checkout')->getsequence();
$steps				=	array_keys($checkout_sequence); 

// $steps have string value so we need to add quote (') as suffix and prefix 
 $steps = array_map(function($step){ return "'$step'"; }, $steps);
 
?>

<script>


	(function($){

		//define checkout
		
		/**
		 * -----------------------------------------------------------
		 * Checkout > Process
		 *
		 *	1. Change (step_ready)
		 * 	 	Invoke when you need to change step
		 * 	 	step_ready : current step
		 * 
		 *	2 do  
		 *		Submit current active step form
		 * 
		 *	3. success(data)
		 * 		Invoke when successfully complete current step.
		 * 		data : JSON object
		 * 
		 * 	4. notification(data)
		 * 		Invoke when anything is going wrong with current step
		 * 		data : JSON object
		 * 
		 * -----------------------------------------------------------
		 */
		paycart.checkout=
		{
			step :
			{
				change : function(step_ready)
				{
					// @PCTODO: do not use hard-coding
					// availble steps 
					var steps 		= [ <?php echo implode(',', $steps); ?> ];
					var class_name	= '';
	
					// set incomplet class to all (remove previous class and add default classes);
					$('.pc-checkout-step').removeClass('text-success').addClass('muted');
					
					for (i=0; i<steps.length; i++) {
	
						class_name = '.pc-checkout-step-'+steps[i];
	
						$(class_name).removeClass('muted');
						
						if (step_ready == steps[i]){
							// active mark it
							$(class_name).removeClass('muted');
							break; 
						}
	
						//Previous step mark completed
						$(class_name).addClass('text-success');
					}
				}
		 	},

		 	process : function()
			{
				// get all form data for post	
				var postData 	= $("#pc-checkout-form").serializeArray();
				var link  		= 'index.php?option=com_paycart&view=checkout&task=process';
	
				console.log('paycart.checkout.process');
	
				//@PCTODO :: Display Spinner{ request is processing }  
	
	
				paycart.ajax.go(link, postData);
	
				return false;
					
			},


			goback : function(data)
			{
				var link  		= 'index.php?option=com_paycart&view=checkout&task=goback';
	
				console.log('paycart.checkout.goback.do');
	
				//@PCTODO :: Display Spinner{ request is processing }  
	
				paycart.ajax.go(link, data);
	
				return false;
					
			},
	
			/**
			 * data is json object 
			 */
			success: function(data)
			{
				console.log('paycart.checkout.process.success : start');
	
				// replace string
				$(".pc-checkout-step-html").html(data.html);
	
				console.log('paycart.checkout.process.success : end');
			},
	
			/**
			 * data is json object 
			 */
			notification :function(data)
			{
				console.log('paycart.checkout.process.error :: ' + data);
			}		
		};


	   /**
		*-----------------------------------------------------------
		* Checkout > Login Screen
		*
		* 1. init
		* 		Initialize screen to default settings.
		* 
		* 2. do 
		*		Submit login data
		*
		* 3. setEmailCheckout(bool is_guest) :
		* 		is_guest= TRUE then Set mode to guest checkout,
		* 		hide elements which have attribute data-pc-emailcheckout="hide"
		* 		show elements which have attribute data-pc-emailcheckout="show"
		*-------------------------------------------------------------
		*/
		paycart.checkout.login =
			{
				init : function()
				{
					// initialize screen interface
					//1. on click on guest checkout mode
					paycart.checkout.login.setEmailCheckout(true);
					
					$('#paycart_form_emailcheckout_1').click(function(){
							paycart.checkout.login.setEmailCheckout(true)
						});
					
					$('#paycart_form_emailcheckout_0').click(function(){
						paycart.checkout.login.setEmailCheckout(false)
					});
				},
				
				do : function()
				{
					console.log('paycart.checkout.login.do');
					
					paycart.checkout.process();

					return false;					
				},

				setEmailCheckout : function(is_guest)
				{
					//default is guest mode
					if(is_guest){
						$('[data-pc-emailcheckout="show"]').show();
						$('[data-pc-emailcheckout="hide"]').hide();
					}else{
						$('[data-pc-emailcheckout="show"]').hide();
						$('[data-pc-emailcheckout="hide"]').show();
					}
				}
			};

		/**
		*-----------------------------------------------------------
		* Checkout > Address Screen
		*
		* 1. Copy
		*		from 	: 	data get "from" selector
				to		:	data copy "to" seletor 
		* 		Copy one form data to anaother form
		* 
		* 2. do 
		*		Submit login data
		*
		*-------------------------------------------------------------
		*/

		paycart.checkout.address = 
		{
			copy : function(from, to)
			{
				var regExp 			=	/\[(\w*)\]$/;
				var from_name 		=	'paycart_form['+from +']';
				var to_name 		=	'paycart_form['+to +']';
				
				var form_selector	= '[name^="'+from_name+'"]';
					
				$(form_selector).each(function() {

					// get index
					var matches = this.name.match(regExp);

					if (!matches) {
						return false;
					}

					//matches[1] contains the value between the Square Bracket
					var index 		= matches[1];
					var to_selector = '[name^="'+to_name+'['+index+']"]';

					$(to_selector).val($(this).val())
				});

				console.log('copy '+from+' to '+to);
			},
		
			// Copy billing to shipping				
			billing_to_shipping : function()
			{
				// Checked billing to shipping 
				if( $('#billing_to_shipping').prop('checked') == true ) { 

					paycart.checkout.address.copy('billing', 'shipping');
					
					$('.pc-checkout-shipping fieldset:first').fadeOut();

					return true;
				} 

				// unchecked billing to shipping 
				
				// delete all shipping input values
				$('[name^="paycart_form[shipping]"]').val('');

				// Open shipping address deatil field setfor
				$('.pc-checkout-shipping fieldset:first').fadeIn();
				
				console.log('delete input from shipping');

				return true;
			},

			/**
			 * Invoke to continue checkout flow
			 */
			onContinue	:	function()
			{
				//Before Submit Copy billing to shipping address
				if ( $('#billing_to_shipping').prop('checked') == true ) { 
					paycart.checkout.address.copy('billing', 'shipping');
				}

				paycart.checkout.address.do();
			},

			/**
			 * Invoke to submit to get action
			 */
			do : function()
			{
				console.log('paycart.checkout.address.do');

				paycart.checkout.process();

				return false;					
			}
		};


		/**
		*-----------------------------------------------------------
		* Checkout > Address Screen
		*	All function required when buyer is login and address already exist 
		*
		*-------------------------------------------------------------
		*/
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
					'move_next_name'	:	'shipping_address_info',		
					//next-move required (true then move to shipping address ) Otherwise move to next checkout step 
					'move_next'			:	true	
			},

			/**
			 *  Invoke to get shipping address stuff
			 */
			shipping_address_info	: 
			{
					'name'				:	'shipping',
					'input_selector'	:	'#shippingaddress_id',
					'same_as_selector'	:	'#billing_to_shipping',
					'title_text'		:	'Select Shipping Address',
					'div_selector'		:	'.pc-checkout-shippingaddress-addnew-html',
					'move_next_name'	:	'billing_address_info',
					'move_next'			:	false
			},

			visible_address_info	:	{},

		   /**
			* Initial setup for buyer address
			*	- Which address will visible
			*	- According to visible address set other stuff like title, hide dummy span which contain previous selected address 
			*	
			*/
			init : function(visible_address)
			{
				if (visible_address) { 
					// set current visble address
					paycart.checkout.buyeraddress.visible_address_info = visible_address;
				}

				//if address is not available then set default address
				if ($.isEmptyObject(paycart.checkout.buyeraddress.visible_address_info)) {
					// if address is not avalable
					paycart.checkout.buyeraddress.visible_address_info = paycart.checkout.buyeraddress.billing_address_info;
				}

				//get current visible address
				var buyer_address = paycart.checkout.buyeraddress.visible_address_info;

				// Show add-new buyer address span
				$('.pc-checkout-buyeraddress-addnew').show();

				// Current title
				$('[data-pc-checkout-buyeraddresses-title="show"]').html('<h3>'+buyer_address['title_text']+'</h3>');
			},
			
			/**
			 * After selecting any address invoke this function to execute further
			 * processing.
			 */
			onSelect : function (buyeraddress_id) 
			{
				var visible_buyeraddress = paycart.checkout.buyeraddress.visible_address_info;

				// set buyeraddress id on hidden input element
				$(visible_buyeraddress['input_selector']).val(buyeraddress_id);

				// if next step required 
				if( !visible_buyeraddress['move_next'] ) {
					paycart.checkout.address.do();
					return false;
				}

				// if first address is set then set next address

				//change visible address
				paycart.checkout.buyeraddress.init(paycart.checkout.buyeraddress[visible_buyeraddress['move_next_name']]);

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
				var visible_buyeraddress = paycart.checkout.buyeraddress.visible_address_info;
				
				// next address show for next task
				if (paycart.checkout.buyeraddress.selected(0) === false) {
					// it means ready to next step
					return false;
				};

				var from_name 		=	visible_buyeraddress['name'];

				var to 			=	$('[name^="paycart_form['+from_name+'][to]"]').val();
				var address		=	$('[name^="paycart_form['+from_name+'][address]"]').val();
				var zipcode		=	$('[name^="paycart_form['+from_name+'][zipcode]"]').val();
				var country		=	$('[name^="paycart_form['+from_name+'][country]"]').find(":selected").text();
				var state		=	$('[name^="paycart_form['+from_name+'][state]"]').find(":selected").text();
				var city		=	$('[name^="paycart_form['+from_name+'][city]"]').val();
				var phone		=	$('[name^="paycart_form['+from_name+'][phone1]"]').val();

				//@PCFIXME:: add VAT var
				
				// @PCTODO::Address should be formated accoding to address formate
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
				
				//set to for address
				//$('[href="#pc-checkout-buyeraddress-0"]').html('Use Same as {previous} Address');
				
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
				var visible_buyeraddress	=	paycart.checkout.buyeraddress.visible_address_info;
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
			},

			/**
			 * Click on cancel button
			 */
			onCancel : function()
			{
				// hide addnew-html
				$('.pc-checkout-shippingaddress-addnew-html, .pc-checkout-billingaddress-addnew-html').hide();

				// show add new span
				$('.pc-checkout-buyeraddress-addnew').show();

			},

			/**
			 * Click to add new address
			 */
			addNew	:	function()
			{
				$('.pc-checkout-buyeraddress-addnew').hide();
				var visible_buyeraddress = paycart.checkout.buyeraddress.visible_address_info;
				$(visible_buyeraddress['div_selector']).show();
				
			}
		};
			
		
	})(paycart.jQuery);


</script>