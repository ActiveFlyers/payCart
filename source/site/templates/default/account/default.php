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
						<div class="all-orders">
							<h2 class="page-header"><?php echo JText::_('COM_PAYCART_ORDERS');?> <sup><span class="badge"><?php echo $total_orders;?></span></sup></h2>	
						</div>					
						<?php foreach($carts as $cart_id => $cart): ?>
							<?php 
								$params = json_decode($cart->params);	
								$products = get_object_vars($params->products);
							?>
							<div class="order-items-wraper accordion" id="accordion-order<?php echo $cart_id;?>">
								<div class="accordion-group">
									<div class="accordion-header well-small">
										<div class="accordion-toggle">
											<div class="row-fluid"  data-toggle="collapse" data-parent="#accordion-order<?php echo $cart_id;?>" data-target="#order_<?php echo $cart_id?>">
												<div class="span6">
													<div class="row-fluid">
														<div class="span8">
															<h3 class="order-item-title font500 clearfix">
																<span class="pull-left"><?php echo JText::_('COM_PAYCART_ORDER_ID');?> : <?php echo $cart->cart_id;?></span>  
																<span class="muted pull-left"><small>[<?php echo count($products);?> <?php echo JText::_('COM_PAYCART_ITEM');?>(s)]</small></span>
															</h3>
															<span class="muted"><?php echo JText::_('COM_PAYCART_ORDER_PLACED_ON');?> : <?php echo $formatter->date(new Rb_Date($cart->created_date));?></span>
														</div>
														<div class="span4">
															<h4 class="font500"><?php echo JText::_('COM_PAYCART_TOTAL');?> <?php echo JText::_('COM_PAYCART_AMOUNT');?> </h4> 
															<span class="muted"><?php echo $formatter->amount($invoices[$cart->invoice_id]['total']);?></span>
														</div>
													</div>
												</div>
												<div class="span6">
													<div class="pull-right" style="margin-right:20px">
														<h4 class="font500">
															<a class="pull-right view-order-details" href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order&order_id='.$cart_id);?>"><?php echo JText::_('COM_PAYCART_VIEW_ORDER_DETAILS');?> <i class="fa fa-angle-double-right"></i></a>
														</h4>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="order-items-content">
										<div id="order_<?php echo $cart_id?>" class="accordion-body collapse in">
										<?php
															
										
										foreach($products as $product_id => $product) :
											$product_instance = PaycartProduct::getInstance($product_id);
											$product_price = $product_instance->getPrice();
											$product_quantity = $product_instance->getQuantitySold();
											$image = $product_instance->getCoverMedia();
										?>
											
											<div class="accordion-inner">
												<div class="clearfix">
													<?php if($cart->isShippableProductExist):?>
															<?php if($cart->is_delivered) :?>
																	<h4 class="font500"><?php echo JText::_('COM_PAYCART_DELIVERY_STATUS');?> : <span class="label label-success"><?php echo JText::_('COM_PAYCART_CART_STATUS_DELIVERED');?></span></h4>
																	<h4 class="font500"><?php echo JText::_('COM_PAYCART_CART_STATUS_DELIVERED');?> <?php echo strtolower(JText::_('COM_PAYCART_ON'));?>: <?php echo $formatter->date(new Rb_Date($cart->delivered_date));?></h4>
															<?php else :?>
																	<h4 class="font500"><?php echo JText::_('COM_PAYCART_DELIVERY_STATUS');?> : <span class="label label-warning"><?php echo JText::_('COM_PAYCART_CART_STATUS_PENDING');?></span></h4>												
															<?php endif;?>
													<?php endif;?>									
												</div>
												
												<div class="row-fluid">
													<div class="span2">
														<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product_id);?>" class="thumbnail">
															<img src="<?php echo !empty($image) ? $image['thumbnail'] : '';?>" 
																title="<?php echo !empty($image) ? $image['title'] : '';?>" 
																alt="<?php echo !empty($image) ? $image['title'] : '';?>" />
														</a>
													</div>
													<div class="span10">
														<h4 class="font500"><a href="<?php echo JRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product_id);?>"><?php echo $product_instance->getTitle();?></a></h4>
														<h5 class="ordered-item-details"><span class="muted"><?php echo JText::_('COM_PAYCART_QTY');?> : <?php echo $product_quantity;?></span></h5>
														<h5 class="ordered-item-details"><span class="muted"><?php echo JText::_('COM_PAYCART_PRICE');?> : </span><span class="text-error"><?php echo $formatter->amount($product_price);?></span></h5>
													</div>
												</div>
											</div>
										
										<?php endforeach;?>
											
										<?php 
										//Invoice Link	
										$downloadUrl = JRoute::_('index.php?option=com_paycart&view=pdfdownload&task=sitedownload&action=sitePdfDownload&cart_id='.$cart_id);
										if(Rb_HelperPlugin::getStatus('pdfdownload','paycart')):?>
											<div class="clearfix well-small">
													<span class="pull-right btn-link view-order-details" onclick="rb.url.redirect('<?php echo $downloadUrl; ?>');  event.stopPropagation(); ">
														 <i class="fa fa-1x fa-file-pdf-o"></i> <?php echo JText::_('COM_PAYCART_INVOICE');?>
													</span>
											</div>
										<?php endif;?>											
										</div>
									</div>
								</div>
							</div>
							<?php endforeach;?>
							<div class="center">
								<?php echo $pagination->getListFooter(); ?>
							</div>
				</div>				
			</div>
		</div>      	
	</div>
</div>
<?php 