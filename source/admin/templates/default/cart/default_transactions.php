<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Rimjhim Jain
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>

<div class="row-fluid">
	<div class="span6">				
		<table class="table table-hover">
			<thead>
			<!-- TABLE HEADER START -->
				<tr>	
					<th><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_ID');?></th>
					<th><?php echo JText::_('COM_PAYCART_ADMIN_AMOUNT');?></th>
					<th><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_PAYMENT_STATUS');?></th>
				</tr>
				<!-- TABLE HEADER END -->
				</thead>
				<tbody>
				<?php foreach ($transactions as $transaction):?>
					<tr>
						<td><?php echo PaycartHtml::link('index.php?option=com_paycart&view=transaction&task=edit&id='.$transaction->transaction_id, $transaction->transaction_id); ?></td>							
						<td><?php echo $transaction->amount;?></td>
						<td><?php echo JText::_($transactionStatusList[$transaction->payment_status]);?></td>
					</tr>		
				<?php endforeach;?>
				</tbody>		    
		</table>
	</div>
</div>
<?php 