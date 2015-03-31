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
<div class="well">
	<div class="row-fluid pc-filter-row">
		<div class="pc-filter-minwidth-100 span2">
			<label><?php echo JText::_('COM_PAYCART_ADMIN_TITLE').'/ '.JText::_('COM_PAYCART_ADMIN_SKU').'/ '.JText::_('COM_PAYCART_ADMIN_ALIAS')?></label>
			<?php echo paycartHtml::_('paycarthtml.text.filter', 'title', 'product', $filters, 'filter_paycart', array('class'=> 'pc-filter-width'));?>
		</div>

		<div class="pc-filter-minwidth-150 span2 visible-desktop pc-filter-gap-top">
			<?php echo Rb_Html::_('rb_html.boolean.filter', 'visible', 'product', $filters, 'filter_paycart','COM_PAYCART_ADMIN');?>
		</div>
		
		<div class="pc-filter-minwidth-150 span2 visible-desktop pc-filter-gap-top">
			<?php echo paycartHtml::_('paycarthtml.category.filter','productcategory_id','product',$filters,'filter_paycart')?>
		</div>

		<div class="pc-filter-minwidth-150 span2 pc-filter-gap-top" >
			<?php $quantity = '';?>
			<?php if(isset($filters['quantity'])):?>
				<?php $quantity = (strlen($filters['quantity'][0]) == 1)?$filters['quantity'][0]:$filters['quantity'][1];?>
			<?php endif;?>
			<select name="filter_paycart_product_quantity_range" data-pc-selector="filter_paycart_product_quantity_range">
				<option value="0" <?php echo (strlen($quantity) == 0)?'selected':'';?>><?php echo Jtext::_('COM_PAYCART_ADMIN_FILTERS_SELECT_QUANTITY')?></option>
				<option value="1" <?php echo ($quantity == 1)?'selected':'';?>><?php echo JText::_("COM_PAYCART_ADMIN_FILTERS_IN_STOCK");?></option>
				<option value="2" <?php echo (strlen($quantity) == 1 && $quantity == 0)?'selected':'';?>><?php echo JText::_("COM_PAYCART_ADMIN_FILTERS_OUT_OF_STOCK");?></option>
			</select>
			<input type="hidden"  name="filter_paycart_product_quantity[0]" data-pc-selector="filter_paycart_product_quantity_from" value="<?php echo (strlen($quantity) == 1 && $quantity == 0)?$quantity:'';?>"/>
			<input type="hidden"  name="filter_paycart_product_quantity[1]" data-pc-selector="filter_paycart_product_quantity_to" value="<?php echo ($quantity == 1)?$quantity:'';?>"/>
		</div>	
		
		<div class="pc-filter-minwidth-150 span1 visible-desktop pc-filter-gap-top">
			<?php echo Rb_Html::_('rb_html.boolean.filter', 'published', 'product', $filters, 'filter_paycart','COM_PAYCART_ADMIN');?>
		</div>
		
		<div class="pc-filter-minwidth-100 span2">
			<label><?php echo JText::_("COM_PAYCART_ADMIN_PRICE")?></label>
			<?php echo paycartHtml::_('paycarthtml.range.filter', 'price', 'product', $filters,'text','filter_paycart');?>
		</div>
		
		<div class="pc-filter-minwidth-50 span1 pc-filter-gap-top">
			<input type="submit" name="filter_submit" class="btn-block btn btn-primary" value="<?php echo JText::_('COM_PAYCART_ADMIN_GO');?>" />
			<input type="reset"  name="filter_reset"  class="btn-block btn" value="<?php echo JText::_('COM_PAYCART_ADMIN_RESET');?>" onclick="paycart.admin.grid.filters.reset(this.form);" />
		</div>
	</div>
</div>
<?php 