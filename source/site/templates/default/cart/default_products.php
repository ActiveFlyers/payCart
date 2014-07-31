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
?>

<div id="pc-cart-products">
 	<!-- top-buttons -->
 	<div class="row-fluid ">
        <h3>
        	<span class="pull-left"> <?php echo JText::_('Cart');?> <span class="muted"><?php echo JText::sprintf(" (%s items) ", count($products))?></span></span>
        	<span class="pull-right text-error"><strong> <?php echo JText::sprintf('Total = %s %s','$ ',$cart->getTotal())?></strong></span>
        </h3>
 	</div>
 	
 	<hr />
 	
 	<div class="clearfix">
		<div class="pull-left ">	 			
			<?php $returnUrl = '';?>
	        <button class="btn btn-large" onClick="rb.url.redirect('<?php echo $returnUrl;?>')"><i class="fa fa-angle-left"></i> <?php echo JText::_('Back');?></button>
	    </div>
	    <div class="pull-right">	 			
	       <button class="btn btn-large btn-primary" type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"></i><?php echo JText::_(' Place Order');?></button>
	    </div>
	</div>

	<!--  products listing  --> 
	<?php foreach($products as $item):?>
	<?php $product = PaycartProduct::getInstance($item->getParticularId());?>
		<div class="row-fluid pc-item">
			
			<div class="pull-left pc-grid-4">
				<h4><img class="thumbnail" src="<?php $media = $product->getCoverMedia(); echo $media['optimized']?>" /></h4>
			</div>
			
			<div class="pull-right pc-grid-8">
				 <h4 class="text-info"><?php echo $product->getTitle(); ?></h4>
				 <p class="pc-item-attribute">
				 	 <?php foreach ($product->getAttributeValues() as $attributeId => $optionId):?>
	                     <?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
					 	<span class="muted"><?php echo $instance->getTitle();?></span> &nbsp;<span><?php $options = $instance->getOptions(); echo $options[array_shift($optionId)]->title;?></span><br /> 	
					<?php endforeach;?>
						<span class="muted">unit price:</span> &nbsp;<span><?php //echo PaycartFactory::getConfig('currency'); 
					     echo $item->getUnitPrice(); ?></span><br />
				 	<?php //if($item->tax>0):?>
<!--				 	<span class="muted">+ Tax </span><span><?php //echo $item->tax;?> %</span><br />-->
				 <?php //endif;?> 
				 </p>
				 
				<div class="clearfix">
					<div class="pull-left pc-grid-4">
					 	 <label class="muted"><?php echo Jtext::_("Quantity")?></label>
				 		 <input class="pc-grid-6" type="number" value="<?php echo $item->getQuantity(); ?>" onBlur="paycart.cart.product.updateQuantity(<?php echo $product->getId();?>,this.value)"/>
					</div>
					
					<div class="pull-right text-right">
					 	 <h3>
						 	 <span><?php echo JText::_('Price = ')?></span>
					 		 <span><?php //echo PaycartFactory::getConfig('currency'); 
					 			echo $item->getTotal(); ?> </span>
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
		<span><?php echo JText::_('Estimated Total = ');?></span><span class="text-error"><strong><?php echo $cart->getTotal();?></strong></span>
	</h3>
	<p class="small text-right"><a href="#" ><?php echo JText::_('Delivery charges may apply');?></a></p>
	 
	 <!--  footer buttons --> 
	 <div class="clearfix">
		<div class="pull-left ">	 			
	        <button class="btn btn-large"><i class="fa fa-angle-left"></i> <?php echo JText::_('Back');?></button>
	    </div>
	    <div class="pull-right">	 			
	       <button class="btn btn-large btn-primary" type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"></i><?php echo JText::_(' Place Order');?></button>
	    </div>
	</div>
</div>
<?php 
