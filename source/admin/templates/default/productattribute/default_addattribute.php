<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		rimjhim
*/
defined('_JEXEC') or die();

if(empty($productAttributeIds)){
	return '';
}
?>
<?php foreach ($productAttributeIds as $id => $value):?>
  <?php $selected    = null;
		$attributeId = $value;
		if(is_array($value)){
		 	$selected = array_shift($value);
		 	$attributeId = $id;
		}
			
		$instance = PaycartProductAttribute::getInstance($attributeId);?>
		<div class="control-group paycart-product-attribute-<?php echo $attributeId?>">
			<div class="control-label"><label><?php echo $instance->getTitle();?></label></div>
			<div class="controls"><?php echo PaycartAttribute::getInstance($instance->getType())->getEditHtml($instance, $selected);?>
					<button class="btn" id="paycart-product-attribute-remove" type="button" onClick="paycart.admin.product.attribute.detach('<?php echo $attributeId?>');">
							<?php echo JText::_('COM_PAYCART_DELETE');?>
			 			</button>
			</div>
			<hr/>
		</div>
<?php endforeach;?>
		