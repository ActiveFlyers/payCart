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
<div class="pc-buyer-wrapper clearfix">
<div class="pc-buyer row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=buyer'); 
	?>
</div>
<!-- ADMIN MENU -->
<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php // echo $this->loadTemplate('filter'); ?>
	<table class="table table-striped">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_BUYER_ID", 'buyer_id', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_USERNAME", 'username', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_EMAIL", 'email', $filter_order_Dir, $filter_order);?></th>
				
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
		
		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			foreach ($records as $record):?>
			
				<tr class="<?php echo "row".$count%2; ?>">								
					<td><?php echo $record->buyer_id;?></td>
					<td>
						<?php echo PaycartHtml::link('index.php?option=com_paycart&view=buyer&task=edit&buyer_id='.$record->buyer_id, $record->username);?>
					</td>
					<td>
						<?php echo $record->email;?>
					</td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
	<input type="hidden" name="task" value="" />
	
</form>
</div>
</div>
</div>
<?php 
