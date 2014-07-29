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
 * @param $productId => product id that is being added to cart 
 */

?>

<?php $product =  PaycartProduct::getInstance($productId);?>
<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo JText::_("Added to Cart") ?></h3>
	</div>
</div>

<div id="rbWindowBody">
	<div class="modal-body row-fluid">
	 	<div class="span6 text-center">
	 		<?php $media = $product->getCoverMedia();?>
	 		<img src="<?php echo $media['thumbnail']?>"/>
	 	</div>
	 
	 	<div class="span6">
	 		<h4><?php echo $product->getTitle();?></h4>
	 		<p class="pc-item-attribute">
			 	 <?php foreach ($product->getAttributeValues() as $attributeId => $optionId):?>
	                     <?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
				 	<span class="muted"><?php echo $instance->getTitle();?></span> &nbsp;<span><?php $options = $instance->getOptions(); echo $options[array_shift($optionId)]->title;?></span><br /> 	
				<?php endforeach;?>
			</p>
	 	</div>
	</div>
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
		<button class="btn btn-primary pull-left" onClick="rb.ui.dialog.close();"> 
			<?php echo JText::_('Continue Shopping'); ?> 
		</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true" type="button" onClick="rb.url.redirect('index.php?option=com_paycart&view=cart'); return false;">
			<?php echo JText::_('View Cart') ?> 
		</button>
	</div>
</div>
