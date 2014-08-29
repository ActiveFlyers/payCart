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
	JHtml::_('formbehavior.chosen', 'select.pc-chozen');
 }
 
?>

<script>	

	(function($){
		
		
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

		paycart.checkout = {};
		paycart.checkout.getData = function(request){
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
		paycart.checkout.login = {};
		paycart.checkout.login.get = function (){					
						var link = 'index.php?option=com_paycart&view=cart&task=login';		
						paycart.ajax.go(link);		
						return false;
					};
				
		paycart.checkout.login.init = function(){
						paycart.formvalidator.initialize('form.pc-form-validate');
						
						// initialize screen interface
						//1. on click on guest checkout mode
						paycart.checkout.login.setEmailCheckout(true);
						
						$('#paycart_cart_login_emailcheckout_1').click(function(){
								paycart.checkout.login.setEmailCheckout(true)
							});
						
						$('#paycart_cart_login_emailcheckout_0').click(function(){
							paycart.checkout.login.setEmailCheckout(false)
						});
					};
				
		paycart.checkout.login.do = function(){
						//console.log('paycart.checkout.login.do');
						if(paycart.formvalidator.isValid('#pc-checkout-form')){
							// get all form data for post	
							var postData 	= $("#pc-checkout-form").serializeArray();
							var link  		= 'index.php?option=com_paycart&view=cart&task=login';
							paycart.ajax.go(link, postData);
						}
						return false;					
					};

		paycart.checkout.login.error = function(errors){
						for (var index in errors){
							paycart.formvalidator.handleResponse(false, $('#'+errors[index].for), errors[index].message_type, errors[index].message);
						}
					};

		paycart.checkout.login.setEmailCheckout = function(is_guest){
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

		paycart.checkout.address = {};
		paycart.checkout.address.copy = function(from, to){
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
						
						paycart.checkout.address.setAddress(data);
										
						//console.log('copy '+from+' to '+to);
					};

		paycart.checkout.address.get = function (){					
						var link = 'index.php?option=com_paycart&view=cart&task=address';		
						paycart.ajax.go(link);		
						return false;
					};
			
		paycart.checkout.address.init = function(){
						paycart.formvalidator.initialize('form.pc-form-validate');
						// if billing to shipping already checked then need to copy all address
						paycart.checkout.address.onBillingToShipping();
					};

		   /**
			* Invoke to get specific address detail and put into input containers
			* 
			* selected_address_id	: Selected address value 
			* selector_index 		: Either billing or shipping
			* 
			*/
		paycart.checkout.address.onSelect = function(selected_address_id, selector_index){
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
						  request['success_callback']	=	paycart.checkout.address.setAddress;
						  
						paycart.checkout.getData(request);
					};

		/**
		 * Invoke to fill address values into selected address {either billing or shipping}
		 */
		paycart.checkout.address.setAddress = function(data){
						// paycart_cart_address[billing] or paycart_cart_address[shipping] 
						var selecor_name = 'paycart_cart_address['+data['selector_index'] +']', 
							state_value	= 0 ;
						
						for (index in data['buyeraddress']) {
							$('[name="'+selecor_name+'['+index+']"]').val(data['buyeraddress'][index]);
		
							if ('state_id' == index) {
								state_value 	=	data['buyeraddress'][index];
							}
						}
		
						// special treatment for country and state value
						$('[name="'+selecor_name+'[country_id]"]').trigger('change', {'state_id' : state_value});
						
					};
			
		
		// Copy billing to shipping				
		paycart.checkout.address.onBillingToShipping = function(){
						// Checked billing to shipping 
						if( $('#billing_to_shipping').prop('checked') == true ) { 
		
							paycart.checkout.address.copy('billing', 'shipping');
		
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
		paycart.checkout.address.onContinue	= function(){
						//Before Submit Copy billing to shipping address
						if ( $('#billing_to_shipping').prop('checked') == true ) { 
							paycart.checkout.address.copy('billing', 'shipping');
						}
		
						paycart.checkout.address.do();
					};

			/**
			 * Invoke to submit to get action
			 */
		paycart.checkout.address.do = function(){
						//console.log('paycart.checkout.address.do');
						if(paycart.formvalidator.isValid('#pc-checkout-form')){
							// get all form data for post	
							var postData 	= $("#pc-checkout-form").serializeArray();
							var link  		= 'index.php?option=com_paycart&view=cart&task=address';
							paycart.ajax.go(link, postData);
						}
		
						return false;					
					};

		paycart.checkout.address.error = function(errors){
						for (var index in errors){
							paycart.formvalidator.handleResponse(false, $('#'+errors[index].for), errors[index].message_type, errors[index].message);
						}
					};


	   /**
		*-----------------------------------------------------------
		* Checkout > Order confirm Screen 
		*-------------------------------------------------------------
		*/
		paycart.checkout.confirm = {};					
		paycart.checkout.confirm.get = function (){					
						var link = 'index.php?option=com_paycart&view=cart&task=confirm';		
						paycart.ajax.go(link);		
						return false;
					};
			
		paycart.checkout.confirm.do = function(){
						//console.log('paycart.checkout.address.do');
						if(paycart.formvalidator.isValid('#pc-checkout-form')){
							// get all form data for post	
							var postData 	= $("#pc-checkout-form").serializeArray();
							var link  		= 'index.php?option=com_paycart&view=cart&task=confirm';
							paycart.ajax.go(link, postData);
						}
		
						return false;					
					};

		// update product-quantity into cart
		paycart.checkout.confirm.onChangeProductQuantity = function(product_id)	{
						var link, postData, product_quantity;
		
						product_quantity = $('#pc-checkout-quantity-'+product_id).val();
						// @PCTODO:: Properly validate it
						
						// get all form data for post	
						postData 	= {'product_id' : product_id, 'quantity' : product_quantity};
						link  		= 'index.php?option=com_paycart&view=cart&task=updateProductQuantity';
						paycart.ajax.go(link, postData);
						
						return false;				
					};

		paycart.checkout.confirm.onChangeProductQuantity.error = function(data){
						var response     = $.parseJSON(data);
						var prevQuantity = response.prevQuantity;
						var allowedQuantity = response.allowedQuantity;
						var productId 	 = response.productId;
						var message      = response.message;
						$('#pc-checkout-quantity-error-'+productId).text(message);
						$('#pc-checkout-quantity-'+productId).val(prevQuantity);
					};

		// update product-quantity into cart
		paycart.checkout.confirm.onRemoveProduct = function(product_id)	{
						var link, postData;
		
						// get all form data for post	
						postData 	= {'product_id' : product_id};
						link  		= 'index.php?option=com_paycart&view=checkout&task=removeproduct';
						paycart.ajax.go(link, postData);
						
						return false;
					};

		// update product-quantity into cart
		paycart.checkout.confirm.onApplyPromotionCode = function(){
						// get all form data for post	
						postData 	= {'promotion_code' : $('#paycart-promotion-code-input-id').val()};
						link  		= 'index.php?option=com_paycart&view=cart&task=applyPromotion';
						paycart.ajax.go(link, postData);
						
						return false;
					};

	   /**
		*-----------------------------------------------------------
		* Checkout > Payment Screen 
		*-------------------------------------------------------------
		*/
		paycart.checkout.payment = {}; 
		paycart.checkout.payment.onChangePaymentgateway = function(){
						var paymentgateway_id = $('#pc-checkout-payment-gateway').val();
		
						if (!paymentgateway_id) {
							return false;
						}
						
						paycart.checkout.payment.getPaymentForm(paymentgateway_id);
					};			

	   /**
		*	Invoke to get payment form html 
		*	 @param int paymentgateway_id : payment gatway id
		*
		* 	If successfully complete request then call  
		*/
		paycart.checkout.payment.getPaymentForm = function(paymentgateway_id){
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
					};

	   /**
		*	Invoke to after fetching payment form   
		*/
		paycart.checkout.payment.afterFetchingPaymentForm = function(data){
						// Payment-form setup into payment div
				    	$('.payment-form-html').html(data['html']);
		
				    	// Payment-form action setup
				    	$('#payment-form-html').prop('action', data['post_url']); 
					};

	   /**
		*	Invoke to checkout cart (Cart will be locked)  
		*/
		paycart.checkout.payment.onCheckout = function(){
						var request = [];
						
						request['data'] = { 'task' : 'lock'};
						request['success_callback']	= paycart.checkout.payment.onPayNow;
						  
						paycart.checkout.getData(request);
		
						return false;
					};

	   /**
		*	Invoke to initiate Payment 
		*/
		paycart.checkout.payment.onPayNow = function(){
						//disabled pay now button and payment-gateways
						$('#paycart-invoice-paynow, #pc-checkout-payment-gateway ').prop('disabled','disabled');
						
						// Submit Form
				    	$('#payment-form-html').submit();
					};
				
	})(paycart.jQuery);


</script>