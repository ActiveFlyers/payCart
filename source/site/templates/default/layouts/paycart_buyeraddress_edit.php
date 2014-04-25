<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Layouts
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

/**
 * List of Populated Variables
 * $displayData = have all required data 
 * $displayData->prefix = Prefix for element {id and name}
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

$prefix = 'paycart_buyeraddress';

// cusom prefix provided
if(isset($displayData->prefix) && !empty($displayData->prefix))  {
	$prefix = $displayData->prefix;
}

?>

<fieldset>

<!--	Buyeraddress id	-->
	<div class="">
	<!--	Buyeraddress Country : value		-->
		<div class="">		
			<input	name="<?php echo $prefix; ?>[buyeraddress_id]" 
					id="<?php echo $prefix; ?>_buyeraddress_id"
					value="<?php echo $displayData->buyeraddress_id; ?>"
					type="hidden"
				/>
		</div>								
	</div>
				
<!--	Buyeraddress buyer_id	-->
	<div class="">
		<!--	Buyeraddress buyer_id : value		-->
		<div class="">		
			<input	name="<?php echo $prefix; ?>[buyer_id]" 
					id="<?php echo $prefix; ?>_buyer_id" 
					value="<?php echo $displayData->buyer_id; ?>"
					type="hidden" 
				/>
				
		</div>								
	</div>	
		
<!-- Buyer To -->
	<div class="control-group">
	  <label class="control-label" for="to"></label>
	  <div class="controls">
	    <input 
	    		name="<?php echo $prefix; ?>[to]" 
				id="<?php echo $prefix; ?>_to" 
				value="<?php echo $displayData->to; ?>"
				placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_TO_LABEL'); ?>" 
				class="input-xlarge" required="" type="text"
		/>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_TO_DESC');?></p>
	  </div>
	</div>
		
<!-- Buyer Address -->
	<div class="control-group">
	  <label class="control-label" for="address"></label>
	  <div class="controls">                     
	    <textarea 	name="<?php echo $prefix; ?>[address]" 
					id="<?php echo $prefix; ?>_address"
	    			placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ADDRESS'); ?>"
	    >
	    	<?php echo $displayData->address; ?>
	    </textarea>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_ADDRESS_DESC');?></p>
	  </div>
	</div>
	
	
<!-- Buyeraddress input-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input	name="<?php echo $prefix; ?>[country]" 
				id="<?php echo $prefix; ?>_country" 
	    		placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_COUNTRY');?>" class="input-xlarge"
	    		value="<?php echo $displayData->country; ?>" 
	    		type="text"
	    		required=""
	    />
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_COUNTRY_DESC');?></p>
	  </div>
	</div>
		
<!--	Buyeraddress State	-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input 	name="<?php echo $prefix; ?>[state]" 
				id="<?php echo $prefix; ?>_state" 
				value="<?php echo $displayData->state; ?>"
				placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_STATE'); ?>" class="input-xlarge" type="text"
				required=""
				>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_STATE_DESC');?></p>
	  </div>
	</div>

<!--	Buyeraddress City	-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input 	name="<?php echo $prefix; ?>[city]" 
				id="<?php echo $prefix; ?>_city" 
				value="<?php echo $displayData->city; ?>"
	    		placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_CITY'); ?>" class="input-xlarge" type="text"
	    		required="" >
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_CITY_DESC');?></p>
	  </div>
	</div>

<!--	Buyeraddress Zipcode	-->
	<div class="control-group">
	  <label class="control-label" for="Zipcode"></label>
	  <div class="controls">
	    <input 	name="<?php echo $prefix; ?>[zipcode]" 
				id="<?php echo $prefix; ?>_zipcode" 
				value="<?php echo $displayData->zipcode; ?>"
				placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ZIPCODE'); ?>" class="input-xlarge" type="text"
				required=""
				>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_ZIPCODE_DESC');?></p>
	  </div>
	</div>

<!--	Buyeraddress Vatnumber	-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input 	name="<?php echo $prefix; ?>[vat_number]" 
				id="<?php echo $prefix; ?>_vat_number" 
				value="<?php echo $displayData->vat_number; ?>" 
	    		placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_VAT_NUMBER'); ?>" class="input-xlarge" type="text">
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_VAT_NUMBER_DESC');?></p>
	  </div>
	</div>
		
<!--	Buyeraddress Phone1	-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input	name="<?php echo $prefix; ?>[phone1]" 
				id="<?php echo $prefix; ?>_phone1" 
				value="<?php echo $displayData->phone1; ?>" 
	    		placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE1'); ?>" class="input-xlarge" type="text"
	    		required=""
	    		>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE1_DESC');?></p>
	  </div>
	</div>


<!--	Buyeraddress Phone2	-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input	name="<?php echo $prefix; ?>[phone2]" 
				id="<?php echo $prefix; ?>_phone2" 
				value="<?php echo $displayData->phone2; ?>" 
	    		placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE2'); ?>" class="input-xlarge" type="text">
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE2_DESC');?></p>
	  </div>
	</div>


</fieldset>
	
	<script>

		(function($){
	
			$(document).ready(function($){
				
			});				
		 	
		})(paycart.jQuery);
	</script>
	



