<?php

/**
 * @copyright   Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Layouts
 * @contact	support+paycart@readybytes.in
 * @author  Rimjhim Jain
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
<td align="center" colspan="2" height="29" valign="middle" width="65" style="border-top: 2px solid rgb(230, 230, 230); border-bottom: 2px solid rgb(230, 230, 230); color: #5d5d5d;"><strong>Item</strong></td>
</tr>
					
	<?php foreach ($product_particulars as $particular) :?>
		<tr>
		<?php if(!$particular instanceof stdClass):?>
			<?php $particular = $particular->toObject();?>
		<?php endif;?>
		<?php $product = PaycartProduct::getInstance($particular->particular_id); ?>
		<?php $coverMedia = $product->getCoverMedia()?>
		<?php $thumb = @$coverMedia['thumbnail'];?>
		<td align="center" height="29" valign="middle" width="50%">
			<img height="75" border="0" style="border:none" alt="<?php echo $particular->title?>" src="<?php echo $thumb?>"></td>
		<td align="center" height="29" valign="middle" width="50%">
			<strong><a href="<?php echo JUri::root().'index.php?option=com_paycart&view=product&task=display&product_id='.$product->getId();?>" target="_blank"> <?php echo $particular->title;?></a></strong>
		</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php 