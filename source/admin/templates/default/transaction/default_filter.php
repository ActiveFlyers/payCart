<?php 
/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayInvoice
* @contact 		payinvoice@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>
<?php $attr = array(); ?>
<div class="container-fluid well">
	<div class="row-fluid">

		<div class="span2 hidden-phone">&nbsp;</div>
		<div class="span10">
			<div class="span1 hidden-phone">&nbsp;</div>
			<div class="span11">
				<div class="span1 hidden-phone" style="min-width: 170px;">
					<label><?php echo JText::_('COM_PAYCART_CREATED_DATE');?></label>
					<div>
						<?php echo paycartHtml::_('paycarthtml.range.filter', 'created_date', 'transaction', $filters, 'date', 'filter_rb_ecommerce', array('style'=>'style="width:90px;"'));?>
					</div>
				</div>

				<div class="span1 hidden-tablet hidden-phone" style="min-width: 140px;">
					<label><?php echo JText::_('COM_PAYCART_CART_TOTAL');?></label>
					<div><?php echo paycartHtml::_('paycarthtml.range.filter', 'amount', 'transaction', $filters, 'text', 'filter_rb_ecommerce');?></div>
				</div>

				<div class="span1 hidden-tablet pc-filter-minwidth-100">
					<label><?php echo JText::_('COM_PAYCART_USERNAME');?></label>
					<div>
						<?php echo paycartHtml::_('paycarthtml.text.filter', 'username', 'transaction', $filters, 'filter_rb_ecommerce', array('class'=>'pc-filter-width'));?>
					</div>
				</div>

				<div class="span1 pc-filter-minwidth-100">
					<label><?php echo JText::_('COM_PAYCART_CART_ID_LABEL');?></label>
					<div><?php echo paycartHtml::_('paycarthtml.text.filter', 'object_id', 'transaction', $filters, 'filter_rb_ecommerce', array('class'=>'pc-filter-width'));?></div>
				</div>

				<div class="span2" style="min-width: 110px;">					
					<label><?php echo JText::_('COM_PAYCART_PAYMENT_METHOD');?></label>
					<?php $attr['style'] ='class="pc-filter-width"';?>
					<div><?php echo paycartHtml::_('paycarthtml.paymentgateway.filter', 'processor_type', 'transaction', 'payment', $filters, 'filter_rb_ecommerce');?></div>
				</div>
				
				<div class="span2" style="min-width: 110px;">
					<label><?php echo JText::_('COM_PAYCART_CART_STATUS_LABEL');?></label>
					<?php 
						$attr['style'] ='class="pc-filter-width"';
						$options    = array();
						$options[0] = array('title'=>JText::_('COM_PAYCART_ADMIN_FILTERS_SELECT_PUBLISHED_STATE'), 'value'=>'');
						$status 	= Rb_EcommerceResponse::getStatusList();
						
						foreach ($status as $key => $value){			
							$options[$key] = array('title' => JText::_($value), 'value' => $key);
						}
						echo JHtml::_('select.genericlist', $options, 'filter_rb_ecommerce_transaction_payment_status'.''.'[]', 'onchange="document.adminForm.submit();"', 'value', 'title', @array_shift($filters[payment_status]));
					?>
				</div>
				
				<div class="pc-filter-minwidth-50 span2 pc-filter-gap-top">
					<input type="submit" name="filter_submit" class="btn-block btn btn-primary" value="<?php echo JText::_('COM_PAYCART_ADMIN_GO');?>" />
					<input type="reset"  name="filter_reset"  class="btn-block btn" value="<?php echo JText::_('COM_PAYCART_ADMIN_RESET');?>" onclick="paycart.admin.grid.filters.reset(this.form);" />
				</div>

			</div>
		</div>
	</div>
</div>
<?php
