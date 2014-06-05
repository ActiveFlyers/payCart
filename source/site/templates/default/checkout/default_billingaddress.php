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
	<?php if (isset($title) && !empty($title)): ?>
	 	<h3>Billing Info</h3>
	 <?php endif;?>
	 
	 <?php if (isset($shipping_to_billing) && !empty($shipping_to_billing)): ?>
	 	<label class="checkbox">
			<input 	id='billing_to_shipping' type="checkbox" 
					checked="checked"		 name="paycart_form[shipping_to_billing]"
					onClick="paycart.checkout.address.shipping_to_billing();"
					value='true'
			> Same as Shipping address
		</label>
	<?php endif; ?>
	
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

		
	<script>
			
		(function($){
					
			
		})(paycart.jQuery);
	
	</script>	 

<?php

