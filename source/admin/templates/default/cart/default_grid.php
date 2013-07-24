<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		Puneet Singhal 
*/

defined('_JEXEC') OR die();
?>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php // echo $this->loadTemplate('filter'); ?>
	<table class="table table-hover">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
			
				<th  width="1%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(<?php echo count($records); ?>);" />
				</th>
				<th >
					<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_CART_ID_LABEL", 'cart_id', $filter_order_Dir, $filter_order);?>
				</th>
			    				
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_CART_BUYER_LABEL", 'buyer_id', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Text::_('COM_PAYCART_CART_SUBTOTAL_LABEL');?></th>
				<th ><?php echo Rb_Text::_('COM_PAYCART_CART_TOTAL_LABEL');?></th>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_CART_STATUS_LABEL", 'status', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Text::_('COM_PAYCART_CART_CHECKOUT_DATE_LABEL');?></th>
				<th ><?php echo Rb_Text::_('COM_PAYCART_CART_PAID_DATE_LABEL');?></th>			
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
		
		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):
				$class = '';
				if($record->status == Paycart::CART_STATUS_PAID){
					$class = 'success';
				}
			?>
			
				<tr class="<?php echo "row".$count%2 .' '.$class; ?> ">								
					<th>
				    	<?php echo PaycartHtml::_('grid.id', $cbCount++, $record->{$record_key} ); ?>
				    </th>				
					<td><?php echo PaycartHtml::link($uri.'&task=edit&cart_id='.$record->cart_id, $record->cart_id);?></td>
					<td><?php echo $record->buyer_id;?></td>
					<td><?php echo PaycartHelper::formatAmount($record->subtotal);?></td>
					<td><?php echo PaycartHelper::formatAmount($record->total);?></td>
					<td><?php echo $record->status;?></td>
					<td><?php echo $record->checkout_date;?></td>
					<td><?php echo $record->paid_date;?></td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="8">
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
<?php 
