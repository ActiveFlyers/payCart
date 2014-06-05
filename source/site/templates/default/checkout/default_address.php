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
				
				<button type="button" onClick="paycart.checkout.address.do();" 
						class="pc-whitespace btn btn-block btn-large btn-primary">
					Continue <i class="fa fa-angle-double-right"></i>
				</button>
				
			</div>
			
		<?php 	endif;	?>
		
		<input	type="hidden"	name='step_name' value='address' />
				
	</div>
		
	<script>
			
		(function($) {

			paycart.checkout.address = 
			{
				copy : function(from, to)
				{
					var regExp 			=	/\[(\w*)\]$/;
					var from_name 		=	'paycart_form['+from +']';
					var to_name 		=	'paycart_form['+to +']';
					
					var form_selector	= '[name^="'+from_name+'"]';
						
					$(form_selector).each(function() {

						// get index
						var matches = this.name.match(regExp);

						if (!matches) {
							return false;
						}

						//matches[1] contains the value between the Square Bracket
						var index 		= matches[1];
						var to_selector = '[name^="'+to_name+'['+index+']"]';

						$(to_selector).val($(this).val())
					});

					console.log('copy '+from+' to '+to);
				},
			
				// Copy billing to shipping				
				billing_to_shipping : function()
				{
					// Checked billing to shipping 
					if( $('#billing_to_shipping').prop('checked') == true ) { 

						paycart.checkout.address.copy('billing', 'shipping');
						
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

					//@FIXME:: (check element exist or not) Or Remove this stuff from here
					//Before Submit Copy billing to shipping address
					if ( $('#billing_to_shipping').prop('checked') == true ) { 
						paycart.checkout.address.copy('billing', 'shipping');
					}
					
					paycart.checkout.submit.do();

					return false;					
				}
			};

			paycart.checkout.address.billing_to_shipping();
			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
	
	</script>	 

<?php

