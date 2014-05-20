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


	 <div class="pc-checkout-shipping row-fluid">
		 	<h3>Shipping Info</h3>
		 	 <fieldset>
				<label>ZIP code*</label>
				<input type="text" name="zipcode" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>

				<label>Full Name*</label>
				<input type="text" name="fullname" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Phone Number*</label>
				<input type="text" name="phonenumber" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<select name="Country" class="span12">
					<option value="" selected="selected">Select Country *</option> 
					<?php include '_options_country.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<select name="State" class="span12">
					<option value="" selected="selected">Select State*</option> 
					<?php include '_options_state.php'?> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Town/City*</label>
				<input type="text" name="city" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Delivery Address*</label>
				<span class="hide help-block">Example block-level help text here.</span>
				<textarea class="input-block-level" rows="3"></textarea>
			</fieldset>
			
			<h3>Billing Address</h3>
			<label class="checkbox">
				<input type="checkbox" checked="checked"> Same as shipping address
			</label>

			<button type="submit" class="pc-whitespace btn btn-block btn-large btn-primary">Deliver To This Address</button>
			 
		</div>
		
		<script>
				
			(function($){
				paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			})(paycart.jQuery);
		
		</script>	 
<?php

