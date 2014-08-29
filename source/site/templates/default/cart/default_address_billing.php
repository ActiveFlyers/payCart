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
<?php if (!empty($buyer_addresses)) :?>
	<select name='select_billing_address' id="pc-buyeraddress-billing-address" class="pc-chozen input-block-level" onChange='paycart.cart.address.onSelect(this.value, "billing");'>
		<option value='0'> <?php echo JText::_(' Select Existing Address'); ?> </option>
		<?php foreach ($buyer_addresses as $buyeaddress_id => $buyeraddress_details):?>
			<?php $selected = ($billing_address_id == $buyeraddress_details->buyeraddress_id) ? 'selected' : ''; ?>
			<option value='<?php echo $buyeraddress_details->buyeraddress_id?>'	<?php echo $selected; ?>>
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
  	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_zipcode-lbl" for="paycart_cart_address_billing_zipcode" class="required"><?php echo JText::_('ZIP code'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][zipcode]" id="paycart_cart_address_billing_zipcode" class="input-block-level" required="" value = "<?php echo @$billing_address->zipcode; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_zipcode"><?php echo JText::_('Error');?></span>
		</div>
	</div>

	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_to-lbl" for="paycart_cart_address_billing_to" class="required"><?php echo JText::_('Full Name'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][to]" id="paycart_cart_address_billing_to" class="input-block-level" required="" value = "<?php echo @$billing_address->to; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_to"><?php echo JText::_('Error');?></span>
		</div>
	</div>
	
	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_phone1-lbl" for="paycart_cart_address_billing_phone1" class="required"><?php echo JText::_('Phone Number'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][phone1]" id="paycart_cart_address_billing_phone1" class="input-block-level" required="" value = "<?php echo @$billing_address->phone1; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_phone1"><?php echo JText::_('Error');?></span>
		</div>
	</div>
				
	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_billing_country_id-lbl" for="paycart_billing_country_id" class="required"><?php echo JText::_('Select Country'); ?></label>
 		</div>
 		<div class="controls">		 			
			<?php echo PaycartHtmlCountry::getList('paycart_cart_address[billing][country_id]',  @$billing_address->country_id,  'paycart_billing_country_id', Array('class'=>'pc-chozen input-block-level', 'required' => '')); ?>
			<span class="pc-error" for="paycart_billing_country_id"><?php echo JText::_('Error');?></span>
		</div>
	</div>
				
				
	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_billing_state_id-lbl" for="paycart_billing_state_id" class="required"><?php echo JText::_('Select State'); ?></label>
 		</div>
 		<div class="controls">		 			
			<?php echo PaycartHtmlState::getList('paycart_cart_address[billing][state_id]', @$billing_address->state_id,  'paycart_billing_state_id', Array('class'=>'pc-chozen input-block-level', 'required' => ''), @$billing_address->country_id);?>
			<span class="pc-error" for="paycart_billing_state_id"><?php echo JText::_('Error');?></span>
		</div>
	</div>
	
	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_city-lbl" for="paycart_cart_address_billing_city" class="required"><?php echo JText::_('Town/City'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][city]" id="paycart_cart_address_billing_city" class="input-block-level" required="" value = "<?php echo @$billing_address->city; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_city"><?php echo JText::_('Error');?></span>
		</div>
	</div>
	
	<div class="control-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_address-lbl" for="paycart_cart_address_billing_address" class="required"><?php echo JText::_('Address'); ?></label>
 		</div>
 		<div class="controls">
			<textarea name="paycart_cart_address[billing][address]" id="paycart_cart_address_billing_address" class="input-block-level" required=""><?php echo @$billing_address->address; ?></textarea>
			<span class="pc-error" for="paycart_cart_address_billing_address"><?php echo JText::_('Error');?></span>
		</div>
	</div>
</fieldset>
		
<script>
	<?php // @PCTODO : move to proper location?>
	(function($){

		$('#paycart_billing_country_id').on('change',  function(event, data) {
			var default_selected_state = 0;

			if (typeof data !== 'undefined' && typeof data.state_id !== 'undefined') {
				default_selected_state =  data.state_id;
			}
			
			paycart.address.state.onCountryChange('#paycart_billing_country_id', '#paycart_billing_state_id', default_selected_state);
		});
		//if state already selected then no need to get states
		if (!$('#paycart_billing_state_id').val()) { 
			paycart.address.state.onCountryChange('#paycart_billing_country_id', '#paycart_billing_state_id');
		}
		
		<?php if (!$is_platform_mobile) : ?>
			<?php echo "$('#pc-buyeraddress-billing-address').chosen()";?>
		<?php endif;?>	
		
	})(paycart.jQuery);

</script>	 

<?php

