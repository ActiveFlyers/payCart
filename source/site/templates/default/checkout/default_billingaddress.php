<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

/**
 * Populated varaible ::
 * $title : title required or not
 * $shipping_to_billing ::  html display or not
 * $billing_address 	:: 	stdclass object, contain previous address data 
 * 
 */

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
	<?php if (isset($title) && !empty($title)): ?>
	 	<h3><?php echo JText::_('COM_PAYCART_BILLING_INFO'); ?></h3>
	 <?php endif;?>
	 
	 <?php if (isset($shipping_to_billing) && !empty($shipping_to_billing)): ?>
	 	<label class="checkbox">
			<input 	id='billing_to_shipping' type="checkbox" 
					checked="checked"		 name="paycart_form[shipping_to_billing]"
					onClick="paycart.checkout.address.shipping_to_billing();"
					value='true'
			> <?php echo JText::_('COM_PAYCART_SAME_AS_SHIPPING_ADDRESS'); ?>
		</label>
	<?php endif; ?>
	
	 	 <fieldset>
	 	 
	 	 	<label class="control-label required" ><?php echo JText::_('ZIP code'); ?></label>
			<input type="text" name="paycart_form[billing][zipcode]" class="input-block-level"  value="<?php echo @$billing_address->zipcode; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>

			<label class="control-label required"><?php echo JText::_('Full Name'); ?></label>
			<input type="text" name="paycart_form[billing][to]" class="input-block-level" value="<?php echo @$billing_address->to; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label class="control-label required"><?php echo JText::_('Phone Number'); ?></label>
			<input type="text" name="paycart_form[billing][phone1]" class="input-block-level" value="<?php echo @$billing_address->phone1; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Select Country'); ?></label>
			<?php
				echo PaycartHtmlCountry::getList('paycart_form[billing][country]',  @$billing_address->country,  'billing_country_id', Array('class'=>'span12'));

			?>
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Select State'); ?></label>
			<?php 
	  			echo PaycartHtmlState::getList('paycart_form[billing][state]', @$billing_address->state,  'billing_state_id', Array('class'=>'span12'), @$billing_address->country);
	  		?>
		  		<script>
	
				(function($){
	
					$('#billing_country_id').on('change',  function() {
						paycart.address.state.onCountryChange('#billing_country_id', '#billing_state_id');
					});
					//if state already selected then no need to get states
					if (!$('#billing_state_id').val()) { 
						paycart.address.state.onCountryChange('#billing_country_id', '#billing_state_id');
					}
				})(paycart.jQuery);
				
				</script>
		
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Town/City'); ?></label>
			<input type="text" name="paycart_form[billing][city]" class="input-block-level"  value="<?php echo @$billing_address->city; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Delivery Address'); ?></label>
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			<textarea class="input-block-level" rows="3" name="paycart_form[billing][address]"><?php echo @$billing_address->address; ?></textarea>

		</fieldset>

		
	<script>
			
		(function($){
					
			
		})(paycart.jQuery);
	
	</script>	 

<?php

