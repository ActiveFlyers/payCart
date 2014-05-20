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
	<div class="pc-checkout-login row-fluid">
		
		<h3>Login</h3>

		<fieldset class="radio input-lg pc-whitespace">
			<input type="radio" id="paycart_form_emailcheckout_1" name="paycart_form[emailcheckout]"  value="1" checked="checked">
			<label for="paycart_form_emailcheckout_1" class="radio">Continue as guest</label>
				
			<input type="radio" id="paycart_form_emailcheckout_0"  name="paycart_form[emailcheckout]" value="0">
			<label for="paycart_form_emailcheckout_0" class="radio">Existing Customer</label>
			
			<span class="hide help-block">Example block-level help text here.</span>
		</fieldset>
				
		<fieldset>
			 <div class="control-group">	
		 		<label>Email</label>
		 		<div class="controls">
					<input type="text" name="paycart_form[email]" class="input-block-level" placeholder="Enter email address (required)">
					<span class="hide help-block">Example block-level help text here.</span>
				</div>
			 </div>
			 <div class="control-group" data-pc-emailcheckout="hide">	
				<label>Password</label>
				<div class="controls">
					<input type="password" name="paycart_form[password]" class="input-block-level">
					<span class="hide help-block">Example block-level help text here.</span>
				</div>
			</div>
	 	</fieldset>
	 	
		<button type="button" onClick="paycart.checkout.login.do();" class="pc-whitespace btn btn-block btn-large btn-primary">Continue <i class="fa fa-angle-double-right"></i> </button>
		 	
		<p class="small muted text-center" data-pc-emailcheckout="show">You can opt for register an account after completing this purchase.</p>
		
		
		<input	type="hidden"	name='step_name' value='login' />
	 </div>	 
	 
	<script>
		//paycart.queue.push('paycart.checkout.login.init()');

		
		(function($){

			paycart.checkout.step.change('<?php echo $step_ready; ?>');
			
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

				setEmailCheckout : function(guest)
				{
					//default is guest mode
					if(guest){
						$('[data-pc-emailcheckout="show"]').show();
						$('[data-pc-emailcheckout="hide"]').hide();
					}else{
						$('[data-pc-emailcheckout="show"]').hide();
						$('[data-pc-emailcheckout="hide"]').show();
					}
				}
			};

			paycart.checkout.login.init();
			
		})(paycart.jQuery);
		
	</script>


<?php

