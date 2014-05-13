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
	$current = 1;
	;
?>

<script>
paycart.queue.push('paycart.checkout.login.init()');
</script>

<div class='pc-checkout-wrapper clearfix'>
	<?php include_once '_checkout-state.php';?>
	<div class="pc-checkout-login row-fluid">
		<form>
			<h3>Login</h3>
			<fieldset id="paycart_form_guestcheckout" class="radio input-lg pc-whitespace">
					<input type="radio" id="paycart_form_guestcheckout_1" name="paycart_form[guestcheckout]"  value="1" checked="checked">
					<label for="paycart_form_guestcheckout_1" class="radio">Continue as guest</label>
				
					<input type="radio" id="paycart_form_guestcheckout_0"  name="paycart_form[guestcheckout]" value="0">
					<label for="paycart_form_guestcheckout_0" class="radio">Existing Customer</label>
					
					<span class="hide help-block">Example block-level help text here.</span>
			</fieldset>
				
			<fieldset>
				 <div class="control-group">	
			 		<label>Email</label>
			 		<div class="controls">
						<input type="text" name="email" class="input-block-level" placeholder="Enter email address (required)">
						<span class="hide help-block">Example block-level help text here.</span>
					</div>
				 </div>
				 <div class="control-group" data-pc-guestcheckout="hide">	
					<label>Password</label>
					<div class="controls">
						<input type="text" name="password" class="input-block-level">
						<span class="hide help-block">Example block-level help text here.</span>
					</div>
				</div>
		 	</fieldset>
		 	<button type="submit" class="pc-whitespace btn btn-block btn-large btn-primary">Continue <i class="fa fa-angle-double-right"></i> </button>
		 	
		 	<p class="small muted text-center" data-pc-guestcheckout="show">You can opt for register an account after completing this purchase.</p>
		 </form>
	 </div>	 
</div>

<?php

