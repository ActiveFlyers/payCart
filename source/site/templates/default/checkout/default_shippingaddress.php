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
		 	<h3><?php echo JText::_('COM_PAYCART_SHIPPING_ADDRESS_TITLE'); ?></h3>
	<?php endif; ?>	 	
	
	<?php if (isset($billing_to_shipping) && !empty($billing_to_shipping)): ?>
	 	<label class="checkbox">
			<input 	id='billing_to_shipping' type="checkbox" 
					checked="checked"		 name="paycart_form[billing_to_shipping]"
					onClick="paycart.checkout.address.billing_to_shipping();"
					value='true'
			><?php echo JText::_('COM_PAYCART_SAME_AS_BILLING_ADDRESS'); ?>
		</label>
	<?php endif; ?>
		
		 <fieldset>
	 	 
	 	 	<label class="control-label required" ><?php echo JText::_('ZIP code'); ?></label>
			<input type="text" name="paycart_form[shipping][zipcode]" class="input-block-level" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>

			<label class="control-label required"><?php echo JText::_('Full Name'); ?></label>
			<input type="text" name="paycart_form[shipping][to]" class="input-block-level">
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label class="control-label required"><?php echo JText::_('Phone Number'); ?></label>
			<input type="text" name="paycart_form[shipping][phone1]" class="input-block-level">
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Select Country'); ?></label>
			<?php
				echo PaycartHtmlCountry::getList('paycart_form[shipping][country]', '',  "shipping_country_id", Array('class'=>"span12"));

			?>
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Select State'); ?></label>
			<?php 
	  			echo PaycartHtmlState::getList('paycart_form[shipping][state]', '',  "shipping_state_id", Array('class'=>"span12"));
	  		?>
		  		<script>

				(function($){

					$('#shipping_country_id').on('change',  function() {
						paycart.address.state.onCountryChange('#shipping_country_id', '#shipping_state_id');
					});
					paycart.address.state.onCountryChange('#shipping_country_id', '#shipping_state_id');
				})(paycart.jQuery);

				</script>

			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Town/City'); ?></label>
			<input type="text" name="paycart_form[shipping][city]" class="input-block-level">
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Delivery Address'); ?></label>
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			<textarea class="input-block-level" rows="3" name="paycart_form[shipping][address]"></textarea>
			
		</fieldset>
		
	<script>
			
		(function($){
					
			
		})(paycart.jQuery);
	
	</script>	 

<?php

