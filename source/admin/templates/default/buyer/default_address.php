<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
	<script>

		// edit existing buyer address
		var edit_buyeraddress = function(buyeraddress_id)
		{
			paycart.admin.buyeraddress.edit(buyeraddress_id);
		};

		(function($)
		{
			var callBackOnSuccess = function(data)
			{
				alert('//PCTODO: GOOD!! default address is changed successfully');
			};


			var callBackOnError = function(data)
			{
				alert('//PCTODO: Oops!! default address is fail to change successfully :( Please check ajax response data');
			};
			
			$(document).ready(function($) {

				$('.add-new-address').click( function()
						{	// pass buyer-id
							paycart.admin.buyeraddress.window(<?php echo $record_id; ?>);
						});

				// update buyer billing address
				$('.buyer_billing_address').click(function()
						{
							var json_object		= {} ;
							var paycart_form 	= {};

							// get all paycart_form data
							// make sure get selected redio button
							paycart_form['billing_address_id'] = $('.buyer_billing_address:radio:checked').val();
							paycart_form['buyer_id']			 = <?php echo $record_id ?> ;

							// prepare paycart-form data
							json_object['paycart_form'] = paycart_form;
							paycart.admin.buyer.update(json_object , callBackOnSuccess, callBackOnError);

						});

				// update buyer shipping address
				$('.buyer_shipping_address').click(function()
						{
							var json_object		= {};
							var paycart_form 	= {};

							// get all paycart_form data
							// make sure get selected redio button
							paycart_form['shipping_address_id'] = $('.buyer_shipping_address:radio:checked').val();
							paycart_form['buyer_id']			 = <?php echo $record_id ?> ;

							// prepare paycart-form data
							json_object['paycart_form'] = paycart_form;
							paycart.admin.buyer.update(json_object , callBackOnSuccess, callBackOnError);

						});
				
			});
		})(paycart.jQuery);
	</script>
	
<!-- Html begin	-->
	<div class="" >
<?php
	if (empty($addresses)):
	?>	
		<div class="center">
			<a href="#" class="btn btn-success add-new-address">
				<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo Rb_Text::_('COM_PAYCART_ADD_NEW_ADDRESS');?>
			</a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-question-sign "></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_SUPPORT_LINK');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-book"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_DOCUMENTATION_LINK');?></a>
		</div>
<?php else : ?>
	
	<div class="row-fluid">
		<a href="#" class="btn btn-success add-new-address pull-right">
				<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo Rb_Text::_('COM_PAYCART_BUYERADDRESS_ADD');?>
		</a>
	</div>
	
	<hr />
	
	<?php foreach ($addresses as $address_id => $address_details) : ?>
		<?php 
				//preprocessing for default data
				$deafult_checked_shipping_address = "";
				$deafult_checked_billing_address  = "";
				 
				if ( $shipping_address_id == $address_details->buyeraddress_id) :
					$deafult_checked_shipping_address = "checked";
				endif;

				if ( $billing_address_id == $address_details->buyeraddress_id) :
					$deafult_checked_billing_address = "checked";
				endif;
				
				
		?>
	
		<div class="row-fluid">
			<div class="span9">
				<?php
					// @PCTODO : should be formated and use template instead of hardcoded-sequence
					echo 	"<b>{$address_details->to}</b><br/>".
							"{$address_details->address}<br/>".
							"{$address_details->city},{$address_details->state}<br/>".
							"{$address_details->country} {$address_details->zipcode}<br/>".
							"{$address_details->phone1}, {$address_details->phone2} <br/>".
							JText::_('COM_PAYCART_BUYERADDRESS_VAT_NUMBER'). " : {$address_details->vat_number}<br/>"
						; 
				?>
			</div>
			<div class="span3">
			
				<a href="#" class="btn btn btn-success" onClick="edit_buyeraddress(<?php echo $address_details->buyeraddress_id; ?>)">
						<i class="icon-edit icon-white"></i>&nbsp; <?php echo Rb_Text::_('COM_PAYCART_BUYERADDRESS_EDIT');?>
				</a>
				
				
				<label class="radio" >
					<input type="radio" name="buyer_shipping_address" class="buyer_shipping_address"  value="<?php echo $address_details->buyeraddress_id; ?>"  <?php echo $deafult_checked_shipping_address; ?> />
					<?php echo JText::_('COM_PAYCART_BUYER_DEFAULT_SHIPPING_ADDRESS'); ?>
				</label>
				
				<label class="radio " >
					<input type="radio" name="buyer_billing_address" class="buyer_billing_address" value="<?php echo $address_details->buyeraddress_id; ?>"  <?php echo $deafult_checked_billing_address; ?> />
					<?php echo JText::_('COM_PAYCART_BUYER_DEFAULT_ADDRESS_ADDRESS'); ?>
				</label>
				
			</div>
		</div>
		
		<hr />
	<?php endforeach; ?>
<?php endif; ?>

</div>

