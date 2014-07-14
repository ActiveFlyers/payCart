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
	
				if($("#pc-checkout-form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
					// Our validation work on submit call therefore first we will ensure that form is not properly fill 
					// then we will call submit method. So proper msg display and focus on required element. 
					//$("#pc-checkout-form").submit();
					console.log('Validation fail');
				}
				
				console.log('paycart.checkout.process');
	
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
						    		var callback = response['callback'];
						    		(eval(callback))(response);
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

		paycart.checkout.buyeraddress = 
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

					$(to_selector).val($(this).val());
				});

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
									'selector_index'	: selector_index,
									'callback'			: 'paycart.checkout.buyeraddress.fill_address_values'
								  };
				  
				paycart.checkout.getData(request);
				
			},

		   /**
			* Invoke to fill address values into selected address {either billing or shipping}
			*/
			fill_address_values : function(data) 
			{
				// paycart_form[billing] or paycart_form[shipping] 
				var selecor_name 	= 'paycart_form['+data['selector_index'] +']';
				
				for (index in data['buyeraddress']) {
					$('[name="'+selecor_name+'['+index+']"]').val(data['buyeraddress'][index]);
				}
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
				console.log('paycart.checkout.address.do');

				paycart.checkout.process();

				return false;					
			}
		};
				
	})(paycart.jQuery);


</script>