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

<div class="pc-checkout-state row-fluid clearfix">
	<?php echo $this->loadTemplate('steps');?>
</div>
		
<div class="pc-checkout-login row-fluid">
	
	<h3><?php echo JText::_('Login');?></h3>

	<fieldset class="radio input-lg pc-whitespace">
		<input type="radio" id="paycart_cart_login_emailcheckout_1" name="paycart_cart_login[emailcheckout]"  value="1" checked="checked">
		<label for="paycart_cart_login_emailcheckout_1" class="radio"><?php echo JText::_('Continue as guest');?></label>
			
		<input type="radio" id="paycart_cart_login_emailcheckout_0"  name="paycart_cart_login[emailcheckout]" value="0">
		<label for="paycart_cart_login_emailcheckout_0" class="radio"><?php echo JText::_('Existing Customer');?></label>			
	</fieldset>
			
	<fieldset>
		<span class="pc-error" for="paycart_cart_login" id="paycart_cart_login">&nbsp;</span> <?php //@PCTODO : improve it?>
		
		<div class="control-group">	
			<div class="control-label">
	 			<label id="paycart_cart_login_email-lbl" for="paycart_cart_login_email" class="required" aria-invalid="false"><?php echo JText::_('Email');?></label>
	 		</div>
	 		<div class="controls">
				<input type="email" name="paycart_cart_login[email]" id="paycart_cart_login_email" class="input-block-level validate-email" required="" value = "<?php echo @$buyer->email; ?>" error-message="<?php echo JText::_('Please enter valid email address.');?>"/>
				<span class="pc-error" for="paycart_cart_login_email"><?php echo JText::_('Please enter valid email address.');?></span>
			</div>
		 </div>
		 <div class="control-group" data-pc-emailcheckout="hide">
		 	<div class="control-label">
	 			<label id="paycart_cart_login_password-lbl" for="paycart_cart_login_password" class="required" aria-invalid="false"><?php echo JText::_('Password');?></label>
	 		</div>				
			<div class="controls">
				<input type="password" name="paycart_cart_login[password]" id="paycart_cart_login_password" required class="input-block-level">
				<span class="pc-error" for="paycart_cart_login_password"><?php echo JText::_('Please enter a password.');?></span>
			</div>
		</div>
 	</fieldset>
 	
	<button type="button" onClick="paycart.cart.login.do();" class="pc-whitespace btn btn-block btn-large btn-primary">
			<?php echo JText::_('Continue');?> <i class="fa fa-angle-double-right"></i> 
	</button>
	 	
	<p class="small muted text-center" data-pc-emailcheckout="show"><?php echo JText::_('You can opt for register an account after completing this purchase.');?></p>
	
	
	<input	type="hidden"	name='step_name' value='login' />
 </div>	 
 
<script>
			
	(function($){
		$(document).ready(function(){
			paycart.cart.login.init();
		});
	})(paycart.jQuery);
	
</script>
<?php

