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

?>
<div>
<?php
	if (empty($states)):
	?>	
	
	<div class="row-fluid">
		<div class="center muted">
			<div>
				<h1>&nbsp;</h1>
				<i class="fa fa-flag fa-5x"></i>
			</div>
			
			<div>
				<h3><?php echo JText::_('COM_PAYCART_ADMIN_STATE_GRID_BLANK_MSG');?></h3>
			</div>
		</div>
	</div>
	<div class="row-fluid">	
		<div class="center">
			<a href="#" class="btn btn-success btn-large" onClick="paycart.admin.state.window(<?php echo "'$record_id', 0";  ?>);">
				<i class="icon-plus-sign icon-white"></i>&nbsp;
				<?php echo Rb_Text::_('COM_PAYCART_ADMIN_STATE_ADD');?>
			</a>			
		</div>
	</div>
	
<?php else : ?>

	<div class="row-fluid">
		<a href="#" class="btn btn-success add-new-address pull-right" onClick="paycart.admin.state.window(<?php echo "'$record_id', 0 "; ?>);" >
				<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo JText::_('COM_PAYCART_ADMIN_STATE_ADD');?>
		</a>
	</div>
		
	<table class="table table-striped">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
<!--				<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>-->
				<th><?php echo JText::_('COM_PAYCART_ADMIN_TITLE')?></th>
				<th><?php echo JText::_('COM_PAYCART_ADMIN_ISOCODE')?></th>
	 			<th><?php echo JText::_('COM_PAYCART_ADMIN_STATUS')?></th> 
				<th><?php echo JText::_('COM_PAYCART_ADMIN_STATE_OPERATION')?></th>
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
	
		<tbody>
		<!-- TABLE BODY START -->
			<?php 
			$count= count($states);
			$counter = 1;
			$cbCount = 0;
			foreach ($states as $state_id => $state_details):?>			
				<tr class="<?php echo "row".$count%2; ?>">	
<!--					<th><?php echo PaycartHtml::_('grid.id', $cbCount++, $state_id );?></th>-->
					<td>
						<a href="#" onClick="paycart.admin.state.window(<?php echo "'$record_id', {$state_id}"; ?>);" >
								<?php echo $state_details->title; ?>
						</a>
					</td>
					<td><?php echo $state_details->isocode;?></td>
					<td><?php echo ($state_details->published == '1')? '<span class="label label-success">'.JText::_('COM_PAYCART_ADMIN_PUBLISHED') : '<span class="label">'.JText::_('COM_PAYCART_ADMIN_UNPUBLISHED');?></span></td>
					<td>
						<a href="#" onClick="paycart.admin.state.window(<?php echo "'$record_id', {$state_id}"; ?>);" >
								<i class="icon-edit icon-white"></i>
						</a>
						<a href="#" onClick="paycart.admin.state.remove.go(<?php echo $state_id; ?>);" >
							<i class="icon-trash icon-white text-error"></i>
						</a>
					</td>
				</tr>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
	</table>
	
<?php endif; ?>
</div>
<?php 