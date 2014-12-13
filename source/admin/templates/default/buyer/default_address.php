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
	
<!-- Html begin	-->
	<div class="" >
<?php
	if (empty($addresses)):
	?>	
	<div class="row-fluid">
		<div class="center muted">
			<div>
				<h1>&nbsp;</h1>
				<i class="fa fa-user fa-5x"></i>
			</div>
			
			<div>
				<h3><?php echo JText::_('COM_PAYCART_ADMIN_STATE_GRID_BLANK_MSG');?></h3>
			</div>
		</div>
	</div>

	<div class="row-fluid">	
		<div class="center">
			<a href="#" class="btn btn-success add-new-address" onClick="paycart.admin.buyeraddress.window(<?php echo "'$record_id'";  ?>,0);">
				<i class="icon-plus-sign icon-white"></i>&nbsp;
				<?php echo Rb_Text::_('COM_PAYCART_BUYERADDRESS_ADD_NEW');?>
			</a>
		</div>
	</div>
<?php else : ?>
	
	<div class="row-fluid">
		<a href="#" class="btn btn-success add-new-address pull-right" onClick="paycart.admin.buyeraddress.window(<?php echo "'$record_id'";  ?>,0);">
				<i class="icon-plus-sign icon-white"></i>&nbsp;
				<?php echo Rb_Text::_('COM_PAYCART_BUYERADDRESS_ADD_NEW');?>
		</a>	
	</div>
	
	<?php foreach ($addresses as $address_id => $address_details) : ?>
			<div class="span4">
				<a href="#" onClick="paycart.admin.buyeraddress.edit(<?php echo $address_details->buyeraddress_id; ?>)">
						<i class="icon-edit icon-white"></i>
				</a>
				<?php	
					$attrId = PaycartBuyeraddress::getInstance($address_id); 
				    $address = (object)$attrId->toArray() ;
				    echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $address, PAYCART_LAYOUTS_PATH);
				?>				
			</div>
			
			<!-- =======================================================================
										 -----	START  -----
			  	 Working code to set address as default shipping and billing address
			  
			     Note : Commenting it out bcz we are not utilizing default addresses			
			 =========================================================================== -->
			
			<?php 
				//preprocessing for default data
				
//				$deafult_checked_shipping_address = "";
//				$deafult_checked_billing_address  = "";
//				 
//				if ( $shipping_address_id == $address_details->buyeraddress_id) :
//					$deafult_checked_shipping_address = "checked";
//				endif;
//
//				if ( $billing_address_id == $address_details->buyeraddress_id) :
//					$deafult_checked_billing_address = "checked";
//				endif;
				
				
			?>
		
			<!--<div class="span3">
			
				<label class="radio" >
					<input	type="radio" name="buyer_shipping_address" class="buyer_shipping_address"  
							value="<?php echo $address_details->buyeraddress_id; ?>"  <?php echo $deafult_checked_shipping_address; ?>
							onClick="paycart.admin.buyer.shipping_address.update(<?php echo $address_details->buyeraddress_id; ?>, <?php echo $record_id; ?>)" 
					/>
					<?php echo JText::_('COM_PAYCART_BUYER_DEFAULT_SHIPPING_ADDRESS'); ?>
				</label>
				
				<label class="radio " >
					<input 	type="radio" name="buyer_billing_address" class="buyer_billing_address"
							value="<?php echo $address_details->buyeraddress_id; ?>"  <?php echo $deafult_checked_billing_address; ?>
							onClick="paycart.admin.buyer.billing_address.update(<?php echo $address_details->buyeraddress_id; ?>, <?php echo $record_id; ?>)" 
					/>
					<?php echo JText::_('COM_PAYCART_BUYER_DEFAULT_BILLING_ADDRESS'); ?>
				</label>
				
			</div>-->
			
			<!-- =======================================================================
			  							-----	END  -----		
			 =========================================================================== -->
		
	<?php endforeach; ?>
<?php endif; ?>

</div>

