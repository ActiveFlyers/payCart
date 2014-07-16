<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

/**
 * Populated varaible
 * $title : title required or not
 * $shipping_address 	:: 	stdclass object, contain previous address data 
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

//@PCTODO:: define constant for it
if (!PaycartFactory::getApplication()->client->mobile) {
	JHtml::_('formbehavior.chosen', '.pc-buyeraddress');
}

?>

	<?php if (!empty($buyer_addresses)) :?>
	
		<select name='select_billing_address' class="pc-buyeraddress input-block-level" onChange='paycart.checkout.buyeraddress.onSelect(this.value, "shipping");'>
		
			<option value='0'> <?php echo JText::_(' Select Existing Address'); ?> </option>
		<?php
			foreach ($buyer_addresses as $buyeaddress_id => $buyeraddress_details):

				$selected = ($shipping_address_id == $buyeraddress_details->buyeraddress_id)
								? 'selected' : '';
		?>
			<option value='<?php echo $buyeraddress_details->buyeraddress_id?>'
				<?php echo $selected; ?>
			>
				<?php echo $buyeraddress_details->address; ?>
				<?php echo "{$buyeraddress_details->city}-{$buyeraddress_details->zipcode}"; ?>
				<?php echo "{$buyeraddress_details->state_id}"; ?>
				<?php echo "{$buyeraddress_details->country_id}"; ?>
				<?php echo "{$buyeraddress_details->phone1}"; ?>
			</option>
		<?php endforeach;?>
		</select>		
	<?php endif; ?>
		
		 <fieldset>
	 	 
	 	 	<label class="control-label required" ><?php echo JText::_('ZIP code'); ?></label>
			<input type="text" name="paycart_form[shipping][zipcode]" class="input-block-level"  value="<?php echo @$shipping_address->zipcode; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>

			<label class="control-label required"><?php echo JText::_('Full Name'); ?></label>
			<input type="text" name="paycart_form[shipping][to]" class="input-block-level" value="<?php echo @$shipping_address->to; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label class="control-label required"><?php echo JText::_('Phone Number'); ?></label>
			<input type="text" name="paycart_form[shipping][phone1]" class="input-block-level" value="<?php echo @$shipping_address->phone1; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Select Country'); ?></label>
			<?php
				echo PaycartHtmlCountry::getList('paycart_form[shipping][country_id]',  @$shipping_address->country,  'shipping_country_id', Array('class'=>'input-block-level'));

			?>
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Select State'); ?></label>
			<?php 
	  			echo PaycartHtmlState::getList('paycart_form[shipping][state_id]', @$shipping_address->state,  'shipping_state_id', Array('class'=>'input-block-level'), @$shipping_address->country_id);
	  		?>
		  		<script>
	
				(function($){
	
					$('#shipping_country_id').on('change',  function(event, data) {
						var default_selected_state = 0;

						if (typeof data !== 'undefined' && typeof data.state_id !== 'undefined') {
							default_selected_state =  data.state_id;
						}

						paycart.address.state.onCountryChange('#shipping_country_id', '#shipping_state_id', default_selected_state);
					});
					

					//if state already selected with country respected  then no need to get states (In checkout steps on restart)
					if (!$('#shipping_state_id').val()) { 
						paycart.address.state.onCountryChange('#shipping_country_id', '#shipping_state_id');
					}
					
				})(paycart.jQuery);
				
				</script>
		
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Town/City'); ?></label>
			<input type="text" name="paycart_form[shipping][city]" class="input-block-level"  value="<?php echo @$shipping_address->city; ?>" >
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			
			<label  class="control-label required"><?php echo JText::_('Delivery Address'); ?></label>
			<span class="hide help-block"><?php echo JText::_('Example block-level help text here.'); ?></span>
			<textarea class="input-block-level" rows="3" name="paycart_form[shipping][address]"><?php echo @$shipping_address->address; ?></textarea>
					
		</fieldset>
		
	<script>
			
		(function($){
					
			
		})(paycart.jQuery);
	
	</script>	 

<?php

