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

 //load chosen when client platform is not mobile
 if (!$is_platform_mobile) {
	JHtml::_('formbehavior.chosen', '.pc-buyeraddress');
 }
 
?>

<script>


	(function($){

		//define checkout
		
		
		// Loader 
		$( document ).ajaxStart(function() {
			paycart.ajax.loader.show();
			}).ajaxStop(function() {
				paycart.ajax.loader.hide();
			});
		
		paycart.ajax.loader = 
		{
			show : function() 
			{
				$('#pc-checkout-loader').show();
			},

			hide : function()
			{
				$('#pc-checkout-loader').hide();
			}
		
		};

		
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
				change : function(steps)
				{
					var selector, on_click, steps = $.parseJSON(steps);
					
					// set in-complete class to all (remove previous class and add default classes);
					$('.pc-checkout-step').removeClass('text-success pc-checkout-cursor-pointer').addClass('muted').unbind('click');
					
					for ( var key in steps ) {
						
						selector = '.'+steps[key].class;

						$(selector).removeClass('muted');
						
						if (steps[key].isActive) {
							break;
						}

						// add success
						$(selector).addClass('text-success');

						on_click = steps[key].onclick;
						 
						// bind click function on completed stage 
						if ( on_click  ) {
							$(selector).addClass('pc-checkout-cursor-pointer').
								on('click', { 'method' : on_click },
									function(event)
									{	//@PCTODO ::Remove eval and create paycart utility function
										// take reference http://stackoverflow.com/questions/359788/how-to-execute-a-javascript-function-when-i-have-its-name-as-a-string
										eval(event.data.method);
									});
					    }
					}
				}
		 	},

		 	process : function()
			{
				// get all form data for post	
				var postData 	= $("#pc-checkout-form").serializeArray();
				var link  		= 'index.php?option=com_paycart&view=checkout&task=process';
	
				if($("#pc-checkout-form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
					// Our validation work on submit call therefore first we will ensure that form is not properly fill 
					// then we will call submit method. So proper msg display and focus on required element. 
					//$("#pc-checkout-form").submit();
					console.log('Validation fail');
				}
				
				//console.log('paycart.checkout.process');
	
				paycart.ajax.go(link, postData);
	
				return false;
					
			},


			goback : function(data)
			{
				var link  		= 'index.php?option=com_paycart&view=checkout&task=goback';
	
				//console.log('paycart.checkout.goback.do');	
				paycart.ajax.go(link, data);
	
				return false;
					
			},
	
			/**
			 * data is json object 
			 */
			success: function(data)
			{
				//console.log('paycart.checkout.process.success : start');
	
				// replace string
				$(".pc-checkout-step-html").html(data.html);
	
				//console.log('paycart.checkout.process.success : end');
			},
	

			getData : function(request)
			{
				var url = ( typeof request['url'] == "undefined"  ) 
	    					? 'index.php?option=com_paycart&view=checkout'
							: request['url'];

				// json formate nd call back
				url = url+'&format=json';
				
				$.ajax({
				    url		: ( typeof request['url'] == "undefined"  ) 
					    		? 'index.php?option=com_paycart&view=checkout&format=json'
	    						: request['url']+'&format=json',
	    						
				    cache	: ( typeof request['cache'] == "undefined" ) 
		    					? false
								: request['cache'],
								
					data	: ( typeof request['data'] == "undefined" ) 
					    		? {}
								: request['data'],

				    success : function( response ) {

								//console.log ("Success:  " + response );

								//clear data (remove warnings and error)
								response = rb.ajax.junkFilter(response);
								
								 
								if( typeof response['message_type'] != "undefined" ) { 
									console.log ( {" response contain error :  " : response } );
						    		return false;
								}
		
								// Any callback available
						    	if( typeof response['callback'] != "undefined"  && response['callback'] ) {
							    	//@PCTODO:: cross check function existing into paycart namespace  
						    		var callback = new Function(response['callback']);
						    		callback(response);
									return true;
								}

						    	// Any callback available
						    	if( typeof request['success_callback'] != "undefined"  && request['success_callback'] ) { 
						    		var callback = request['success_callback'];
						    		callback(response);
						    		return true;
								}

								return true;
						    },

					error : function( response ) {

						    	console.log ({"Error on fetching JSON data :  " :response} );

						    	return response;
						    }
				  });
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
					//console.log('paycart.checkout.login.do');
					
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
		*
		*-------------------------------------------------------------
		*/

		paycart.checkout.buyeraddress = 
		{
			copy : function(from, to)
			{
				var regExp 			=	/\[(\w*)\]$/, 
					from_name 		=	'paycart_form['+from +']',
					to_name 		=	'paycart_form['+to +']',
					form_selector	= 	'[name^="'+from_name+'"]',
					state_value		=	0,
					byeraddress		= 	[],
					data			=	[],
					matches, index;
				
					
				$(form_selector).each(function() {

					// get index
					matches = this.name.match(regExp);

					if (!matches) {
						return false;
					}

					//matches[1] contains the value between the Square Bracket
					index 		= matches[1];

					byeraddress[index]	=	$(this).val();
				});

				data['selector_index']	=	to ;
				data['buyeraddress']	=	byeraddress ;
				
				paycart.checkout.buyeraddress.setAddress(data);
								
				//console.log('copy '+from+' to '+to);
			},

			init	: function()
			{
				// if billing to shipping already checked then need to copy all address
				paycart.checkout.buyeraddress.onBillingToShipping();
			},

		   /**
			* Invoke to get specific address detail and put into input containers
			* 
			* selected_address_id	: Selected address value 
			* selector_index 		: Either billing or shipping
			* 
			*/
			onSelect	: function(selected_address_id, selector_index)
			{
				selected_address_id = parseInt(selected_address_id);
				
				if (!selected_address_id) {
					return true;
				}

				var request = [];
				request['data'] = { 
									'buyeraddress_id' 	: selected_address_id, 
									'task' 				: 'getBuyerAddress',
									'selector_index'	: selector_index
								  };
				  request['success_callback']	=	paycart.checkout.buyeraddress.setAddress;
				  
				paycart.checkout.getData(request);
			},

		   /**
			* Invoke to fill address values into selected address {either billing or shipping}
			*/
			setAddress : function(data) 
			{
				// paycart_form[billing] or paycart_form[shipping] 
				var selecor_name = 'paycart_form['+data['selector_index'] +']', 
					state_value	= 0 ;
				
				for (index in data['buyeraddress']) {
					$('[name="'+selecor_name+'['+index+']"]').val(data['buyeraddress'][index]);

					if ('state_id' == index) {
						state_value 	=	data['buyeraddress'][index];
					}
				}

				// special treatment for country and state value
				$('[name="'+selecor_name+'[country_id]"]').trigger('change', {'state_id' : state_value});
				
			},
			
		
			// Copy billing to shipping				
			onBillingToShipping : function()
			{
				// Checked billing to shipping 
				if( $('#billing_to_shipping').prop('checked') == true ) { 

					paycart.checkout.buyeraddress.copy('billing', 'shipping');

					$('.pc-checkout-shipping-html').fadeOut();

					return true;
				} 

				// unchecked billing to shipping

				// Open shipping address deatil field set 
				$('.pc-checkout-shipping-html').fadeIn();
				
				return true;
			},

			/**
			 * Invoke to continue checkout flow
			 */
			onContinue	:	function()
			{
				//Before Submit Copy billing to shipping address
				if ( $('#billing_to_shipping').prop('checked') == true ) { 
					paycart.checkout.buyeraddress.copy('billing', 'shipping');
				}

				paycart.checkout.buyeraddress.do();
			},

			/**
			 * Invoke to submit to get action
			 */
			do : function()
			{
				//console.log('paycart.checkout.address.do');

				paycart.checkout.process();

				return false;					
			}
		};

	   /**
		*-----------------------------------------------------------
		* Checkout > Order confirm Screen 
		*-------------------------------------------------------------
		*/
		paycart.checkout.confirm = 
		{
			edit : 
			{	
				email : function() 
				{
					try
					{
						var data = {'back_to' : 'email_address'};
						paycart.checkout.goback(data);
					} catch (e) {
						console.log({'exception_was ' : e});
					}
					
					return false;
				},
			
				address : function()
				{
					try {
						var data = {'back_to' : 'address'};
						paycart.checkout.goback(data);
					} catch (e) {
						console.log({'exception_was': e});	
					}

					return false;
				},

				confirm : function()
				{
					try {
						var data = {'back_to' : 'confirm'};
						paycart.checkout.goback(data);
					} catch (e) {
						console.log({'exception_was': e});	
					}

					return false;
				}

			},

			process :	function()
			{
				paycart.checkout.process()
				
			},

			// update product-quantity into cart
			onChangeProductQuantity : function(product_id, product_quantity)
			{
				var link, postData;

				// @PCTODO:: Properly validate it
				
				// get all form data for post	
				postData 	= {'product_id' : product_id, 'quantity' : product_quantity};
				link  		= 'index.php?option=com_paycart&view=checkout&task=updateProductQuantity';
				paycart.ajax.go(link, postData);
				
				return false;				
			},

			// update product-quantity into cart
			onRemoveProduct : function(product_id)
			{
				var link, postData;

				// get all form data for post	
				postData 	= {'product_id' : product_id};
				link  		= 'index.php?option=com_paycart&view=checkout&task=removeproduct';
				paycart.ajax.go(link, postData);
				
				return false;
			},

			// update product-quantity into cart
			onApplyPromotionCode : function()
			{
				// get all form data for post	
				postData 	= {'promotion_code' : $('#paycart-promotion-code-input-id').val()};
				link  		= 'index.php?option=com_paycart&view=checkout&task=applyPromotion';
				paycart.ajax.go(link, postData);
				
				return false;
			}
			
		};

	   /**
		*-----------------------------------------------------------
		* Checkout > Payment Screen 
		*-------------------------------------------------------------
		*/
		paycart.checkout.payment = 
		{
			onChangePaymentgateway : function() 
			{
				var paymentgateway_id = $('#pc-checkout-payment-gateway').val();

				if (!paymentgateway_id) {
					return false;
				}
				
				paycart.checkout.payment.getPaymentForm(paymentgateway_id);
			},			

		   /**
			*	Invoke to get payment form html 
			*	 @param int paymentgateway_id : payment gatway id
			*
			* 	If successfully complete request then call  
			*/
			getPaymentForm : function(paymentgateway_id)
			{
				if (!paymentgateway_id) {
					console.log('Payment Gateway required for fetching payment form html');
					return false;
				}

				var request = [];
				
				request['data'] = { 
									'paymentgateway_id'	:	paymentgateway_id, 
									'task' 				: 'getPaymentFormHtml'
								  };
				  
				request['success_callback']	= paycart.checkout.payment.afterFetchingPaymentForm;
				  
				paycart.checkout.getData(request);
				
			 	return true;
			},

		   /**
			*	Invoke to after fetching payment form   
			*/
			afterFetchingPaymentForm : function(data)
			{
				// Payment-form setup into payment div
		    	$('.payment-form-html').html(data['html']);

		    	// Payment-form action setup
		    	$('#payment-form-html').prop('action', data['post_url']); 
			},

		   /**
			*	Invoke to checkout cart (Cart will be locked)  
			*/
			onCheckout : function()
			{
				var request = [];
				
				request['data'] = { 'task' : 'checkout'};
				request['success_callback']	= paycart.checkout.payment.onPayNow;
				  
				paycart.checkout.getData(request);

				return true;
			},

		   /**
			*	Invoke to initiate Payment 
			*/
			onPayNow : function()
			{
				//disabled pay now button and payment-gateways
				$('#paycart-invoice-paynow, #pc-checkout-payment-gateway ').prop('disabled','disabled');
				
				// Submit Form
		    	$('#payment-form-html').submit();
			}
		
		};
				
	})(paycart.jQuery);


</script>