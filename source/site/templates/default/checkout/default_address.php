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

	 <div class="pc-checkout-address row-fluid">
				
		<!--	Billing Address -->
	 	<div class="span6 pc-checkout-billing ">
	 	
	 		<h3><?php echo JText::_('COM_PAYCART_ADDRESS_BILLING'); ?></h3>
	 		
	 		<div class="pc-checkout-billing-html">
			 	<?php
			 		// load billing address template
			 		echo $this->loadTemplate('billingaddress'); 
			 	?>
			 </div>
			 
		</div>
	
		<!--	Shipping Address	-->
		<div class=" span6 pc-checkout-shipping clearfix">
		
			<h3><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING'); ?></h3>

			<label class="checkbox">
				<input 	id='billing_to_shipping' type="checkbox" 
						<?php echo ($billing_to_shipping)? 'checked="checked"' : ''?>		
						name="paycart_form[billing_to_shipping]"
						onClick="return paycart.checkout.buyeraddress.onBillingToShipping();"
						value='true'
				/><?php echo JText::_('COM_PAYCART_SAME_ADDRESS_TEXT'); ?>
					
			</label>
			
			<div class="pc-checkout-shipping-html">
			 	<?php
					// load shipping address template
			 		echo $this->loadTemplate('shippingaddress');
			 	?>
		 	</div>
		 	
		</div>
	
		<!--	Continue Checkout	-->
		<div class="clearfix">
			
			<button type="button" onClick="paycart.checkout.buyeraddress.onContinue();" 
					class="pc-whitespace btn btn-block btn-large btn-primary">
				<?php echo JText::_('COM_PAYCART_BUTTON_CONTINUE'); ?> <i class="fa fa-angle-double-right"></i>
			</button>
			
		</div>
			
		<input	type="hidden"	name='step_name' value='address' />
				
	</div>
		
	<script>
			
		(function($) {
						
			paycart.checkout.buyeraddress.init();
			paycart.checkout.step.change('<?php echo json_encode($available_steps) ?>');	

			//$("#pc-checkout-form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
			
		})(paycart.jQuery);
	
	</script>	 

<?php

