<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Paycart
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		Rimjhim Jain
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cart'); 
	?>
</div>
<!-- ADMIN MENU -->
<div class="span10">

<form action="<?php echo $uri; ?>" method="post" id="adminForm" name="adminForm">
	<table class="table table-hover">
		<thead>
			<!-- TABLE HEADER START -->
			<tr>
				<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
				<th><?php echo PaycartHtml::_('grid.sort', "COM_PAYCART_ADMIN_TRANSACTION_ID", 'transaction_id', $filter_order_Dir, $filter_order);?></th>
				<th class="hidden-phone"><?php echo PaycartHtml::_('grid.sort', "COM_PAYCART_ADMIN_BUYER", 'buyer_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo JText::_("COM_PAYCART_ADMIN_CART_ID");?></th>
				<th><?php echo PaycartHtml::_('grid.sort', "COM_PAYCART_ADMIN_AMOUNT", 'amount', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PaycartHtml::_('grid.sort', "COM_PAYCART_ADMIN_STATUS", 'payment_status', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo PaycartHtml::_('grid.sort', "COM_PAYCART_ADMIN_PAYMENT_METHOD", 'processor_type', $filter_order_Dir, $filter_order);?></th>				
				<th class="hidden-phone"><?php echo PaycartHtml::_('grid.sort', "COM_PAYCART_ADMIN_CREATED_DATE", 'created_date', $filter_order_Dir, $filter_order);?></th>
	
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
		<tbody>
		<?php $count = $limitstart;
			  $cbCount = 0;
			  
			  foreach ($records as $record):?>
				<tr class="<?php echo "row".$count%2; ?>">
				 	<th class="default-grid-chkbox nowrap hidden-phone">
				    	<?php echo PaycartHtml::_('grid.id', $cbCount, $record->{$record_key} ); ?>
				    </th>
					<td><?php echo PaycartHtml::link($uri.'&task=edit&id='.$record->{$record_key}, $record->{$record_key}); ?></td>							
					<td class="nowrap hidden-phone"><?php echo ($record->buyer_id)?PaycartHtml::link('index.php?option=com_paycart&view=buyer&task=edit&id='.$record->buyer_id, $record->buyer_id):$record->buyer_id;?></td>
					<td>
						<?php $cart_id = $invoices[$record->invoice_id]->object_id?>
						<?php echo PaycartHtml::link('index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,$cart_id);?>
					</td>
					<td><?php echo $record->amount;?></td>
					<td><?php echo JText::_($statusList[$record->payment_status]);?></td>
					<td><?php echo $record->processor_type;?></td>					
					<td class="nowrap hidden-phone"><?php echo Rb_date::timeago($record->created_date);?></td>
			
		<?php $count++;?>
		<?php $cbCount++;?>	
		<?php endforeach;?>
		</tbody>
    
  </table>
 		<div class="row">
     		<div class="offset5 span7"><?php echo $pagination->getListFooter();?></div>
   		</div> 
    
			
	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
</div>
<?php 
