<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Buyer
* @subpackage       BuyerJusergroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>
<!-- Address type -->
	<div class="control-group">
	  <label class="control-label" for="textinput">
	  		<?php echo JText::_('COM_PAYCART_ADDRESS_TYPE');?>
	  </label>
	  <div class="controls">
		  	<select class="paycart-grouprule-buyer-address-type" name="<?php echo $namePrefix;?>[address_type]" id="<?php echo $idPrefix;?>address-type">
				<option value="billing" <?php echo isset($config['address_type']) && $config['address_type'] == 'billing' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_ADDRESS_BILLING');?></option>
				<option value="shipping" <?php echo isset($config['address_type']) && $config['address_type'] == 'shipping' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_ADDRESS_SHIPPING');?></option>
			</select>		   
	  </div>
	</div>
	

<!-- Buyeraddress country-->
	<div class="control-group">
	  <label class="control-label" for="textinput">
	  		<?php echo JText::_('COM_PAYCART_COUNTRY');?>
	  </label>
	  <div class="controls">
		  	<select class="paycart-grouprule-buyer-countries-assignment" name="<?php echo $namePrefix;?>[countries_assignment]" data-pc-selector="pc-option-manipulator" id="<?php echo $idPrefix;?>countries-assignment">
				<option value="any" <?php echo !isset($config['countries_assignment']) ||  $config['countries_assignment'] == 'any' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_ANY');?></option>
				<option value="selected" <?php echo isset($config['countries_assignment']) && $config['countries_assignment'] == 'selected' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_SELECTED');?></option>
<!--			<option value="except" <?php echo isset($config['countries_assignment']) && $config['countries_assignment'] == 'except' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_EXCEPT');?></option>-->
			</select>
			<span data-pc-option-manipulator="<?php echo $idPrefix.'countries-assignment';?>" class="<?php echo !isset($config['countries_assignment']) || $config['countries_assignment'] == 'any' ? 'hide' : '';?>"> 
	  			<?php echo PaycartHtmlCountry::getList($namePrefix.'[countries][]', @$config['countries'], "{$idPrefix}countries", array('class' => "pc-chosen", 'multiple' => true));?>
	  		</span>	   
	  </div>
	</div>
	
	
	
<!--	Buyeraddress State	-->
	<div class="control-group">
	  
	  <label class="control-label" for="textinput">
	  		<?php echo JText::_('COM_PAYCART_STATE');?>
	  </label>
	  <div class="controls">
	  	<select class="paycart-grouprule-buyer-states-assignment" name="<?php echo $namePrefix;?>[states_assignment]" id="<?php echo $idPrefix;?>states-assignment" data-pc-selector="pc-option-manipulator">
				<option value="any" <?php echo !isset($config['states_assignment']) || $config['states_assignment'] == 'any' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_ANY');?></option>
				<option value="selected" <?php echo isset($config['states_assignment']) && $config['states_assignment'] == 'selected' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_SELECTED');?></option>
<!--			<option value="except" <?php echo isset($config['states_assignment']) && $config['states_assignment'] == 'except' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_EXCEPT');?></option>-->
			</select>
			<span data-pc-option-manipulator="<?php echo $idPrefix.'states-assignment';?>" class="<?php echo !isset($config['states_assignment']) || $config['states_assignment'] == 'any' ? 'hide' : '';?>">
		  	<?php 
		  		echo PaycartHtmlState::getList($namePrefix.'[states][]',@$config['states'],  "{$idPrefix}states", array('class' => "pc-chosen", 'multiple' => true),  @$config['countries']);
		  	?>
		  	</span>
	  </div>
	</div>
	
	<div class="control-group">	  
	  <label class="control-label" for="textinput">
	  		<?php echo JText::_('COM_PAYCART_ZIPCODE');?>
	  </label>
	  <div class="controls">	  	
	  	 <input type="text" name="<?php echo $namePrefix;?>[min_zipcode]" id="<?php echo $idPrefix;?>min-zipcode" placeholder="<?php echo JText::_('COM_PAYCART_FROM');?>"> 	  	 
	  	 <input type="text" name="<?php echo $namePrefix;?>[max_zipcode]" id="<?php echo $idPrefix;?>max-zipcode" placeholder="<?php echo JText::_('COM_PAYCART_TO');?>">
	  </div>
	</div>
	
	
<script>
	(function($){
		paycart.address.state.onCountryChange(<?php echo "'#{$idPrefix}countries'" ?>, <?php echo "'#{$idPrefix}states'" ?>,null,null,true);
		$(<?php echo "'#{$idPrefix}countries'"; ?>).on('change',  function() {
			paycart.address.state.onCountryChange(<?php echo "'#{$idPrefix}countries'" ?>, <?php echo "'#{$idPrefix}states'" ?>,null,null,true);
		});	
	})(paycart.jQuery);
</script>
