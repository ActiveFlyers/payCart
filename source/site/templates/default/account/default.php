<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

echo $this->loadTemplate('css');
echo $this->loadTemplate('js');
?>
<div class='pc-account-wrapper row-fluid clearfix'> 
	<div id="pc-account" class ='pc-account span12 clearfix' >
	
		<!-- START HEADER -->
		<div class="pc-account-header hidden-phone">
			<?php echo $this->loadTemplate('header');?>
		</div>
		<!-- START HEADER -->
		
		
		<!-- START BODY -->
		<div class="row-fluid">
			<div class="span3 hidden-phone">
				<?php echo $this->loadTemplate('sidebar');?>
			</div>
			<div class="span9">
				<div class ='pc-account span12 clearfix' >
				<table class="table table-hover">
					<thead>					
						<tr>
							<th width="10%">#</th>
							<th><?php echo JText::_('COM_PAYCART_ORDERS');?> <span class="badge"><?php echo $total_orders;?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php $counter = $limitstart+1;?>
						<?php foreach($carts as $cart_id => $cart): ?>
							<tr >
								<td><?php echo $counter++;?></td>						
								<td> 							
									<div>									
										<h3 class="clearfix">										
											<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order&order_id='.$cart_id);?>"><?php echo JText::_('COM_PAYCART_ORDER_ID');?> : <?php echo $cart->cart_id;?></a>
											<?php switch ($cart->status):
											
													case Paycart::STATUS_CART_PAID:
														$class = "label-success";
														break;
													
													case Paycart::STATUS_CART_CANCELLED:
														$class = "label-info";
														break;
													
													case Paycart::STATUS_CART_DRAFTED:
														$class = "label-warning";
														break;
												?>
											<?php endswitch;?>
												<span class="label pull-right <?php echo $class;?>"><i class="fa fa-check-circle"></i> <?php echo JText::_('COM_PAYCART_CART_STATUS_'.$cart->status);?></span>
										</h3>
										<?php $getProductByType  = PaycartFactory::getHelper('cart')->arrangeProductByType($cart_id);?>
										<?php if(($cart->status == Paycart::STATUS_CART_PAID || ($cart->status == Paycart::STATUS_CART_DRAFTED && $cart->is_approved == 1)) 
												  && count($getProductByType['digital']) == 0  && $cart->allowCancel):?>
										<?php $url = "index.php?option=com_paycart&view=account&task=cancelOrder&cart_id=".$cart_id;?>
										<div><a href="#pc-account-confirmation" class="pull-right btn muted" data-toggle="modal" onClick="paycart.ajax.go('<?php echo $url; ?>'); return false;"> <?php echo JText::_("COM_PAYCART_ORDER_CANCEL");?></a></div>
										<div id="pc-account-confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:500px; margin-left:-400px;" data-backdrop="static" data-keyboard="false">
											&nbsp;
										</div>	
										<?php endif;?>
										
									</div>
									<div>
										<h4>
											<?php echo JText::_('COM_PAYCART_AMOUNT');?> : <?php echo $formatter->amount($invoices[$cart->invoice_id]['total']);?>	
											
											<?php 		
											$downloadUrl = JRoute::_('index.php?option=com_paycart&view=pdfdownload&task=sitedownload&action=sitePdfDownload&cart_id='.$cart_id);
											if(Rb_HelperPlugin::getStatus('pdfdownload','paycart')):?>
												<span class="pull-right" onclick="rb.url.redirect('<?php echo $downloadUrl; ?>');  event.stopPropagation(); ">
													 <i class="fa fa-1x fa-download"></i>
												</span>
											<?php endif;?>										
										</h4>										
									</div>
									<div><?php echo JText::_('COM_PAYCART_CREATED_DATE');?> : <?php echo $formatter->date(new Rb_Date($cart->created_date));?></div>
									
									<?php if($cart->isShippableProductExist && $cart->status == Paycart::STATUS_CART_PAID):?>
										<div><?php echo JText::_('COM_PAYCART_DELIVERY_STATUS');?> :
											<?php if($cart->is_delivered) :?>
													<span class="label label-success"><?php echo JText::_('COM_PAYCART_CART_STATUS_DELIVERED');?></span>
													<?php echo strtolower(JText::_('COM_PAYCART_ON'));?>
													<?php echo $formatter->date(new Rb_Date($cart->delivered_date));?>
											<?php else :?>
													<span class="label label-warning"><?php echo JText::_('COM_PAYCART_CART_STATUS_PENDING');?></span>												
											<?php endif;?>
										</div>
									<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<div class="center">
									<?php echo $pagination->getListFooter(); ?>
								</div>								
							</td>
						</tr>
					</tfoot>
				</table>
				</div>				
			</div>
		</div>      	
	</div>
</div>
<?php 