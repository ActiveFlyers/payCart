<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

echo $this->loadTemplate('js');
?>
<div class="pc-country-wrapper clearfix">
<div class="pc-country row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=country'); 
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
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_TITLE", 'title', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_COUNTRY_ISOCODE_3", 'country_id', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_COUNTRY_ISOCODE_2", 'isocode2', $filter_order_Dir, $filter_order);?></th>
				<th ><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_PUBLISHED", 'published', $filter_order_Dir, $filter_order);?></th>
				
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
		
		<tbody>
		<!-- TABLE BODY START -->
			<?php $count= $limitstart;
			$cbCount = 0;
			foreach ($records as $record):?>
			
				<tr class="<?php echo "row".$count%2; ?>">								
					<th><?php echo PaycartHtml::_('grid.id', $cbCount, $record->{$record_key} );?></th>
					<td><?php echo PaycartHtml::link('index.php?option=com_paycart&view=country&task=edit&country_id='.$record->country_id, $record->title);?></td>
					<td><?php echo $record->country_id;?></td>
					<td><?php echo $record->isocode2;?></td>
					<td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>

				</tr>
			<?php $count++;?>
			<?php $cbCount++;?>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="5">
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

