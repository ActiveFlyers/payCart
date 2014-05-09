<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<?php
	$current = 4;
?>


<div class='pc-checkout-wrapper clearfix'>
	<?php include_once '_checkout-state.php';?>
	 <div class="pc-checkout-payment row-fluid">
		 <form>
		 	<h3>Payment</h3>
		 	 <fieldset>
				<label>Payment Mode*</label>
				<select name="paymentmode" class="span12">
					<option value="creditcard" selected="selected">Credit Card</option>
					<option value="paypal" >PayPal</option>
					<option value="banktransfer" >Bank Transfer</option> 
				</select>
				<span class="hide help-block">Example block-level help text here.</span>
				
				<label>Credit Card Number*</label>
				<input type="number" name="zipcode" placeholder="xxxx-xxxx-xxxx-xxxx" class="input-block-level">
				<span class="hide help-block">Example block-level help text here.</span>

				<label>Expiry Date*</label>
				<div class="span12 clearfix">
					<select name="expiry_mm" class="pc-grid-4">
						<option value="" selected="selected">MM</option>
						<option value="01" >01</option>
						<option value="02" >02</option>
						<option value="03" >03</option>
						<option value="04" >04</option>
						<option value="05" >05</option>
						<option value="06" >06</option>
						<option value="07" >07</option>
						<option value="08" >08</option>
						<option value="09" >09</option>
						<option value="10" >10</option>
						<option value="11" >11</option>
						<option value="12" >12</option>
					</select>
					
					<select name="expiry_yyyy" class="pc-grid-4">
						<option value="" selected="selected">YYYY</option>
						<option value="2014" >2014</option>
						<option value="2015" >2015</option>
						<option value="2016" >2016</option>
						<option value="2017" >2017</option>
						<option value="2018" >2018</option>
						<option value="2019" >2019</option>
					</select>
				</div>
				
				<label>CVV Number*</label>
				<input type="number" name="cvv" class="pc-grid-3" placeholder="xxx">
				<span class="small pc-grid-6 muted">
					<i class="fa fa-credit-card fa-2x pull-left"></i>
				 	Last 3 digits on back of your card
				</span>
				
				<span class="hide help-block">error message.</span>
				
			</fieldset>
			

			<button type="submit" class="pc-whitespace btn btn-block btn-large btn-primary">Pay Now</button>
			 
			 
		 </form>
	 </div>	 
</div>
<?php

