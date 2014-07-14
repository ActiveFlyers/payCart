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

// use it for multiple ids
static $id_suffix = 0;
$id_suffix++;

?>

<fieldset>

<!--	Buyeraddress id	-->
	<div class="">
	<!--	Buyeraddress Country : value		-->
		<div class="">		
			<input	name="<?php echo $prefix; ?>[buyeraddress_id]" 
					id="buyeraddress_id_<?php echo $id_suffix; ?>"
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
					id="buyer_id_<?php echo $id_suffix; ?>" 
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
				id="to_<?php echo $id_suffix; ?>" 
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
					id="address_<?php echo $id_suffix; ?>"
	    			placeholder="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ADDRESS'); ?>"
	    >
	    	<?php echo $displayData->address; ?>
	    </textarea>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_ADDRESS_DESC');?></p>
	  </div>
	</div>
	
	
<!-- Buyeraddress country-->
	<div class="control-group">
	  <label class="control-label" for="textinput">
	  	<?php echo JText::_('COM_PAYCART_BUYERADDRESS_COUNTRY');?>"
	  </label>
	  <div class="controls">
	  		<?php
	  			echo PaycartHtmlCountry::getList($prefix.'[country_id]', $displayData->country_id, "country_id_$id_suffix", array('class'=>"input-xlarge")); 
	  		?>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_COUNTRY_DESC');?></p>
	  </div>
	</div>
	
	
	
	<!--	Buyeraddress State	-->
	<div class="control-group">
	  
	  <label class="control-label" for="textinput"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_STATE'); ?></label>
	  <div class="controls">
	  	<?php 
	  		echo PaycartHtmlState::getList($prefix.'[state_id]',$displayData->state_id,  "state_id_$id_suffix", array('class'=>"input-xlarge"),  $displayData->country_id);
	  	?>
	    <p class="help-block"><?php echo JText::_('COM_PAYCART_BUYERADDRESS_STATE_DESC');?></p>
	    
	    <script>

			(function($){

				$(<?php echo "'#country_id_$id_suffix'"; ?>).on('change',  function() {
					paycart.address.state.onCountryChange(<?php echo "'#country_id_$id_suffix'" ?>, <?php echo "'#state_id_$id_suffix'" ?>);
				});
				<?php
					// if state already available then no need to get states  
					if (!$displayData->state_id) :
				?>
					paycart.address.state.onCountryChange(<?php echo "'#country_id_$id_suffix'" ?>, <?php echo "'#state_id_$id_suffix'" ?>);
				<?php endif; ?>
				
			})(paycart.jQuery);
			
		</script>
		
	  </div>
	</div>

<!--	Buyeraddress City	-->
	<div class="control-group">
	  <label class="control-label" for="textinput"></label>
	  <div class="controls">
	    <input 	name="<?php echo $prefix; ?>[city]" 
				id="city_<?php echo $id_suffix; ?>" 
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
				id="zipcode_<?php echo $id_suffix; ?>" 
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
				id="vat_number_<?php echo $id_suffix; ?>" 
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
				id="phone1_<?php echo $id_suffix; ?>" 
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
				id="phone2_<?php echo $id_suffix; ?>" 
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
	



