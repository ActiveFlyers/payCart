<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
* @author		rimjhim jain
*/

defined('_JEXEC') or die( 'Restricted access' );
?>


<div class="well">
	<div class="row-fluid">	
		<div class="span9">
			<div class="row-fluid pc-filter-row">
				<div class="pc-filter-minwidth-100 span2">
					<label><?php echo JText::_('COM_PAYCART_ADMIN_TITLE')?></label>
					<?php echo paycartHtml::_('paycarthtml.text.filter', 'title', 'discountrule', $filters, 'filter_paycart', array('class'=>'pc-filter-width'));?>
				</div>
				<div class="pc-filter-minwidth-100 span2">
					<label><?php echo JText::_('COM_PAYCART_ADMIN_COUPON_CODE')?></label>
					<?php echo paycartHtml::_('paycarthtml.text.filter', 'coupon', 'discountrule', $filters, 'filter_paycart', array('class'=>'pc-filter-width'));?>
				</div>
				<div class="pc-filter-minwidth-150 span2 pc-filter-gap-top">
					<?php echo Rb_Html::_('rb_html.boolean.filter', 'published', 'discountrule', $filters, 'filter_paycart','COM_PAYCART_ADMIN');?>
				</div>
				<div class="pc-filter-minwidth-150 span2 pc-filter-gap-top">
					<?php echo paycartHtml::_('paycarthtml.processor.filter','processor_classname','discountrule','discountrule',$filters,'filter_paycart');?>
				</div>
				<div class="pc-filter-minwidth-100 span2">
					<label><?php echo JText::_('COM_PAYCART_ADMIN_AMOUNT')?></label>
					<?php echo paycartHtml::_('paycarthtml.range.filter', 'amount', 'discountrule', $filters,'text','filter_paycart');?>
				</div>
				<div class="pc-filter-minwidth-50 span2 pc-filter-gap-top">
					<div class="help-block"><input type="submit" name="filter_submit" class="btn-block btn btn-primary" value="<?php echo JText::_('COM_PAYCART_ADMIN_GO');?>" /></div>
					<div><input type="reset"  name="filter_reset"  class="btn-block btn" value="<?php echo JText::_('COM_PAYCART_ADMIN_RESET');?>" onclick="paycart.admin.grid.filters.reset(this.form);" /></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 