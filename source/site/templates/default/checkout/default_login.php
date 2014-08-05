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
		
		<h3><?php echo JText::_('Login');?></h3>

		<fieldset class="radio input-lg pc-whitespace">
			<input type="radio" id="paycart_form_emailcheckout_1" name="paycart_form[emailcheckout]"  value="1" checked="checked">
			<label for="paycart_form_emailcheckout_1" class="radio"><?php echo JText::_('Continue as guest');?></label>
				
			<input type="radio" id="paycart_form_emailcheckout_0"  name="paycart_form[emailcheckout]" value="0">
			<label for="paycart_form_emailcheckout_0" class="radio"><?php echo JText::_('Existing Customer');?></label>
			
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.');?></span>
		</fieldset>
				
		<fieldset>
			 <div class="control-group">	
		 		<label><?php echo JText::_('Email');?></label>
		 		<div class="controls">
					<input type="text" name="paycart_form[email]" class="input-block-level required" placeholder="Enter email address (required)" value = "<?php echo @$buyer->email; ?>" />
					<span class="hide help-block"><?php echo JText::_('Example block-level help text here.');?></span>
				</div>
			 </div>
			 <div class="control-group" data-pc-emailcheckout="hide">	
				<label><?php echo JText::_('Password');?></label>
				<div class="controls">
					<input type="password" name="paycart_form[password]" class="input-block-level">
					<span class="hide help-block"><?php echo JText::_('Example block-level help text here.');?></span>
				</div>
			</div>
	 	</fieldset>
	 	
		<button type="button" onClick="paycart.checkout.login.do();" class="pc-whitespace btn btn-block btn-large btn-primary">
				<?php echo JText::_('Continue');?> <i class="fa fa-angle-double-right"></i> 
		</button>
		 	
		<p class="small muted text-center" data-pc-emailcheckout="show"><?php echo JText::_('You can opt for register an account after completing this purchase.');?></p>
		
		
		<input	type="hidden"	name='step_name' value='login' />
	 </div>	 
	 
	<script>
				
		(function($){

			paycart.checkout.step.change('<?php echo json_encode($available_steps) ?>');
			paycart.checkout.login.init();
			
		})(paycart.jQuery);
		
	</script>


<?php

