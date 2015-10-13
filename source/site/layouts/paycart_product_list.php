<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
defined('_JEXEC') or die();

/**
 * List of Populated Variables
 * $displayData : Array of stdclass objects related to products with all their properties, some of those are :-
 * 
 * $displayData->product_id->price
 * $displayData->product_id->title
 * $displayData->product_id->inStock
 * $displayData->product_id->media : It is an array containing data of coverImage of product
 * 									 	 ( array ('optimized'  => LOCATION OF OPTIMIZED IMAGE,
 * 												   'original'  => LOCATION OF ORIGINAL IMAGE,
 * 												   'thumbnail' => LOCATION OF THUMBNAIL IMAGE,
 * 												   'squared'   => LOCATION OF SQUARED IMAGE, 
 * 												  )
 * 										  )
 * 
 */
?>

<?php foreach($displayData->products as $product_id =>$product) : ?>
	<?php $inStock   = $product->inStock;?>  
	<?php $class     = !$inStock?'pc-product-stockout':''?>  
	
	<div class="pc-product-outer thumbnail<?php echo isset($displayData->pagination_start)? 'pc-next-'.$displayData->pagination_start:''?>" >
		<div class='pc-product '>
			<?php echo $product_id;?>
			<?php $media = $product->media;?>      
			<?php $url   = PaycartRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product->product_id);?>
			<a class="pc-clickable" href="<?php echo $url;?>">
				<div class="pc-product-content">
					<?php if(!empty($class)):?>
						<strong><span class="<?php echo $class;?> text-center"><?php echo strtoupper(JText::_("COM_PAYCART_PRODUCT_IS_OUT_OF_STOCK"));?></span></strong>
					<?php endif;?>  
					<img class="<?php echo !$inStock?'pc-product-stockout-image':'';?>" src="<?php echo isset($media['optimized'])?$media['optimized']:'';?>">
				
					<p class="pc-product-title pc-break-word muted"><?php echo $product->title;?></p>
				</div>
			</a>		
			<?php $mrp = '';
				 if($product->retail_price > $product->price){
				 	$mrp = $product->formatted_retail_price;
				 	$percentage = (($product->retail_price-$product->price)*100)/$product->retail_price;
				 }
			?>
		
			<h4>
					<span class="amount"><?php echo ($mrp)?'<strike class="muted"><small>'.$mrp.'</small></strike>&nbsp;':$mrp?></span>
					<span class="amount"><?php echo $product->formatted_price?></span>
					
					<?php if($mrp):?>
						<?php $percentage = floatval(PaycartFactory::getHelper('format')->amount($percentage,false));?>
						<span class="pc-discount label label-important"><?php echo '- '.$percentage.'%';?></span>
					<?php endif;?>
			</h4>
			<?php $url = 'index.php?option=com_paycart&view=cart&task=addToCart&product_id='.$product->product_id; ?>
			<div class="pc-add-to-cart hidden-phone" >
				<?php if(!in_array($product->product_id, $displayData->currentCartProducts)):?>
					<input type="button" class="btn" data-pc-selector="<?php echo $product->product_id; ?>" onclick="rb.ajax.go('<?php echo $url; ?>'); return false;" style="text-transform: uppercase; " value="<?php echo JText::_("COM_PAYCART_CART_ADD_TO_CART"); ?>">
					<a href="#" data-pc-selector="showCheckoutBtn<?php echo $product->product_id; ?>" onclick="rb.url.redirect(&quot;index.php?option=com_paycart&view=cart&quot;); return false;"  style="display:none"><?php echo JText::_("COM_PAYCART_CART_VIEW_CART"); ?></a>
				<?php else :?>
					<input type="button" class="btn btn-success" style="text-transform: uppercase; " value="✔ Added">
					<a href="#" onclick="rb.url.redirect(&quot;index.php?option=com_paycart&view=cart&task=checkout&quot;); return false;" style="display:block">
		            <?php echo JText::_("COM_PAYCART_CART_VIEW_CART"); ?></a>
					
				<?php endif;?>
			</div>
		</div>
	</div>
<?php endforeach;?>



<style>
 .pc-product-outer .pc-add-to-cart{
    visibility:hidden;
    opacity:0;
  	-webkit-transition: visibility 0.2s linear, opacity 0.2s linear;
  	-moz-transition: visibility 0.2s linear, opacity 0.2s linear;
  	-o-transition: visibility 0.2s linear, opacity 0.2s linear;
}

 .pc-product-outer:hover {
    border: 1px solid transparent;
    box-shadow: 0 0 6px -3px #000;
}

.pc-product-outer:hover .pc-add-to-cart{
     visibility:visible;
     opacity:1;
     transition:all 0.2s ease-in-out 0s
}

.pc-add-to-cart {
    color:#999; 
    -webkit-transition-property:color; 
    -webkit-transition-duration: 1s, 1s; 
    -webkit-transition-timing-function: linear, ease-in;
}

.pc-add-to-cart:hover {
    color: #333;
}

</style>

<?php 
