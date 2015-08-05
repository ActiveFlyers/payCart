<?php 
/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	    GNU/GPL, see LICENSE.php
* @package	    PAYCART
* @subpackage	PDFEXPORT
* @contact 	    support+PAYCART@readybytes.in
*/

// no direct access
if(defined('_JEXEC')===false) die();
?>
<style>

body {
	padding: 1em;
	font-family: dejavusans;
	font-size: 16px;
}
</style>
<?php 
$formatter  = PaycartFactory::getHelper('format');	
$paid_transaction = new stdClass();
foreach ($transactions as $transaction){
	if($transaction->payment_status == 'payment_complete'){
		$paid_transaction = $transaction;
	}
}
?>
<div <?php echo ($resultCount > 1)?'style="page-break-before:always"': ''?>> 
<div> 
	<!-- 
	--------------------------------------------------------------------------------------------------------
				Company Logo, Address , User's and additional details			
	--------------------------------------------------------------------------------------------------------
    -->
	<table  >
		<tr>
			
			<td>
				<h4 >
					<?php if(!empty($config_data['company_name'])):?>
					<?php echo $config_data['company_name']; ?> 
					<?php endif;?>
				</h4>
				<p>
					<?php if(!empty($config_data['company_address'])):?>
					<?php echo $config_data['company_address'];?><br />
					<?php endif;?>
					<?php if(!empty($config_data['company_phone'])):?>
					<?php echo $config_data['company_phone'];?><br />
					<?php endif;?>
				</p>
			</td>
			
			<td align="center">
			<div class="media">
				<div  style="max-width:150px; width:150px;">
				
					<?php if(!empty($config_data['company_logo'])):?>
							<?php $media = PaycartMedia::getInstance($config_data['company_logo']);?>
							
						<img class="media-object" alt="" src="<?php echo $media->getOriginal();?>">												
					 <?php endif;?>
				</div>
			</div>		
			</td>
			
		</tr>
				<tr><td><h3>&nbsp;</h3></td></tr>		
		<tr>
		<?php 
		
				$to			= $billingAddress->getTo();
				$address    = $billingAddress->getAddress();
				$city       = $billingAddress->getCity();
				$state      = $billingAddress->getStateId();
				$country	= $billingAddress->getCountryId();
				$zip_code	= $billingAddress->getZipcode();
				$tax_number	= $billingAddress->getVatnumber();
				$phone		= $billingAddress->getPhone();
				
		?>
		
			<?php 
		
				$shipTo			= $shippingAddress->getTo();
				$shipAddress    = $shippingAddress->getAddress();
				$shipCity       = $shippingAddress->getCity();
				$shipState      = $shippingAddress->getStateId();
				$shipCountry	= $shippingAddress->getCountryId();
				$shipZip_code	= $shippingAddress->getZipcode();
				$shipPhone		= $shippingAddress->getPhone();	
		?>
		
			<td class="pull-left span6">
				<p><b><?php echo JText::_('COM_PAYCART_ADDRESS_BILLING');?></b><br/>
				<?php echo $to; ?><br/>
				<?php echo $address;?><br />
				<?php echo $city. ''. $state;?><br />
				<?php echo $country.'-'.$zip_code;?><br />
				<?php echo JText::_('COM_PAYCART_PHONE_NUMBER');?>-<?php echo $phone;?><br/>
				<?php if(!empty($tax_number)):?>
				<?php echo "<br/>".JText::_('PLG_PAYCART_PDFDOWNLOAD_BUYER_TAX_NUMBER').":".$tax_number;?>
				<?php endif;?>
				</p>
			</td>
			
			<td class="pull-right span6" >
				<p><b><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING');?></b><br/>
				<?php echo $shipto; ?><br/>
				<?php echo $shipAddress;?><br />
				<?php echo $shipCity. ''. $shipState;?><br />
				<?php echo $shipCountry.'-'.$shipZip_code;?><br />
				<?php echo JText::_('COM_PAYCART_PHONE_NUMBER');?>-<?php echo $shipPhone;?>
				</p>
			</td>
			
		</tr>
		<tr><td><h3>&nbsp;</h3></td></tr>
	</table>
	
<br/>
    
		<table class="table table-bordered">
			<tr class="row-fluid">
				<td class="span6">
					
				<b>	<?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_INVOICE_SERIAL');?>:</b>
						<?php echo $rb_invoice['serial']; ?><br/>
				</td>	
				
				<td class="span6">
				    	<?php if(!empty($paid_transaction->gateway_txn_id)):?>
				    	<b><?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_INVOICE_TRANSACTION_KEY');?>:</b>
				    	<?php endif;?> 
				    	<?php if(!empty($paid_transaction->gateway_txn_id)):?>
						<?php echo $paid_transaction->gateway_txn_id;?>
						<?php endif;?> <br/><br/>
				</td>
			</tr>
			
			<tr class="row-fluid">
				 <td class="span6">   	
				    	<?php if(!empty($rb_invoice['processor_type'])):?>
				    	<b><?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_PAYMENT_METHOD');?>:</b>
				    	<?php endif;?>	
						<?php if(!empty($rb_invoice['processor_type'])):?>
						<?php echo $rb_invoice['processor_type'];?>
						<?php endif;?>
				</td>
				
				<td class="span6">
					
					<b>	<?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_CART_STATUS');?>:</b>
						<?php echo JText::_($cart->getStatus());?>
				</td>	
			</tr>
		
	    	<tr class="row-fluid">
	    		<td class="span6">
	    			<b><?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_PAID_ON');?>:</b>
	    			<?php $paid_date = $cart->getPaidDate();?>
			        <?php echo $this->getHelper('format')->date($paid_date); ?>			
				</td>
				
	    		<td class="span6">
	    			<b><?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_TOTAL'); ?>:</b>
					<?php echo $currency_symbol." ".number_format($rb_invoice['total'], 2);?>
					
	    		</td>
	    	</tr>
			</table>
				
		<br><br>

  		<table class="table table-bordered">
		
		<!--=========================================
	              Individual Product Details 
		===========================================-->
		
		<thead>
			<tr style="background: #ccc;">
				<th><?php echo JText::_("COM_PAYCART_PRODUCT")?></th>
				<th><?php echo JText::_("COM_PAYCART_UNIT_PRICE")?></th>
				<th><?php echo JText::_("COM_PAYCART_QUANTITY")?></th>
				<th><?php echo JText::_("COM_PAYCART_PRICE")?></th>
				<th><?php echo JText::_("COM_PAYCART_TAX")?></th>
				<th><?php echo JText::_("COM_PAYCART_DISCOUNT")?></th>
				<th><?php echo JText::_("COM_PAYCART_TOTAL")?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$price    = 0;
				$tax      = 0;
				$discount = 0;
				$total    = 0;
			?>
			
			
			<?php foreach ($product_particular as $particular) :?>
				<?php $price    += $particular->price;?>
				<?php $tax      += $particular->tax;?>
				<?php $discount += $particular->discount;?>
				<?php $total    += $particular->total;?>
				<tr>
					<?php $product =  PaycartProduct::getInstance($particular->particular_id);
					$sku 		   = $product->getSKU();
					$sku		   = !empty($sku)?"(".$sku.")":"";
					?>
					<td><?php echo $particular->title.$sku; ?></td>
					<td><?php echo $formatter->amount($particular->unit_price, true, $currencyId); ?></td>
					<td><?php echo $particular->quantity; ?></td>
					<td><?php echo $formatter->amount($particular->price, true, $currencyId); ?></td>
					<td>
						<?php echo $formatter->amount($particular->tax, true, $currencyId); ?>
						&nbsp;
							<?php $key = $particular->type.'-'.$particular->particular_id;?>
							<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE])):?>
							  <a href="#" class="pc-popover" title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
							  	 data-content="<?php echo implode("<hr/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE]);?>" data-trigger="hover">
							 	 <i class="fa fa-info-circle"></i>
							  </a>
							<?php endif;?>
					</td>
					<td>
						<?php echo $formatter->amount($particular->discount, true, $currencyId); ?>
						&nbsp;
						<?php $key = $particular->type.'-'.$particular->particular_id;?>
						<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE])):?>
							  <a href="#" class="pc-popover" title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
							  data-content="<?php echo implode("<hr/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE]);?>" data-trigger="hover">
							 	 <i class="fa fa-info-circle"></i>
							  </a>
						<?php endif;?>
					</td>
					<td><?php echo $formatter->amount($particular->total, true, $currencyId); ?></td>
				</tr>
			<?php endforeach;?>
				
		
			<!--=========================================
	           			Cart Discount and taxes 
			===========================================-->
			
			<?php $finalTotal = $total; ?>	
			<?php if(!empty($promotion_particular)):?>
				<tr>
					<td colspan="7"><h4><?php echo JText::_("COM_PAYCART_CART_DISCOUNT_AND_TAX")?></h4></td>
				</tr>
			
				<?php $promotion_particular = array_shift($promotion_particular);?>
				<tr>
					<td colspan="6">
						<?php echo $promotion_particular->title;?>
						<br>
						<small>
							<?php $key = $promotion_particular->type.'-'.$promotion_particular->particular_id;?>
							<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE])):?>
								 	<?php echo '('.implode("<br/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE]).')';?>
							<?php endif;?>
						</small>
					</td>
					<td><?php echo $formatter->amount($promotion_particular->total, true, $currencyId);?></td>
					
					<?php $finalTotal += $promotion_particular->total;?>
				</tr>
			<?php endif;?>
		
			<?php if(!empty($duties_particular)):?>
				<?php $duties_particular = array_shift($duties_particular);?>
				<tr>
					<td colspan="6">
						<?php echo $duties_particular->title;?>
						<br>
						<small>
							<?php $key = $duties_particular->type.'-'.$duties_particular->particular_id;?>
							<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE])):?>
								 	<?php echo '('.implode("<br/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE]).')';?>
							<?php endif;?>
						</small>
					</td>
					<td><?php echo $formatter->amount($duties_particular->total, true, $currencyId);?></td>
				
					<?php $finalTotal += $duties_particular->total;?>
				</tr>
			<?php endif;?>
				
				
				<!--=========================================
	           			 	Products total 
				===========================================-->
			
				<tr>
				
					<td colspan="5">
						&nbsp;
					</td>
					<td>
						<b><strong><?php echo JText::_("COM_PAYCART_TOTAL")?></strong>
					</td>
					<td><?php echo $formatter->amount($total, true, $currencyId); ?></b></td>
				</tr>
						
				
			<!--=========================================
	               		Shipping Details
			===========================================-->
			<?php $shippingTotal = 0; ?>
			<?php if(!empty($shipping_particular)):?>
			
				<?php foreach ($shipping_particular as $id => $particular):?>
					
						<?php // if cart is locked then $particular->params is stdclass object, else it will object of JRegistry?>
						<?php if($cart->isLocked()):?>
							<?php $params = $particular->params;?>
						<?php else:?>
							<?php $params = new stdClass();?>
							<?php $params->product_list = $particular->params->get('product_list');?>
							<?php $params->delivery_date = $particular->params->get('delivery_date');?>
						<?php endif;?>
						
						
						<?php echo $formatter->amount($particular->unit_price, true, $currencyId);?>
				
						<?php $finalTotal += $particular->total;?>
						<?php $shippingTotal += $particular->total;?>
					
				<?php endforeach;?>
				
				<tr>
					<td colspan="5">
						&nbsp;
					</td>
					<td>
						<b><strong><?php echo JText::_('COM_PAYCART_SHIPPING_DETAILS')?></strong>
					</td>
					<td><?php echo $formatter->amount($shippingTotal, true, $currencyId);?></b></td>
				</tr>
			
			<?php endif;?>
			
			<!-- ======================================== 
				   		Final total
			==========================================-->
				<tr>
					<td colspan="5">
						&nbsp;
					</td>
					<td><b><?php echo JText::_('COM_PAYCART_PAYABLE_AMOUNT')?></b></td>
					<td><strong><?php echo $formatter->amount($finalTotal, true, $currencyId);?></strong></td>
				</tr>
		</tbody>
	</table>
	<br>
	<br>
	<!-- ======================================== 
				   		Note
	==========================================-->
	<p><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_NOTE"); ?>:-</p>
	<p><?php echo $note;?></p>

</div>
</div>
<?php 

