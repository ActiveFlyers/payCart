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

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php // echo $this->loadTemplate('filter'); ?>
	
	<table class="table table-condensed ">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
			
				<th  width="1%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(<?php echo count($records); ?>);" />
				</th>
				<th >
					<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_CATEGORY_ID", 'category_id', $filter_order_Dir, $filter_order);?>
				</th>
			    <th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_CATEGORY_NAME", 'name', $filter_order_Dir, $filter_order);?></th>
							
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
				    	<?php echo PaycartHtml::_('grid.id', $cbCount++, $record->{$record_key} ); ?>
				    </th>				
					<td><?php echo $record->category_id;?></td>
					<td><?php echo PaycartHtml::link($uri.'&task=edit&category_id='.$record->{$record_key}, $record->name);?>
						<br /> <?php echo $record->alias; ?>
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
	<input type="hidden" name="boxchecked" value="0" />
</form>
<?php 
