<?php

/**
 * @copyright   Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Layouts
 * @contact	support+paycart@readybytes.in
 * @author 	Manish Trivedi  
 */

defined('_JEXEC') or die();

/**
 * List of Populated Variables
 * $displayData = have all required data 
 * $displayData->product_particulars 
 * $displayData->shipping_particulars
 * $displayData->promotion_particulars
 * 
 */

$product_particulars   = $displayData->product_particulars;
$shipping_particulars  = $displayData->shipping_particulars;
$promotion_particulars = $displayData->promotion_particulars;
$formatter 			   = PaycartFactory::getHelper('format');

if (count($product_particulars) <= 0 ) {
    return ;
}
?>

<!--</tbody>-->
<!--</table>-->
<!--<table cellpadding="0" cellspacing="0" width="100%">-->
<!--	<tbody>-->
<table cellpadding="0" cellspacing="0" align="left" border="0" width="100%">
<tbody>
<tr>
<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>S. No.</strong></td>
<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Item Name </strong></td>
<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Quantity</strong></td>
<td align="center" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Price</strong></td>
</tr>
	<?php $count          = 0;?>
	<?php $promotion      = 0;?>
	<?php $total_shipping = 0;?>
	<?php $grand_total    = 0;?>
	<?php $total          = 0;?>
	<?php $max_estDeliveryDate =null;?>
	<?php foreach ($shipping_particulars as $sp):?>
		<?php if(!$sp instanceof stdClass):?>
			<?php $sp = $sp->toObject()?>
		<?php endif;?>
		<?php $total_shipping += $sp->total;?>	
		<?php $date = new Rb_Date($sp->params->delivery_date);?>		
   		<?php if(empty($max_estDeliveryDate)):?>
   			<?php $max_estDeliveryDate = $date;?>
   			<?php continue?>
   		<?php endif;?>
									   													   				
		<?php $max_estDeliveryDate = ($max_estDeliveryDate->toUnix() < $date->toUnix())?$date:$max_estDeliveryDate; ?>
	<?php endforeach;?>
					
	<?php foreach ($product_particulars as $particular) :?>
		<tr>
		<?php if(!$particular instanceof stdClass):?>
			<?php $particular = $particular->toObject();?>
		<?php endif;?>
		<td align="center" height="29" valign="middle" width="65"><strong><?php echo ++$count;?></strong></td>
		<td align="center" height="29" valign="middle" width="65">
			<strong><?php echo $particular->title;?></strong>
			<small>
			       <?php 
			       	 $product = PaycartProduct::getInstance($particular->particular_id); 
					 echo !empty($product->getSKU())?JText::_('COM_PAYCART_SKU').' - '.$product->getSKU():'';	 
			       ?>
		 	</small>
		</td>
		<td align="center" height="29" valign="middle" width="65"><strong><?php echo $particular->quantity?></strong></td>
		<td align="center" height="29" valign="middle" width="65">
			<strong>
				<?php $total += $particular->total;
					  echo $formatter->amount($particular->total, true);
				?>
			</strong>
		</td>
		</tr>
	<?php endforeach;?>
	<?php if($max_estDeliveryDate):?>			
		<tr>
			<td style="color: #000000; font-size: 12px; padding-top: 8px; padding-bottom: 8px;" align="center" bgcolor="#FFFFE2" colspan="5"><?php echo JText::_('COM_PAYCART_NOTIFICATION_ESTIMATED_DELIVERY_BY')?><strong><?php echo ' - ',$formatter->date($max_estDeliveryDate);?></strong></td>
		</tr>
	<?php endif;?>
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
							<td align="left" width="85"><?php $grand_total += $total; echo $formatter->amount($total,true);?></td>
						</tr>
						<?php foreach ($promotion_particulars as $pp):?>
							<?php if(!$pp instanceof stdClass):?>
								<?php $pp = $pp->toObject()?>
							<?php endif;?>
							<?php $promotion += $pp->total;?>	
						<?php endforeach;?>
						<?php if(!empty($promotion)):?>
							<tr>
								<td align="right" width="200"><?php echo JText::_('COM_PAYCART_NOTIFICATION_TOTAL_OFFER_DISCOUNT')?> (-) :</td>
								<td align="left" width="85"><?php $grand_total += $promotion; echo $formatter->amount(-$promotion,true);?></td>
							</tr>
						<?php endif;?>
					
						<?php if(!empty($total_shipping)):?>
							<tr>
								<td align="right" width="200"><?php echo JText::_('COM_PAYCART_NOTIFICATION_SHIPPING_CHARGES')?> (+) :</td>
								<td align="left" width="85"><?php $grand_total += $total_shipping; echo $formatter->amount($total_shipping,true);?></td>
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
<?php 
