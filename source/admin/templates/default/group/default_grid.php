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
			echo $helper->render('index.php?option=com_paycart&view=group'); 
	?>
</div>
<!-- ADMIN MENU -->


<div class="span10">
<ul id="paycartAdminGroupTabs" class="nav nav-tabs">
	<li class="active">
		<a data-toggle="tab" href="#paycartAdminGroupTabsInstances"><?php echo JText::_('COM_PAYCART_ADMIN_INSTANCES');?></a>
	</li>
	<li class="">
		<a data-toggle="tab" href="#paycartAdminGroupTabsType"><?php echo JText::_('COM_PAYCART_ADMIN_CREATE_NEW');?></a>
	</li>
</ul>

<div id="paycartAdminGroupTabsContent" class="tab-content">
	<div class="tab-pane" id="paycartAdminGroupTabsType">
		<?php echo $this->loadTemplate('select_type');?>
	</div>
	
	<!-- Grid TAB -->
	<div class="tab-pane active" id="paycartAdminGroupTabsInstances">			
		<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
		
			<?php // echo $this->loadTemplate('filter'); ?>
			
			<table class="table table-striped">
				<thead>
				<!-- TABLE HEADER START -->
					<tr>
					
						<th width="1%">
							<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
						<th>
							<?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_ID", 'group_id', $filter_order_Dir, $filter_order);?>
						</th>
					    				
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_TITLE", 'title', $filter_order_Dir, $filter_order);?></th>
						<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_TYPE", 'type', $filter_order_Dir, $filter_order);?></th>						
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
					$errorMsg = '';
					
					foreach ($records as $record):?>
						<?php $nonEditable = false;?>
						<?php $type   = $record->type;?>
						<?php $params = json_decode($record->params);?>
						<?php if(isset($availableGroupRules[$type])):?>
							<?php $classNames = array_keys($availableGroupRules[$type]);?>
							<?php foreach ($params as $data){
									if(!in_array($data->ruleClass, $classNames)){
										$errorMsg = $data->ruleClass.' : '.JText::_("COM_PAYCART_ADMIN_EITHER_PLUGIN_IS_DISABLED_OR_NOT_INSTALLED");
										$nonEditable = true;
										break;
									}
								  }
							?>
						<?php else:?>
							<?php $errorMsg = $type.' : '.JText::_("COM_PAYCART_ADMIN_ALL_PLUGINS_OF_TYPE_IS_EITHER_DISABLED_OR_NOT_INSTALLED");?>
							<?php $nonEditable = true;?>
						<?php endif;?>
						
						<tr class="<?php echo "row".$count%2; ?>">	
						<?php if(!$nonEditable):?>
								<th>
							    	<?php echo PaycartHtml::_('grid.id', $cbCount++, $record->group_id ); ?>
							    </th>				
								<td><?php echo $record->group_id;?></td>
								<td>
									<?php echo PaycartHtml::link('index.php?option=com_paycart&view=group&task=edit&group_id='.$record->group_id, $record->title);?>
									<p><small><?php echo $record->description;?></small></p>
								</td>
								<td><?php echo JText::_('COM_PAYCART_ADMIN_GROUPRULE_TYPE_'.$record->type);?></td>
								<td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $count, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
								<td><?php echo $record->created_date?></td>
								<td><?php echo $record->modified_date?></td>
							
						<?php else:?>
								<th>
							    	&nbsp;
							    </th>				
								<td><?php echo $record->group_id;?></td>
								<td><?php echo $record->title;?></td>
								<td colspan="4"><?php echo $errorMsg;?></td>
						<?php endif;?>
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
	</div>
</div>
</div>
</div>
</div>
<?php 