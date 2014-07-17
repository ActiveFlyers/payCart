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
		$instance = PaycartProductAttribute::getInstance($id);?>
		<div class="control-group paycart-product-attribute-<?php echo $id?>">
			<div class="control-label"><label><?php echo $instance->getTitle();?></label></div>
			<div class="controls"><?php echo $instance->getEditHtml($value);?>
						<button class="btn" id="paycart-product-attribute-delete" type="button" onClick="paycart.admin.product.attribute.deleteAttributeValues('<?php echo $id?>');">
							<?php echo JText::_('COM_PAYCART_DELETE');?>
			 			</button>
			</div>
			<hr/>
		</div>
<?php endforeach;?>
		