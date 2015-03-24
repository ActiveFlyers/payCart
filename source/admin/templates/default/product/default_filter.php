<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

echo $this->loadTemplate('js');
?>
<div>
	<?php echo Rb_Html::_('rb_html.text.filter', 'title', 'product', $filters, 'filter_paycart');?>
	<?php echo Rb_Html::_('rb_html.boolean.filter', 'visible', 'product', $filters, 'filter_paycart','COM_PAYCART_ADMIN');?>
	<?php echo Rb_Html::_('rb_html.boolean.filter', 'published', 'product', $filters, 'filter_paycart','COM_PAYCART_ADMIN');?>
	<?php echo paycartHtml::_('paycarthtml.category.filter','productcategory_id','product',$filters,'filter_paycart')?>
	<?php echo Rb_Html::_('rb_html.range.filter', 'pri ce', 'product', $filters,'text','filter_paycart');?>
	<?php $quantity = '';?>
	<?php if(isset($filters['quantity'])):?>
		<?php $quantity = (strlen($filters['quantity'][0]) == 1)?$filters['quantity'][0]:$filters['quantity'][1];?>
	<?php endif;?>
	<input type="radio" id='filter_paycart_product_quantity_from' name="filter_paycart_product_quantity_range" value="0" <?php echo (strlen($quantity) == 1 && $quantity == 0)?'checked':'';?> 
		   data-pc-selector="filter_quantity_from"/> Out-of-stock
	<input type="radio" id='filter_paycart_product_quantity_to' name="filter_paycart_product_quantity_range" value="1" <?php echo ($quantity == 1)?'checked':'';?> 
	       data-pc-selector="filter_quantity_to"/> In-Stock
	
	<input type="hidden"  name="filter_paycart_product_quantity[0]" value="<?php echo (strlen($quantity) == 1 && $quantity == 0)?$quantity:'';?>"/>
	<input type="hidden"  name="filter_paycart_product_quantity[1]" value="<?php echo ($quantity == 1)?$quantity:'';?>"/>
	
	<div><input type="submit" name="filter_submit" class="btn btn-primary" value="<?php echo JText::_('Go');?>" /></div>
	<div><input type="reset"  name="filter_reset"  class="btn" value="<?php echo JText::_('Reset');?>" onclick="paycart.admin.grid.filters.reset(this.form);" /></div>
	
</div>
<?php 
