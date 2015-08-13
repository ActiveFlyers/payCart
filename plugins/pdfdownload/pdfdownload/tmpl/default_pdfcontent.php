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

<?php 
$formatter  = PaycartFactory::getHelper('format');	
$paid_transaction = new stdClass();
foreach ($transactions as $transaction){
	if($transaction->payment_status == 'payment_complete'){
		$paid_transaction = $transaction;
	}
}
$currency = $cart->getCurrency();
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
					<?php echo nl2br($config_data['company_address']);?><br />
					<?php endif;?>
					<?php if(!empty($config_data['company_phone'])):?>
					<?php echo $config_data['company_phone'];?><br />
					<?php endif;?>
				</p>
			</td>
			
			<td align="center">
			<div >
				<div>
				
					<?php if(!empty($config_data['company_logo'])):?>
							<?php $media = PaycartMedia::getInstance($config_data['company_logo']);?>
							
						<img  style="max-width:150px; width:150px; height:150px" alt="" src="<?php echo $media->getOriginal();?>">												
					 <?php endif;?>
				</div>
			</div>		
			</td>
			
		</tr>
				<tr><td>&nbsp;</td></tr>		
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
			
			<?php  if($cartHelper->isShippableProductExist($cart)):?>
				<?php 
		
					$shipTo			= $shippingAddress->getTo();
					$shipAddress    = $shippingAddress->getAddress();
					$shipCity       = $shippingAddress->getCity();
					$shipState      = $shippingAddress->getStateId();
					$shipCountry	= $shippingAddress->getCountryId();
					$shipZip_code	= $shippingAddress->getZipcode();
					$shipPhone		= $shippingAddress->getPhone();	
				?>
		
				<td class="pull-right span6" >
					<p><b><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING');?></b><br/>
					<?php echo $shipto; ?><br/>
					<?php echo $shipAddress;?><br />
					<?php echo $shipCity. ''. $shipState;?><br />
					<?php echo $shipCountry.'-'.$shipZip_code;?><br />
					<?php echo JText::_('COM_PAYCART_PHONE_NUMBER');?>-<?php echo $shipPhone;?>
					</p>
				</td>
		<?php  endif;?>	
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
					<?php echo $formatter->amount($rb_invoice['total'],true,$currency);?>
					
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
				<th class="span2"><?php echo JText::_("COM_PAYCART_PRODUCT")?></th>
				<th class="span1"><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_BUYER_UNIT_COST")?></th>
				<th class="span1"><?php echo JText::_("COM_PAYCART_QUANTITY")?></th>
				<th class="span2"><?php echo JText::_("COM_PAYCART_PRICE")?>(<?php echo $formatter->currency($currency); ?>)</th>
				<th class="span1"><?php echo JText::_("COM_PAYCART_TAX")?>(<?php echo $formatter->currency($currency); ?>)</th>
				<th class="span2"><?php echo JText::_("COM_PAYCART_DISCOUNT")?>(<?php echo $formatter->currency($currency); ?>)</th>
				<th class="span2"><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_LINE_TOTAL")?>(<?php echo $formatter->currency($currency); ?>)</th>
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
					<td ><?php echo $particular->title.$sku; ?></td>
					<td ><?php echo $formatter->amount($particular->unit_price,false); ?></td>
					<td ><?php echo $particular->quantity; ?></td>
					<td ><?php echo $formatter->amount($particular->price,false); ?></td>
					<td >
						<?php echo $formatter->amount($particular->tax,false); ?>
						&nbsp;
							<?php $key = $particular->type.'-'.$particular->particular_id;?>
							<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE])):?>
							  <a href="#" class="pc-popover" title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
							  	 data-content="<?php echo implode("<hr/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE]);?>" data-trigger="hover">
							 	 <i class="fa fa-info-circle"></i>
							  </a>
							<?php endif;?>
					</td>
					<td >
						<?php echo $formatter->amount($particular->discount,false); ?>
						&nbsp;
						<?php $key = $particular->type.'-'.$particular->particular_id;?>
						<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE])):?>
							  <a href="#" class="pc-popover" title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
							  data-content="<?php echo implode("<hr/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE]);?>" data-trigger="hover">
							 	 <i class="fa fa-info-circle"></i>
							  </a>
						<?php endif;?>
					</td>
					<td class="span2"><?php echo $formatter->amount($particular->total,false); ?></td>
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
					<td><?php echo $formatter->amount($promotion_particular->total);?></td>
					
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
					<td><?php echo $formatter->amount($duties_particular->total);?></td>
				
					<?php $finalTotal += $duties_particular->total;?>
				</tr>
			<?php endif;?>	
				<tr><td colspan="7">&nbsp;</td></tr>	
				<!--=========================================
	           			 	Products total 
				===========================================-->
			
				<tr>
					<td colspan="4">
						&nbsp;
					</td>
					<td colspan="2">
						<b><strong><?php echo JText::_("COM_PAYCART_TOTAL")?>:</strong>
					</td>
					<td><?php echo $formatter->amount($total,false); ?></b></td>
				</tr>
						
				
			<!--=========================================
	               		Shipping Details
			===========================================-->
			<?php $shippingTotal = 0; ?>
			<?php if(!empty($shipping_particular)):?>
			
					<?php foreach ($shipping_particular as $id => $particular):?>
					
							<?php $finalTotal += $particular->total;?>
							<?php $shippingTotal += $particular->total;?>
						
					<?php endforeach;?>
					<?php  if($cartHelper->isShippableProductExist($cart)):?>
						<tr>
							<td colspan="4">
								&nbsp;
							</td>
							<td colspan="2">
								<b><strong><?php echo JText::_('PLG_PAYCART_PDFDOWNLOAD_SHIPPING_AMOUNT')?>:</strong>
							</td>
							<td>&nbsp;<?php echo $formatter->amount($shippingTotal,false);?></b></td>
						</tr>
					<?php endif;?>
			
			<?php  endif;?>
			
			<!-- ======================================== 
				   		Final total
			==========================================-->
				<tr>
					<td colspan="4">
						&nbsp;
					</td>
					<td colspan="2"><b><?php echo JText::_('COM_PAYCART_PAYABLE_AMOUNT')?>:</b></td>
					<td><strong><?php echo $formatter->amount($finalTotal,true,$currency);?></strong></td>
				</tr>
		</tbody>
	</table>
	<br>
	<br>
	<!-- ======================================== 
				   		Note
	==========================================-->
	<?php if(!empty($note)):?>
	<p><?php echo JText::_("PLG_PAYCART_PDFDOWNLOAD_NOTE"); ?>:-</p>
	<p><?php echo $note;?></p>
	<?php endif;?>

</div>
</div>
<?php 

