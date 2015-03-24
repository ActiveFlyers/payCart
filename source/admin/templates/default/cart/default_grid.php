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

<div class="pc-cart-wrapper clearfix">
<div class="pc-cart row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cart'); 
	?>
</div>
<!-- ADMIN MENU -->
<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php echo $this->loadTemplate('filter'); ?>

	<table class="table table-striped">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
				<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
				<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_CART_ID", 'cart_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_BUYER", 'buyer_id', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_STATUS", 'status', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_CART_APPROVED", 'is_approved', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_CART_DELIVERED", 'is_delivered', $filter_order_Dir, $filter_order);?></th>
				<th><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CART_LOCKED_DATE');?></th>
				<th><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CART_PAID_DATE');?></th>			
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
		
		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):
			?>
			
				<tr class="<?php echo "row".$count%2 .' '; ?> ">								
					<th>
				    	<?php echo PaycartHtml::_('grid.id', $cbCount, $record->{$record_key} ); ?>
				    </th>				
					<td><?php echo PaycartHtml::link($uri.'&task=edit&cart_id='.$record->cart_id, $record->cart_id);?></td>
					<td>
						<?php $buyer = PaycartBuyer::getInstance($record->buyer_id);?>
						<?php 
                                    $buyer_username = $buyer->getUsername();

                                     if (!$record->buyer_id) {
     						               $buyer_username = JText::_('COM_PAYCART_GUEST');
                                      }

	                                 echo $buyer_username.' ('.$record->buyer_id.') ';
    					  ?>
					</td>
					<td><?php echo $record->status;?></td>
					<td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'is_approved', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
					<td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'is_delivered', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
					<td><?php echo $record->locked_date;?></td>
					<td><?php echo $record->paid_date;?></td>
				</tr>
			<?php $count++;?>
			<?php $cbCount++;?>
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
</div>
</div>
</div>
<?php 
