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
?>

<script>


	(function($) {

	 	

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

			
		paycart.cart = {};
	    paycart.cart.product = {};
			
	    paycart.cart.product.get = function (){					
			var link = 'index.php?option=com_paycart&view=cart';		
			paycart.ajax.go(link);		
			return false;
		};
		
		// update product-quantity into cart	
	    paycart.cart.product.updateQuantity = function(productId){	    	    		
	    	    		var quantity = $('.pc-cart-quantity-'+productId).val();
	    	    		var request = [];
	    	    		request['url'] 	= 'index.php?option=com_paycart&view=cart&task=updateProductQuantity';
						request['data']	= {'product_id' : productId, 'quantity' : quantity};
						request['success_callback']	= paycart.cart.product.response;
						paycart.cart.request(request);
						
						return false;
	    	    	};

	   	paycart.cart.product.response = function(response){
				   		if(response.valid){
				   			paycart.cart.product.get();

				   			// after validation invoke trigger
				   			paycart.event.cart.updateproduct();
							return true;
						}		
						
						var prevQuantity = response.prevQuantity;
						var allowedQuantity = response.allowedQuantity;
						var productId 	 = response.productId;
						var message = '';
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
						$('.pc-cart-quantity-error-'+productId).text(message);
	    				$('.pc-cart-quantity-'+productId).val(prevQuantity);
	    	    	};
	    						
	   	paycart.cart.product.remove = function(productId){
				   		var request = [];
				   		request['url'] 	= 'index.php?option=com_paycart&view=cart&task=removeproduct';
						request['data']	= {'product_id' : productId};
						request['success_callback']	= paycart.cart.product.remove.response;
						paycart.cart.request(request);
						
						return false;
	    	      	};
	    	      		        
	   	paycart.cart.product.remove.response = function(response){				
						if(response.valid){
							paycart.cart.product.get();

							// after validation invoke trigger
				   			paycart.event.cart.updateproduct();
				   			
							return true;
						}		
						
						var productId 	 = response.productId;
						var message = '';
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
						$('.pc-cart-remove-error-'+productId).text(message);						
					};  	

		
		paycart.cart.request = function(request){
						var url = ( typeof request['url'] == "undefined"  ) 
			    					? 'index.php?option=com_paycart&view=cart'
									: request['url'];
		
						// json formate nd call back
						url = url+'&format=json';
						
						$.ajax({
						    url		: ( typeof request['url'] == "undefined"  ) 
							    		? 'index.php?option=com_paycart&view=cart&format=json'
			    						: request['url']+'&format=json',
			    						
						    cache	: ( typeof request['cache'] == "undefined" ) 
				    					? false
										: request['cache'],
										
							data	: ( typeof request['data'] == "undefined" ) 
							    		? {}
										: request['data'],
							type 	: ( typeof request['type'] == "undefined" ) 
				    					? 'POST'
										: request['type'],
						    success : function( response ) {
		
										//console.log ("Success:  " + response );
		
										//clear data (remove warnings and error)
										response = rb.ajax.junkFilter(response);									
				
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

								    	if( typeof response['valid'] != "undefined" && response['valid'] == false) { 
											console.log ( {" response contain error :  " : response } );
								    		return false;
										}
										
										return true;
								    },
		
								error : function( response ) {
		
								    	console.log ({"Error on fetching JSON data :  " :response} );
		
								    	return response;
								    }
						  });
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
		paycart.cart.login = {};
		paycart.cart.login.get = function (){					
						var link = 'index.php?option=com_paycart&view=cart&task=login';		
						paycart.ajax.go(link);		
						return false;
					};
				
		paycart.cart.login.init = function(){
						paycart.formvalidator.initialize('form.pc-form-validate');
						
						// initialize screen interface
						//1. on click on guest checkout mode
						paycart.cart.login.setEmailCheckout(true);
						
						$('#paycart_cart_login_emailcheckout_1').click(function(){
								paycart.cart.login.setEmailCheckout(true)
							});
						
						$('#paycart_cart_login_emailcheckout_0').click(function(){
							paycart.cart.login.setEmailCheckout(false)
						});
					};
				
		paycart.cart.login.do = function(){
						//console.log('paycart.cart.login.do');
						if(paycart.formvalidator.isValid('#pc-checkout-form')){
							// get all form data for post	
							var postData 	= $("#pc-checkout-form").serializeArray();
							var link  		= 'index.php?option=com_paycart&view=cart&task=login';
							paycart.ajax.go(link, postData);
						}
						return false;					
					};

		paycart.cart.login.error = function(errors){
						var error_mapper = {'email' : '#paycart_cart_login_email', 'password': '#paycart_cart_login_password', 'header' : '#paycart_cart_login'};
						
						for (var index in errors){
							paycart.formvalidator.handleResponse(false, $(error_mapper[errors[index].for]), errors[index].message_type, errors[index].message);
						}
					};

		paycart.cart.login.setEmailCheckout = function(is_guest){
						//default is guest mode
						if(is_guest){
							$('[data-pc-emailcheckout="show"]').show();
							$('[data-pc-emailcheckout="hide"]').hide();
						}else{
							$('[data-pc-emailcheckout="show"]').hide();
							$('[data-pc-emailcheckout="hide"]').show();
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

		paycart.cart.address = {};
		paycart.cart.address.copy = function(from, to){
						var regExp 			=	/\[(\w*)\]$/, 
							from_name 		=	'paycart_cart_address['+from +']',
							to_name 		=	'paycart_cart_address['+to +']',
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
						
						paycart.cart.address.setAddress(data);
										
						//console.log('copy '+from+' to '+to);
					};

		paycart.cart.address.get = function (){					
						var link = 'index.php?option=com_paycart&view=cart&task=address';		
						paycart.ajax.go(link);		
						return false;
					};
			
		paycart.cart.address.init = function(){
						paycart.formvalidator.initialize('form.pc-form-validate');
						// if billing to shipping already checked then need to copy all address
						paycart.cart.address.onBillingToShipping();
					};

		   /**
			* Invoke to get specific address detail and put into input containers
			* 
			* selected_address_id	: Selected address value 
			* selector_index 		: Either billing or shipping
			* 
			*/
		paycart.cart.address.onSelect = function(selected_address_id, selector_index){
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
						  request['success_callback']	=	paycart.cart.address.setAddress;
						  
						paycart.cart.request(request);
					};

		/**
		 * Invoke to fill address values into selected address {either billing or shipping}
		 */
		paycart.cart.address.setAddress = function(data){
						// paycart_cart_address[billing] or paycart_cart_address[shipping] 
						var selecor_name = 'paycart_cart_address['+data['selector_index'] +']', 
							state_value	= 0 ;
						
						for (index in data['buyeraddress']) {
							$('[name="'+selecor_name+'['+index+']"]').val(data['buyeraddress'][index]);
		
							if ('state_id' == index) {
								state_value 	=	data['buyeraddress'][index];
							}

						<?php if (!$is_platform_mobile) : ?>

							if ( $('[name="'+selecor_name+'['+index+']"]').is('select') ) {
								$('[name="'+selecor_name+'['+index+']"]').trigger("liszt:updated");
							}

						<?php endif; ?>
							
						}
		
						// special treatment for country and state value
						$('[name="'+selecor_name+'[country_id]"]').trigger('change', {'state_id' : state_value});
						
					};
			
		
		// Copy billing to shipping				
		paycart.cart.address.onBillingToShipping = function(){
						// Checked billing to shipping 
						if( $('#billing_to_shipping').prop('checked') == true ) { 
		
							paycart.cart.address.copy('billing', 'shipping');
		
							$('.pc-checkout-shipping-html').fadeOut();
		
							return true;
						} 
		
						// unchecked billing to shipping		
						// Open shipping address deatil field set 
						$('.pc-checkout-shipping-html').fadeIn();
						
						return true;
					};

		/**
		 * Invoke to continue checkout flow
		 */
		paycart.cart.address.onContinue	= function(){
						//Before Submit Copy billing to shipping address
						if ( $('#billing_to_shipping').prop('checked') == true ) { 
							paycart.cart.address.copy('billing', 'shipping');
						}
		
						paycart.cart.address.do();
					};

			/**
			 * Invoke to submit to get action
			 */
		paycart.cart.address.do = function(){
						//console.log('paycart.cart.address.do');
						if(paycart.formvalidator.isValid('#pc-checkout-form')){
							// get all form data for post	
							var postData 	= $("#pc-checkout-form").serializeArray();
							var link  		= 'index.php?option=com_paycart&view=cart&task=address';
							paycart.ajax.go(link, postData);
						}
		
						return false;					
					};

		paycart.cart.address.error = function(errors){
						for (var index in errors){
							paycart.formvalidator.handleResponse(false, $('#'+errors[index].for), errors[index].message_type, errors[index].message);
						}
					};


	   /**
		*-----------------------------------------------------------
		* Checkout > Order confirm Screen 
		*-------------------------------------------------------------
		*/
		paycart.cart.confirm = {};					
		paycart.cart.confirm.get = function (){					
						var link = 'index.php?option=com_paycart&view=cart&task=confirm';		
						paycart.ajax.go(link);		
						return false;
					};
			
		paycart.cart.confirm.do = function(){
						//console.log('paycart.cart.address.do');
						if(paycart.formvalidator.isValid('#pc-checkout-form')){
							// get all form data for post	
							var postData 	= $("#pc-checkout-form").serializeArray();
							var link  		= 'index.php?option=com_paycart&view=cart&task=confirm';
							paycart.ajax.go(link, postData);
						}
		
						return false;					
					};

		// update product-quantity into cart
		paycart.cart.confirm.onChangeProductQuantity = function(product_id)	{
						var request, product_quantity;
		
						product_quantity = $('#pc-checkout-quantity-'+product_id).val();
						// @PCTODO:: Properly validate it
						
						// get all form data for post	
						request = [];
						request['url'] 	= 'index.php?option=com_paycart&view=cart&task=updateProductQuantity';
						request['data']	= {'product_id' : product_id, 'quantity' : product_quantity};
						request['success_callback']	= paycart.cart.confirm.onChangeProductQuantity.response;
						paycart.cart.request(request);
						
						return false;				
					};

		paycart.cart.confirm.onChangeProductQuantity.response = function(response){				
						if(response.valid){
							paycart.cart.confirm.get();

							// after validation invoke trigger
				   			paycart.event.cart.updateproduct();
							return true;
						}		
						
						var prevQuantity = response.prevQuantity;
						var allowedQuantity = response.allowedQuantity;
						var productId 	 = response.productId;
						var message = '';
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
						$('#pc-checkout-quantity-error-'+productId).text(message);
						$('#pc-checkout-quantity-'+productId).val(prevQuantity);
					};
		
		paycart.cart.confirm.onRemoveProduct = function(product_id)	{						
						var request = [];
						request['url'] 	= 'index.php?option=com_paycart&view=cart&task=removeproduct';
						request['data']	= {'product_id' : product_id};
						request['success_callback']	= paycart.cart.confirm.onRemoveProduct.response;
						paycart.cart.request(request);
						
						return false;
					};
					
		paycart.cart.confirm.onRemoveProduct.response = function(response){				
						if(response.valid){
							paycart.cart.confirm.get();

							// after validation invoke trigger
				   			paycart.event.cart.updateproduct();
				   			
							return true;
						}		
						
						var productId 	 = response.productId;
						var message = '';
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
						$('#pc-checkout-remove-error-'+productId).text(message);						
					};

		// update product-quantity into cart
		paycart.cart.onApplyPromotionCode = function(){
						var request = [];
						request['url'] 	= 'index.php?option=com_paycart&view=cart&task=applyPromotion';
						request['data']	= {'promotion_code' : $('#paycart-promotion-code-input-id').val()};
						request['success_callback']	= paycart.cart.onApplyPromotionCode.response;
						paycart.cart.request(request);
						
						return false;
					};
					
		paycart.cart.onApplyPromotionCode.response = function(response){				
						if(response.valid){
							paycart.cart.confirm.get();
							return true;
						}
						
						var message = '';
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
						$('#pc-checkout-promotioncode-error').text(message);						
					};

		paycart.cart.confirm.error = function(errors){
						var error_mapper = {'header' : '#paycart_cart_confirm'};
						
						for (var index in errors){
							paycart.formvalidator.handleResponse(false, $(error_mapper[errors[index].for]), errors[index].message_type, errors[index].message);
						}
					};

		paycart.cart.confirm.onChangeShipping = function(shippingMethod)	{						
						var request = [];
						request['url'] 	= 'index.php?option=com_paycart&view=cart&task=changeShippingMethod';
						request['data']	= {'shipping' : shippingMethod};
						request['success_callback']	= paycart.cart.confirm.onChangeShipping.response;
						paycart.cart.request(request);
						
						return false;
					};

		paycart.cart.confirm.onChangeShipping.response = function(response){				
						if(response.valid){
							paycart.cart.confirm.get();
							return true;
						}		
						
						var productId 	 = response.productId;
						var message = '';
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
						$('#pc-cart-shipping-error'+productId).text(message);						
					};
					
	   /**
		*-----------------------------------------------------------
		* Checkout > Payment Screen 
		*-------------------------------------------------------------
		*/
		paycart.cart.gatewaySelection = {}; 
		paycart.cart.gatewaySelection.onChangePaymentgateway = function(){
						var paymentgateway_id = $('#pc-checkout-payment-gateway').val();
		
						if (!paymentgateway_id) {
							return false;
						}
						
						paycart.cart.getPaymentForm(paymentgateway_id);
					};
								
		paycart.cart.gatewaySelection.error = function(errors){
						var error_mapper = {'payment-gateway' : '#pc-checkout-payment-gateway'};
						
						for (var index in errors){
							paycart.formvalidator.handleResponse(false, $(error_mapper[errors[index].for]), errors[index].message_type, errors[index].message);
						}	
					};

	   /**
		*	Invoke to get payment form html 
		*	 @param int paymentgateway_id : payment gatway id
		*
		* 	If successfully complete request then call  
		*/
		paycart.cart.getPaymentForm = function(paymentgateway_id){
						if (!paymentgateway_id) {
							console.log('Payment Gateway required for fetching payment form html');
							return false;
						}
		
						var request = [];
						
						request['data'] = { 
											'paymentgateway_id'	:	paymentgateway_id, 
											'task' 				: 'paymentForm'
										  };
						  
						request['success_callback']	= paycart.cart.getPaymentForm.response;
						  
						paycart.cart.request(request);
						
					 	return true;
					};
					
		paycart.cart.getPaymentForm.response = function(response){
						if(response.valid){
							// Payment-form setup into payment div
					    	$('.payment-form-html').html(response['html']);
			
					    	// Payment-form action setup
					    	$('#payment-form-html').prop('action', response['post_url']); 
							return true;
						}
						
						for(var index in response.errors){
							if(response.errors.hasOwnProperty(index) == false){
								continue;
							}
							message += "\n" + response.errors[index].message;
						}
					};

	   /**
		*	Invoke to checkout cart (Cart will be locked)  
		*/
		paycart.cart.order = function(){
						var request = [];
						
						request['data'] = { 'task' : 'order'};
						request['success_callback']	= paycart.cart.initiatePayment;
						  
						paycart.cart.request(request);
		
						return false;
					};

	   /**
		*	Invoke to initiate Payment 
		*/
		paycart.cart.initiatePayment = function(){
						//disabled pay now button and payment-gateways
						$('#paycart-invoice-paynow, #pc-checkout-payment-gateway ').prop('disabled','disabled');
						
						// Submit Form
				    	$('#payment-form-html').submit();
					};	

		
	})(paycart.jQuery);


</script>
