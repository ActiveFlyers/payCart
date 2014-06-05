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
		<?php
			if (!empty($buyer_addresses)) :
				echo $this->loadTemplate('buyeraddresses');
			else:
		?>		
			<!--	Billing Address -->
		 	<div class="span6 pc-checkout-billing ">
			 	<?php
			 		$this->assign('title', true); 
			 		echo $this->loadTemplate('billingaddress'); 
			 	?>
			</div>
		
			<!--	Shipping Address	-->
			<div class=" span6 pc-checkout-shipping clearfix">
			 	<?php
			 		$this->assign('title', true);
			 		$this->assign('billing_to_shipping', true);
			 		echo $this->loadTemplate('shippingaddress'); ?>
			</div>
		
			<!--	Continue Checkout	-->
			<div class="clearfix">
				
				<button type="button" onClick="paycart.checkout.address.onContinue();" 
						class="pc-whitespace btn btn-block btn-large btn-primary">
					<?php JText::_('COM_PAYCART_BUTTON_CONTINUE')?> <i class="fa fa-angle-double-right"></i>
				</button>
				
			</div>
			
		<?php 	endif;	?>
		
		<input	type="hidden"	name='step_name' value='address' />
				
	</div>
		
	<script>
			
		(function($) {

			paycart.checkout.address.billing_to_shipping();
			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
	
	</script>	 

<?php

