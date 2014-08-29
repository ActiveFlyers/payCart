<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/**
 * Available variables 
 * 
 * @param $products => array of product particulars
 * @param $cart => object of PaycartCart 
 */

$currencyId = $cart->getCurrency();
?>
<?php if(!empty($products)):?>
<div id="pc-cart-products">
 	<!-- top-buttons -->
 	<div class="row-fluid ">
        <h3>
        	<span class="pull-left"> <?php echo JText::_('COM_PAYCART_CART');?> 
	        	<span class="muted">
	        		<?php $count = count($products);?>
	        		<?php $string = ($count > 1)?"COM_PAYCART_CART_ITEMS":"COM_PAYCART_CART_ITEM";?>
	        		<?php echo '('.$count.' '.JText::_($string).')'; ?>
	        	</span>
        	</span>
        	<span class="pull-right text-error"><strong> <?php echo JText::_('COM_PAYCART_TOTAL').' = '.$formatter->amount($cart->getTotal(),true,$currencyId);?></strong></span>
        </h3>
 	</div>
 	
 	 <br>
 	
 	<div class="clearfix">
		<div class="pull-left ">	 			
	        <button class="btn btn-large" type="button" onClick="window.history.back();return false;"><i class="fa fa-angle-left"></i> <?php echo JText::_('COM_PAYCART_BACK');?></button>
	    </div>
	    <div class="pull-right">	 			
	       <button class="btn btn-large btn-primary" type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"></i><?php echo JText::_('COM_PAYCART_PLACE_ORDER');?></button>
	    </div>
	</div>
 	
 	<hr />

	<!--  products listing  --> 
	<?php foreach($products as $item):?>
	<?php $product = PaycartProduct::getInstance($item->getParticularId());?>
		<div class="row-fluid pc-item">
			
			<div class="pull-left pc-grid-4">
				<h4><img class="thumbnail" src="<?php $media = $product->getCoverMedia(); echo $media['thumbnail']?>" /></h4>
			</div>
			
			<div class="pull-right pc-grid-8">
				 <h4 class="text-info"><?php echo PaycartHtml::link('index.php?option=com_paycart&view=product&product_id='.$product->getId(), $product->getTitle()); ?></h4>
				 <p class="pc-item-attribute">
				 	 <?php foreach ($product->getAttributeValues() as $attributeId => $optionId):?>
	                     <?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
					 	<span><?php echo $instance->getTitle();?></span> &nbsp;<span><?php $options = $instance->getOptions(); echo $options[$optionId]->title;?></span><br /> 	
					<?php endforeach;?>
						<span><?php echo JText::_("COM_PAYCART_UNIT_PRICE")?> :</span>
						
						<span><?php echo $formatter->amount($item->getUnitPrice(),true,$currencyId); ?></span><br />
				 	<?php //if($item->tax>0):?>
<!--				 	<span class="muted">+ Tax </span><span><?php //echo $item->tax;?> %</span><br />-->
				 <?php //endif;?> 
				 </p>
				 
				<div class="clearfix">
					<div class="pull-left pc-grid-4">
					 	 <label><big><?php echo Jtext::_("COM_PAYCART_QUANTITY")?></big></label>
				 		 <span>
				 		 	<input class="pc-grid-6 pc-cart-quantity-<?php echo $product->getId()?>" type="number" min="1" value="<?php echo $item->getQuantity(); ?>"/>&nbsp;
				 		 	<a href="javascript:void(0);" onClick="paycart.cart.product.updateQuantity(<?php echo $product->getId();?>)"><i class="fa fa-refresh"></i></a>
				 		 </span>
				 		 <div class="pc-grid-12 text-error pc-cart-quantity-error-<?php echo $product->getId()?>"></div>
					</div>
					
					<div class="pull-right text-right">
					 	 <h3>
						 	 <span><?php echo JText::_('COM_PAYCART_PRICE')." = "?></span>
					 		 <span><?php echo $formatter->amount($item->getTotal(),true,$currencyId); ?></span>
					 	</h3>
					</div>
			 	</div> 
			 	
			 	<div class="clearfix">
				 	 <a class="pull-right muted" href="javascript:void(0)" onClick="paycart.cart.product.remove(<?php echo $product->getId();?>)"><i class="fa fa-trash-o fa-lg">&nbsp;</i></a>
			 	</div>
			 	
			</div>
			
		</div>
		<hr />
	<?php endforeach;?>
		
	<h3 class="text-right">
		<span><?php echo JText::_('COM_PAYCART_ESTIMATED_TOTAL')." = ";?></span><span class="text-error"><strong><?php echo $formatter->amount($cart->getTotal(),true,$currencyId); ?></strong></span>
	</h3>
<!--	<p class="small text-right"><a href="#" ><?php //echo JText::_('Delivery charges may apply');?></a></p>-->
	 
	 <!--  footer buttons --> 
	 <div class="clearfix">
		<div class="pull-left ">	 			
	        <button class="btn btn-large" type="button" onClick="window.history.back();return false;"><i class="fa fa-angle-left"></i> <?php echo JText::_('COM_PAYCART_BACK');?></button>
	    </div>
	    <div class="pull-right">	 			
	       <button class="btn btn-large btn-primary" type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"></i><?php echo JText::_('COM_PAYCART_PLACE_ORDER');?></button>
	    </div>
	</div>
</div>
<?php else:?>
<div id="pc-cart-products">
 	<div class="row-fluid row-fluid text-center">
 		<h4 class="muted"><?php echo JText::_('COM_PAYCART_CART_EMPTY')?></h4>
 		<div class="row-fluid">
 			<button type="button" class="btn btn-large btn-primary" onclick="rb.url.redirect('<?php echo 'index.php?option=com_paycart';?>'); return false;"> <i class="fa fa-chevron-left"></i> &nbsp; <?php echo JText::_("COM_PAYCART_CONTINUE_SHOPPING");?></button>
 		</div>
 	</div>
</div>

<?php endif;?>
<?php 
