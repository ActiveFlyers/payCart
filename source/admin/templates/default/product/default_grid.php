<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

defined('_JEXEC') OR die();
?>
<div class="pc-product-wrapper clearfix">
<div class="pc-product row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=product'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">	
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php echo $this->loadTemplate('filter'); ?>
	
	<table class="table table-striped ">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
			
				<th width="1%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th width="4%" class="hidden-phone">
					<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_ID", 'product_id', $filter_order_Dir, $filter_order);?>
				</th>
			    				
				<th width="20%"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_TITLE", 'title', $filter_order_Dir, $filter_order);?></th>
				<th width="15%"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_SKU", 'sku', $filter_order_Dir, $filter_order);?></th>
				<th width="10%"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_PRICE", 'price', $filter_order_Dir, $filter_order).' ( '.$formatter->currency(PaycartFactory::getConfig()->get('localization_currency')).')';?></th>
				<th width="7%" class="center hidden-phone"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_PUBLISHED", 'published', $filter_order_Dir, $filter_order);?></th>
				<th width="8%" class="center hidden-phone"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_VISIBLE", 'visible', $filter_order_Dir, $filter_order);?></th>
				<th width="10%" class="center hidden-phone"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_ORDERING", 'ordering', $filter_order_Dir, $filter_order);?></th>	
				<th width="15%" class="center hidden-phone"><?php echo JText::_('COM_PAYCART_ADMIN_QUANTITY')?></th>
				<th width="15%" class="pull-right"></th>						
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
		
		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):?>
				<tr class="<?php echo "row".$count%2; ?>">								
					<th>
				    	<?php echo PaycartHtml::_('grid.id', $cbCount, $record->{$record_key} ); ?>
				    </th>				
					<td class="hidden-phone"><?php echo $record->product_id;?></td>
					<td><?php echo PaycartHtml::link(JUri::base().'index.php?option=com_paycart&view=product&task=edit&product_id='.$record->{$record_key}, $record->title);?>
					</td>
					<td><?php echo $record->sku;?></td>
					<td><?php echo $formatter->amount($record->price, false);?></td>
					<td class="center hidden-phone"><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
					<td class="center hidden-phone"><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'visible', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
					<td class="center hidden-phone">
						<span><?php echo $pagination->orderUpIcon( $count , true, 'orderup', 'Move Up'); ?></span>
						<span><?php echo $pagination->orderDownIcon( $count , count($records), true , 'orderdown', 'Move Down', true ); ?></span>
					</td>
					<td class="hidden-phone"> 
						<div class="progress">
							<div class="bar bar-info"
						  	   title="<?php echo $record->quantity.' : '.JText::_('COM_PAYCART_ADMIN_AVAILABLE')?>"
						  	   style="width:<?php echo $record->quantity_available?>%;">
						  	   		<bold><?php echo $record->quantity;?></bold>
						   </div>
						   <div class="bar bar-danger"
						  	    title="<?php echo $record->quantity_sold.' : '.JText::_('COM_PAYCART_ADMIN_SOLD')?>"
						  	   style="width:<?php echo $record->quantity_consumed ?>%;">
						  	   		<bold><?php echo $record->quantity_sold;?></bold>
						   </div>
						</div>
					</td>
					
					<td class="center">
							<a href="<?php echo $uri.'&task=addVariant&variant_of='.$record->product_id; ?>"><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_ADD_VARIANT');?></a>
					</td>
				</tr>
			<?php $count++;?>
			<?php $cbCount++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
</div>
</div>
</div>
<?php 
