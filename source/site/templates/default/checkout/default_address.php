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
		
<!--	Billing Address -->
	 	<div class="span6 pc-checkout-billing ">
		 	<h3>Billing Info</h3>
		 	
		 	 <fieldset>
		 	 
		 	 	<label class="control-label required" >ZIP code</label>
				<input type="text" name="paycart_form[billing][zipcode]" class="input-block-level" >
				<span class="hide help-block">Example block-level help text here.</span>

				<label class="control-label required">Full Name</label>
				<input type="text" name="paycart_form[billing][to]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label class="control-label required">Phone Number</label>
				<input type="text" name="paycart_form[billing][phone1]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Select Country</label>
				<select name="paycart_form[billing][country]" class="span12">
					<option value="" selected="selected">Select Country </option> 
					<?php include '_options_country.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Select City</label>
				<select name="paycart_form[billing][state]" class="span12">
					<option value="" selected="selected">Select State</option> 
					<?php include '_options_state.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Town/City</label>
				<input type="text" name="paycart_form[billing][city]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Delivery Address</label>
				<span class="hide help-block">Example block-level help text here.</span>
				<textarea class="input-block-level" rows="3" name="paycart_form[billing][address]"></textarea>
			</fieldset>
		</div>
		
<!--	Shipping Address	-->
		<div class=" span6 pc-checkout-shipping clearfix">
		 	<h3>Shipping Info</h3>
		 	
		 	<label class="checkbox">
				<input 	id='billing_to_shipping' type="checkbox" 
						checked="checked"		 name="paycart_form[billing_to_shipping]"
						onClick="paycart.checkout.address.billing_to_shipping();"
						value='true'
				> Same as Billing address
			</label>
			
		 	<fieldset>
				
				<label  class="control-label required">ZIP code</label>
				<input type="text" name="paycart_form[shipping][zipcode]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>

				<label  class="control-label required">Full Name</label>
				<input type="text" name="paycart_form[shipping][to]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Phone Number</label>
				<input type="text" name="paycart_form[shipping][phone1]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Select Country</label>
				<select name="paycart_form[shipping][country]" class="span12">
					<option value="" selected="selected">Select Country </option> 
					<?php include '_options_country.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Select State</label>
				<select name="paycart_form[shipping][state]" class="span12">
					<option value="" selected="selected">Select State</option> 
					<?php include '_options_state.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Town/City</label>
				<input type="text" name="paycart_form[shipping][city]" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label  class="control-label required">Delivery Address</label>
				<span class="hide help-block">Example block-level help text here.</span>
				<textarea class="input-block-level" rows="3" name="paycart_form[shipping][address]"></textarea>
				
			</fieldset>
			
		</div>
		
<!--	Continue Checkout	-->
		<div class="clearfix">
			
			<button type="button" onClick="paycart.checkout.address.do();" 
					class="pc-whitespace btn btn-block btn-large btn-primary">
			Continue <i class="fa fa-angle-double-right"></i>
			</button>
			
		</div>
		
		<input	type="hidden"	name='step_name' value='address' />		
	</div>
		
	<script>
			
		(function($){
			paycart.checkout.address = 
			{
				copy : 
				{
					to_shipping	:	function()
					{
						var regExp = /\[(\w*)\]$/;
						
						$('[name^="paycart_form[billing]"]').each(function() {
		
							// get index
							var matches = this.name.match(regExp);
		
							if (!matches) {
								return false;
							}
		
							//matches[1] contains the value between the Square Bracket
							var index = matches[1];
							$('[name^="paycart_form[shipping]['+index+']"]').val($(this).val())
						});
						console.log('copy billing to shipping');
					}
				},
			
				// Copy billing to shipping				
				billing_to_shipping : function()
				{
					// Checked billing to shipping 
					if( $('#billing_to_shipping').prop('checked') == true ) { 

						paycart.checkout.address.copy.to_shipping();
						
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

				do : function()
				{
					console.log('paycart.checkout.address.do');

					//Before Submit Copy billing to shipping address
					if ( $('#billing_to_shipping').prop('checked') == true ) { 
						paycart.checkout.address.copy.to_shipping();
					}
					
					paycart.checkout.submit.do();

					return false;					
				},

				
			};

			paycart.checkout.address.billing_to_shipping();
			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
	
	</script>	 

<?php

