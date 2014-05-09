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
	
<!-- Html begin	-->
	<div class="" >
<?php
	if (empty($states)):
	?>	
		<div class="center">
			<a href="#" class="btn btn-success add-new-address" onClick="paycart.admin.state.window(<?php echo "'$record_id', 0";  ?>);" >
				<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo JText::_('COM_PAYCART_ADD_NEW_STATE');?>
			</a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-question-sign "></i>&nbsp;<?php echo JText::_('COM_PAYCART_SUPPORT_LINK');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-book"></i>&nbsp;<?php echo JText::_('COM_PAYCART_DOCUMENTATION_LINK');?></a>
		</div>
<?php else : ?>
	
	<div class="row-fluid">
		<a href="#" class="btn btn-success add-new-address pull-right" onClick="paycart.admin.state.window(<?php echo "'$record_id', 0 "; ?>);" >
				<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo JText::_('COM_PAYCART_ADD_NEW_STATE');?>
		</a>
	</div>
		
	<table class="table table-hover">
		<thead>
		<!-- TABLE HEADER START -->
			<tr>
			
				<th width="1%">	<?php echo '#'; ?></th>
				<th >	<?php echo JText::_('COM_PAYCART_STATE_TITLE')?>			</th>
				<th >	<?php echo JText::_('COM_PAYCART_STATE_ISOCODE')?>		</th>
				<th >	<?php echo JText::_('COM_PAYCART_STATE_STATUS')?>		</th>
				<th >	<?php echo JText::_('COM_PAYCART_STATE_OPERATION')?>	</th>
				
			</tr>
		<!-- TABLE HEADER END -->
		</thead>
	
		<tbody>
		<!-- TABLE BODY START -->
			<?php 
			$count= count($states);
			$counter = 1;
			foreach ($states as $state_id => $state_details):?>
			
				<tr class="<?php echo "row".$count%2; ?>">								
					<th><?php echo $counter++;?></th>
					
					<td><?php echo $state_details->title; ?></td>
					<td><?php echo $state_details->isocode;?></td>
					<td><?php echo $state_details->status;?></td>
					<td>
							<a href="#" class="btn btn-success" onClick="paycart.admin.state.window(<?php echo "'$record_id', {$state_id}"; ?>);" >
								<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo JText::_('COM_PAYCART_EDIT_STATE');?>
							</a>
							
							<a href="#" class="btn btn-success" onClick="paycart.admin.state.remove.go(<?php echo $state_id; ?>);" >
								<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo JText::_('COM_PAYCART_DELETE_STATE');?>
							</a>
					</td>
					
				</tr>
			<?php endforeach;?>
		<!-- TABLE BODY END -->
		</tbody>
	</table>		
<?php endif; ?>

</div>

