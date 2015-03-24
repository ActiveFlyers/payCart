<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>


<div class="pc-product-wrapper clearfix">
<div class="pc-group row-fluid">
	<!-- CONTENT START -->

	<!-- ADMIN MENU -->
	<div class="span2">
		<?php
				$helper = PaycartFactory::getHelper('adminmenu');			
				echo $helper->render('index.php?option=com_paycart&view=discountrule'); 
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
					
						<th width="1%">
							<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
						<th>
							<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_ID", 'discountrule_id', $filter_order_Dir, $filter_order);?>
						</th>
					    				
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_TITLE", 'title', $filter_order_Dir, $filter_order);?></th>
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_PROCESSOR_CLASSNAME", 'processor_classname', $filter_order_Dir, $filter_order);?></th>						
						<th class="center"><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_COUPON_CODE", 'coupon', $filter_order_Dir, $filter_order);?></th>
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_AMOUNT", 'amount', $filter_order_Dir, $filter_order);?></th>
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_PUBLISHED", 'published', $filter_order_Dir, $filter_order);?></th>
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_CREATED_DATE", 'created_date', $filter_order_Dir, $filter_order);?></th>
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_MODIFIED_DATE", 'modified_date', $filter_order_Dir, $filter_order);?></th>			
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
						    	<?php echo PaycartHtml::_('grid.id', $cbCount, $record->discountrule_id ); ?>
						    </th>				
							<td><?php echo $record->discountrule_id;?></td>
							<td>
								<?php echo PaycartHtml::link('index.php?option=com_paycart&view=discountrule&task=edit&discountrule_id='.$record->discountrule_id, $record->title);?>
								<p><small><?php echo $record->description;?></small></p>
							</td>
							<td><?php echo JText::_($record->processor_classname);?></td>
							<td class="center"><?php echo $record->coupon ? $record->coupon : '-';?></td>
							<td><?php echo $formatter->amount($record->amount,false);?></td>
							<td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
							<td><?php echo $record->created_date?></td>
							<td><?php echo $record->modified_date?></td>
						</tr>
					<?php $count++;?>
					<?php $cbCount++;?>
					<?php endforeach;?>
				<!-- TABLE BODY END -->
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="9">
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