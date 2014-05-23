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
		paycart.checkout = {};

		/**
		 * -----------------------------------------------------------
		 * Checkout > Change Steps
		 *	
		 * 1. Change (step_ready)
		 * 	Invoke when you need to change step
		 * 	step_ready : current step
		 * 
		 * -----------------------------------------------------------
		 */
		paycart.checkout.step =
		{
			change : function(step_ready)
			{
				// @PCTODO: do not use hard-coding
				// availble steps 
				var steps 		= [ <?php echo implode(',', $steps); ?> ];
				var class_name	= '';

				// set incomplet class to all (remove previous class and add default classes);
				paycart.jQuery('.pc-checkout-step').removeClass('text-success').addClass('muted');
				
				for (i=0; i<steps.length; i++) {

					class_name = '.pc-checkout-step-'+steps[i];

					paycart.jQuery(class_name).removeClass('muted');
					
					if (step_ready == steps[i]){
						// active mark it
						//@PCTODO:: Acive mark
						paycart.jQuery(class_name).addClass('text-success');
						break; 
					}

					//Previous step mark completed
					paycart.jQuery(class_name).addClass('text-success');
				}
			}
				
		};

		/**
		 * -----------------------------------------------------------
		 * Checkout > Submit
		 *
		 * 1 do  
		 *	Submit current active step form
		 * 
		 * 2. success(data)
		 * 	Invoke when successfully complete current step.
		 * 	data : JSON object
		 * 
		 * 3. notification(data)
		 * 	Invoke when anything is going wrong with current step
		 * 	data : JSON object
		 * 
		 * -----------------------------------------------------------
		 */
		
		paycart.checkout.submit = 
		{
			do : function()
			{
				// get all form data for post	
				var postData 	= $("#pc-checkout-form").serializeArray();
				var link  		= 'index.php?option=com_paycart';
	
				console.log('paycart.checkout.submit.do');
	
				//@PCTODO :: Display Spinner{ request is processing }  
	
	
				paycart.ajax.go(link, postData);
	
				return false;
					
			},
	
			/**
			 * data is json object 
			 */
			success: function(data)
			{
				console.log('paycart.checkout.submit.success : start');
	
				// replace string
				$(".pc-checkout-step-html").html(data.html);
	
				console.log('paycart.checkout.submit.success : end');
			},
	
			/**
			 * data is json object 
			 */
			notification :function(data)
			{
				console.log('paycart.checkout.submit.error :: ' + data);
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
					
					paycart.checkout.submit.do();

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
			
		
	})(paycart.jQuery);


</script>