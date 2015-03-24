<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );  
echo $this->loadTemplate('js');
?>
<div class="pc-notification row-fluid">
	<!-- ADMIN MENU -->
	<div class="span2">
		<?php $helper = PaycartFactory::getHelper('adminmenu');?>			
		<?php echo $helper->render('index.php?option=com_paycart&view=notification');?> 
	</div>
	
	<!-- GRID CONTENT START -->
	<div class="span10">
		<!--  LANGUAGE SWITCHER --> 
		<?php		
		if(PAYCART_MULTILINGUAL){
			$displayData = new stdClass();
			$displayData->uri  = $uri;
			echo Rb_HelperTemplate::renderLayout('paycart_language_switcher', $displayData);
		}		
		?>
		
		<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">		
			<table class="table table-striped ">					
				<!-- TABLE HEADER START -->
                <thead>
                	<tr>
                		<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                		<th><?php echo PaycartHtml::_('grid.sort', 'COM_PAYCART_ADMIN_ID', 'notification_id', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo PaycartHtml::_('grid.sort', 'COM_PAYCART_ADMIN_TITLE', 'event_name', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_STATUS", 'published', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo JText::_('COM_PAYCART_ADMIN_DESCRIPTION'); ?></th>
					</tr>
				<!-- TABLE HEADER END -->
                </thead>
					
				<tbody>
				<!-- TABLE BODY START -->
					<?php $count= $limitstart;
					$cbCount = 0;
					foreach ($records as $record):?>
						<tr class="<?php echo "row".$count%2; ?>">								
							<th><?php echo PaycartHtml::_('grid.id', $cbCount, $record->notification_id ); ?></th>				
							<td> <?php echo $record->notification_id;?></td>
							<td>
								<a href="#" class="edit-notification" onClick="paycart.admin.notification.window(<?php echo $record->notification_id; ?>);" >
                                	<?php  echo JText::_('COM_PAYCART_ADMIN_EVENT_'.$record->event_name);     ?>
                                </a>
                            </td>
                            <td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
                            <td><?php echo JText::_('COM_PAYCART_ADMIN_EVENT_'.$record->event_name.'_DESCRIPTION');?></td>
                        </tr>
					<?php $count++;?>
					<?php $cbCount++;?>
					<?php endforeach;?>
				<!-- TABLE BODY END -->
				</tbody>
					
				<tfoot>
					<tr>
						<td colspan="5"><?php echo $pagination->getListFooter(); ?></td>
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
<?php 