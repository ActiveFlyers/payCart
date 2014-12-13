<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

/**
 * Available Prop
 * $message 			=> Message for nuyer
 * transaction_detail	=> Transaction-details for buyer  
 * 
 */
/* @var $formatter PaycartHelperFormat */
$formatter = PaycartFactory::getHelper('format');
?>
<div id="pc-cart-products">
 	<div class="row-fluid text-center">
 		<h1 class="muted"><?php echo JText::_($message);?></h1> 		
 	</div>
 	
 	<?php if ( !empty($transaction_detail) ) :?>
 	<div class="row-fluid ">
 		<h2 class="muted"><?php echo JText::_('COM_PAYCART_TRANSACTION_DETIALS');?></h2>
	 	<table class="table table-striped">
			<thead>
			<!-- TABLE HEADER START -->
				<tr>
					<th><?php echo JText::_('COM_PAYCART_GATEWAY_TRANSACTION_ID'); ?></th>
					<th><?php echo JText::_('COM_PAYCART_MESSAGE'); ?></th>
					<th><?php echo JText::_('COM_PAYCART_CREATED_DATE'); ?></th>			
				</tr>
			<!-- TABLE HEADER END -->
			</thead>
			
			<tbody>
			<!-- TABLE BODY START -->
			<?php
 	 		foreach ($transaction_detail as $data) :
 	 		?>
 	 		<tr class="">
 	 			<td><?php echo $data->gateway_txn_id ; ?></td>
 	 			<td><?php echo JText::_($data->message); ?></td>
 	 			<td><?php echo $formatter->date(new Rb_Date($data->created_date) ); ?></td>	
			</tr>		
			<?php 
 	 		endforeach; 
 			?>		
			<!-- TABLE BODY END -->
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="7">
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<?php endif;?>
</div>
<?php 