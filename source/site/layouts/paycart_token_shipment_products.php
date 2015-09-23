<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Layouts
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain
*/

/**
 * List of Populated Variables
 * $displayData = have all required data
 * $displayData->products = product details
 *
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

if(isset($displayData->products)){
	$productDetails = $displayData->products;
}

$product_particulars   = $displayData->product_particulars;
$promotion			   = $displayData->promotion;
$formatter 			   = PaycartFactory::getHelper('format');
$shippingCharge		   = $displayData->shipping_charge;	
?>

<table cellpadding="0" cellspacing="0" align="left" border="0" width="100%">
<tbody>
	<tr>
		<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>S. No.</strong></td>
		<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Item Name </strong></td>
		<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Quantity</strong></td>
		<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Total</strong></td>
	</tr>
	<?php $count = 0;?>
	<?php $shipment_total = 0;?>
	<?php $grand_total = 0;?>
	<?php foreach ($productDetails as $key => $value):?>
		<tr>
			<td align="center" height="29" valign="middle" width="65"><?php echo ++$count;?></td>
			<td align="center" height="29" valign="middle" width="65">
				<?php $product = PaycartProduct::getInstance($value['product_id']);?>
				<strong><?php echo $product->getTitle();?></strong>
				<small>
				       <?php 
						 echo !empty($product->getSKU())?JText::_('COM_PAYCART_SKU').' - '.$product->getSKU():'';	 
				       ?>
			 	</small>
			 </td>
			<td align="center" height="29" valign="middle" width="65"><?php echo $value['quantity']?></td>
			<td align="center" height="29" valign="middle" width="65">
				<?php $total = ($product_particulars[$value['product_id']]->total*$value['quantity'])/$product_particulars[$value['product_id']]->quantity;?>
				<?php $shipment_total += $total;?>
				<?php echo $formatter->amount($total);?>
			</td>
		</tr>
	<?php endforeach;?>	
</tbody>
</table>
<table cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td style="padding: 10px; color: #5d5d5d;">
				<table cellpadding="3" cellspacing="0" align="right" border="0">
					<tbody>
						<tr>
							<td align="right" width="200"><strong><?php echo JText::_('COM_PAYCART_NOTIFICATION_TOTAL_AMOUNT')?> :<br> </strong></td>
							<td align="left" width="85"><?php $grand_total += $shipment_total; echo $formatter->amount($shipment_total,true);?></td>
						</tr>
						
						<?php if(!empty($promotion)):?>
							<tr>
								<td align="right" width="200"><?php echo JText::_('COM_PAYCART_NOTIFICATION_TOTAL_OFFER_DISCOUNT')?> (-) :</td>
								<td align="left" width="85"><?php $grand_total += $promotion; echo $formatter->amount(-$promotion,true);?></td>
							</tr>
						<?php endif;?>
						<?php if(!empty($shippingCharge)):?>
							<tr>
								<td align="right" width="200"><?php echo JText::_('COM_PAYCART_NOTIFICATION_SHIPPING_CHARGES')?> (+) :</td>
								<td align="left" width="85"><?php $grand_total += $shippingCharge; echo $formatter->amount($shippingCharge,true);?></td>
							</tr>
						<?php endif;?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding: 10px; color: #343434; font-size: 18px;" bgcolor="#ededed">
				<table cellpadding="3" cellspacing="0" align="right" border="0">
					<tbody>
						<tr>
							<td align="right" height="50" valign="middle" width="180">
								<h4><?php echo JText::_('COM_PAYCART_PAYABLE_AMOUNT')?></h4>
							</td>
							<td align="left" width="100">
								<h4><?php echo $formatter->amount($grand_total,true);?></h4>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid rgb(230, 230, 230);"></td>
		</tr>
	</tbody>
</table>