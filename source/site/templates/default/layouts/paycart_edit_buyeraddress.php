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
	
		<div class="">
			<div class="">
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_NAME_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_NAME_LABEL'); ?>
				</label> 	
			</div>
				
			<div class="">
				<input 	name="<?php echo $prefix; ?>[to]" 
						id="<?php echo $prefix; ?>_to" 
						value="<?php echo $displayData->to; ?>"  
						type="text"
				/>	
			</div>
												
		</div>
		
		<div class="">
			<div class="">
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ADDRESS_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ADDRESS'); ?>
				</label> 
			</div>
					
			<div class="">		
				<textarea 	name="<?php echo $prefix; ?>[address]" 
							id="<?php echo $prefix; ?>_address"
							class="required"
				><?php echo $displayData->address; ?></textarea>
			</div>								
		</div>
		
<!--	Buyeraddress Country	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress Country : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_COUNTRY_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_COUNTRY'); ?>
				</label> 
			</div>
			<!--	Buyeraddress Country : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[country]" 
						id="<?php echo $prefix; ?>_country" 
						class="required"
						value="<?php echo $displayData->country; ?>"
					/>
			</div>								
		</div>
				
<!--	Buyeraddress State	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress State : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_STATE_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_STATE'); ?>
				</label> 
			</div>
			<!--	Buyeraddress State : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[state]" 
						id="<?php echo $prefix; ?>_state" 
						value="<?php echo $displayData->state; ?>" 
					/>
			</div>								
		</div>

<!--	Buyeraddress City	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress City : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_CITY_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_CITY'); ?>
				</label> 
			</div>
			<!--	Buyeraddress City : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[city]" 
						id="<?php echo $prefix; ?>_city" 
						value="<?php echo $displayData->city; ?>" 
					/>
			</div>								
		</div>

<!--	Buyeraddress Zipcode	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress Zipcode : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ZIPCODE_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_ZIPCODE'); ?>
				</label> 
			</div>
			<!--	Buyeraddress Zipcode : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[zipcode]" 
						id="<?php echo $prefix; ?>_zipcode" 
						value="<?php echo $displayData->zipcode; ?>" 
					/>
			</div>								
		</div>
		
<!--	Buyeraddress Vatnumber	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress vat_number : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_VAT_NUMBER_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_VAT_NUMBER'); ?>
				</label> 
			</div>
			<!--	Buyeraddress City : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[vat_number]" 
						id="<?php echo $prefix; ?>_vat_number" 
						value="<?php echo $displayData->vat_number; ?>" 
					/>
			</div>								
		</div>

<!--	Buyeraddress Phone1	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress phone1 : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE1_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE1'); ?>
				</label> 
			</div>
			<!--	Buyeraddress phone1 : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[phone1]" 
						id="<?php echo $prefix; ?>_phone1" 
						value="<?php echo $displayData->phone1; ?>" 
					/>
			</div>								
		</div>
				
				
<!--	Buyeraddress Phone2	-->
		<div class="">
			<div class="">
				<!--	Buyeraddress phone2 : label name		-->
				<label title="<?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE2_DESC');?>"> 
					<?php echo JText::_('COM_PAYCART_BUYERADDRESS_PHONE2'); ?>
				</label> 
			</div>
			<!--	Buyeraddress phone2 : value		-->
			<div class="">		
				<input	name="<?php echo $prefix; ?>[phone2]" 
						id="<?php echo $prefix; ?>_phone2" 
						value="<?php echo $displayData->phone2; ?>" 
					/>
			</div>								
		</div>

	</fieldset>
	
	
	<script>

		(function($){
	
			$(document).ready(function($){
				
			});				
		 	
		})(paycart.jQuery);
	</script>
	



