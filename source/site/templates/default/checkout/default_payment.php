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
$amount = 100;
?>


<div class='pc-checkout-wrapper clearfix '>
	
	 <div class="pc-checkout-payment" id="accordion-parent" >

		<div class="row-fluid ">
			<h3><strong><?php echo JText::_('Payment');	?></strong></h3>
		</div>
		
		<div class="row-fluid">

<!-- 	Payment Selection Part		-->
			<div class="span4">
			
				<!-- Payment detail				-->
				<div class="row-fluid">
					<blockquote>
						<p class="muted"><?php echo JText::_('Payable Amount')?></p>
						<p class="text-error"><?php echo $currency_html; ?>100</p>
					</blockquote>
				</div>
				
				<!-- Payment Gateway Selection				-->
				<div class="row-fluid">
					<label> <?php echo JText::_('Payment Method'); ?></label>
					<select name="payment_menthod" id="pc-checkout-payment-gateway" class ="input-block-level" onchange="paycart.checkout.payment.onChangePaymentgateway()">
					<?php 
						foreach ($payment_gateway as $gateway_id => $gateway_details) :
					?>	
							<option value=<?php echo $gateway_id; ?> > <?php echo $gateway_details->title; ?> </option>
						
					<?php 
						endforeach;	
					?>
					</select>
				</div>
				
			</div>

<!--	Payment gateway html display here-->
			<div class="span6">
				<div class="row-fluid payment-form-html-div ">
					<form class="payment-form-html" id="payment-form-html" method="post">
						<?php echo $payment_gateway_html; ?>
					</form>
				</div>
			</div>
			
		</div>
		
		

	 </div>	 
</div>

<script>

	(function($){
		
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

					var postData = { 'paymentgateway_id'	:	paymentgateway_id }
					
					$.ajax({
					    url: 'index.php?option=com_paycart&view=checkout&task=getPaymentFormHtml&format=json',
					    cache	: false,
						data	: postData,
					    dataType: 'json',
					    success: function( data ) {

							if( typeof data['message_type'] != "undefined" ) { 
								console.log ("Error:  " + data );
					    		return false;
							}

					    	// Payment-form setup into payment div
					    	$('.payment-form-html').html(data['html']);

					    	// Payment-form action setup
					    	$('#payment-form-html').prop('action', data['post_url']); 
					    },
					    error: function( data ) {
					    	console.log ("Error:  " + data );
					    }
					  });

					  return true;
					
				},

			   /**
				*	Invoke to checkout cart 
				*		- If successfully complete request then start payment collection  
				*/
				onPaynow : function()
				{
					var postData = { 'task'	:	'checkout' }
					
					$.ajax({
					    url		: 'index.php?option=com_paycart&view=checkout&format=json',
					    cache	: false,
						data	: postData,
					    dataType: 'json',
					    success: function( data ) {

							if( typeof data['message_type'] != "undefined" ) { 
								console.log ("Error:  " + data );
					    		return false;
							}

							// @PCTODO:: unused
					    	if( typeof data['callback'] != "undefined" ) { 
								data['callback']();
					    		return true;
							}

					    	console.log ("Success:  " + data );

					    	// Submit Form
					    	$('#payment-form-html').submit();
					    	
					    },
					    error: function( data ) {
					    	console.log ("Error:  " + data );
					    }
					  });

					  return true;
					
				}
			
			};

		paycart.checkout.step.change('<?php echo $step_ready; ?>');
		paycart.checkout.payment.getPaymentForm($('#pc-checkout-payment-gateway').val());

	})(paycart.jQuery);
</script>
<?php

