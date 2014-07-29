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
<div class="pc-product row-fluid" data-ng-app="pcngProductApp">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=productcategory'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php // echo $this->loadTemplate('filter'); ?>
	
	<table class="table table-striped ">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
			
				<th  width="1%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th>
					<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_ID", 'productcategory_id', $filter_order_Dir, $filter_order);?>
				</th>				
			    <th>
			    	<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_TITLE", 'title', $filter_order_Dir, $filter_order);?>
			    </th>
							
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
					<td><?php echo $record->productcategory_id;?></td>
					<td><?php echo PaycartHtml::link('index.php?option=com_paycart&view=productcategory&task=edit&productcategory_id='.$record->{$record_key}, $record->title);?>
					</td>
				</tr>
			<?php $count++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="3">
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
