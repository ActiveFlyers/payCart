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


	 <div class="pc-checkout-address clearfix">
		
<!--	Shipping Address -->
	 	<div class="pc-checkout-shipping ">
		 	<h3>Shipping Info</h3>
		 	 <fieldset>
				<label>ZIP code*</label>
				<input type="text" name="paycart_form[shipping][zipcode]" class="input-block-level" >
				<span class="hide help-block">Example block-level help text here.</span>

				<label>Full Name*</label>
				<input type="text" name="paycart_form[shipping][to]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Phone Number*</label>
				<input type="text" name="paycart_form[shipping][phone1]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<select name="paycart_form[shipping][country]" class="span12">
					<option value="" selected="selected">Select Country *</option> 
					<?php include '_options_country.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<select name="paycart_form[shipping][state]" class="span12">
					<option value="" selected="selected">Select State*</option> 
					<?php include '_options_state.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Town/City*</label>
				<input type="text" name="paycart_form[shipping][city]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Delivery Address*</label>
				<span class="hide help-block">Example block-level help text here.</span>
				<textarea class="input-block-level" rows="3" name="paycart_form[shipping][address]"></textarea>
			</fieldset>
		</div>
		
<!--	Billing Address	-->
		<div class="pc-checkout-billing clearfix">
		 	<h3>Billing Address</h3>
		 	
		 	<fieldset>
				
				<label>ZIP code*</label>
				<input type="text" name="paycart_form[billing][zipcode]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>

				<label>Full Name*</label>
				<input type="text" name="paycart_form[billing][to]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Phone Number*</label>
				<input type="text" name="paycart_form[billing][phone1]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<select name="paycart_form[billing][country]" class="span12">
					<option value="" selected="selected">Select Country *</option> 
					<?php include '_options_country.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<select name="paycart_form[billing][state]" class="span12">
					<option value="" selected="selected">Select State*</option> 
					<?php include '_options_state.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Town/City*</label>
				<input type="text" name="paycart_form[billing][city]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Delivery Address*</label>
				<span class="hide help-block">Example block-level help text here.</span>
				<textarea class="input-block-level" rows="3" name="paycart_form[billing][address]"></textarea>
				
			</fieldset>
			
		</div>
		
<!--	Continue Checkout	-->
		<div class="clearfix">
			
			<label class="checkbox">
				<input 	id='shipping_to_billing' type="checkbox" 
						checked="checked"		 name="paycart_form[shipping_to_billing]"
						onClick="paycart.checkout.address.shipping_to_billing();"
						value='true'
				> Same as shipping address
			</label>
		
			<button type="button" onClick="paycart.checkout.address.do();" class="pc-whitespace btn btn-block btn-large btn-primary">Deliver To This Address</button>
			
		</div>
		
		<input	type="hidden"	name='step_name' value='address' />		
	</div>
		
	<script>
			
		(function($){
			paycart.checkout.address = 
			{
				copy : 
				{
					to_billing	:	function()
					{
						var regExp = /\[(\w*)\]$/;
						
						$('[name^="paycart_form[shipping]"]').each(function() {
		
							// get index
							var matches = this.name.match(regExp);
		
							if (!matches) {
								return false;
							}
		
							//matches[1] contains the value between the Square Bracket
							var index = matches[1];
							$('[name^="paycart_form[billing]['+index+']"]').val($(this).val())
						});
						console.log('copy shipping to billing');
					}
				},
			
				// Copy Shipping to Billing				
				shipping_to_billing : function()
				{
					// Checked Shipping to billing 
					if( $('#shipping_to_billing').prop('checked') == true ) { 

						paycart.checkout.address.copy.to_billing();
						
						$('.pc-checkout-billing fieldset:first').fadeOut();

						return true;
					} 

					// unchecked Shipping to billing 
					
					// delete all billing input values
					$('[name^="paycart_form[billing]"]').val('');

					// Open billing address deatil field setfor
					$('.pc-checkout-billing fieldset:first').fadeIn();
					
					console.log('delete input from billing');

					return true;
				},

				do : function()
				{
					console.log('paycart.checkout.address.do');

					//Before Submit Copy shipping to billing address
					if ( $('#shipping_to_billing').prop('checked') == true ) { 
						paycart.checkout.address.copy.to_billing();
					}
					
					paycart.checkout.submit.do();

					return false;					
				},

				
			};

			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
	
	</script>	 

<?php

