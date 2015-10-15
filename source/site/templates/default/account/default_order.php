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
	<div id="pc-account" class ='pc-account pc-account-order span12 clearfix' >
	
		<!-- HEADER -->
		<div class="pc-account-header hidden-phone">
			<?php echo $this->loadTemplate('header');?>
		</div>		
		
		
		<!-- BREADCRUMB -->
		<div class="row-fluid">		
			<ul class="breadcrumb pc-account-order-breadcrumb">
				<li><a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=display');?>"><?php echo JText::_('COM_PAYCART_ACCOUNT');?></a> <span class="divider">/</span></li>
				<li><a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order');?>"><?php echo JText::_('COM_PAYCART_MY_ORDERS');?></a> <span class="divider">/</span></li>
				<li class="active"><?php echo $order_id;?></li>
			</ul>
		</div>
		
		<!-- DETAILS -->
		<div class="row-fluid">
			<div class="pc-account-order-details">
				<div class="row-fluid">
					<div class="span6">
						<div class="pc-account-order-orderdetail">
							<fieldset>
								<legend><?php echo JText::_('COM_PAYCART_ORDER_DETAILS');?></legend>
								<div><?php echo JText::_('COM_PAYCART_ORDER_ID');?> : <span class="heading"><?php echo $cart->cart_id;?></span> <span class="pc-lowercase">(<?php echo count($productCartParticulars).' '.JText::_('COM_PAYCART_ITEM'.((count($productCartParticulars) > 1 ) ? 'S' : ''));?>)</span></div>
								<div><?php echo JText::_('COM_PAYCART_ORDER_PLACED');?> : <span class="heading"><?php echo $formatter->date(new Rb_Date($cart->locked_date));?></span></div>
								<?php if($isShippableProductExist):?>
									<div>
										<?php if($cart->is_delivered) :?>
												<span class="text-success"><strong><?php echo JText::_('COM_PAYCART_CART_STATUS_DELIVERED');?></strong></span>
												<span class="pc-lowercase"><?php echo JText::_('COM_PAYCART_ON');?></span>
												<?php echo $formatter->date(new Rb_Date($cart->delivered_date));?>
										<?php else :?>
											<span class="text-warning"><strong><?php echo JText::_('COM_PAYCART_CART_STATUS_PENDING');?></strong></span>												
										<?php endif;?>
									</div>
								<?php endif;?>
							</fieldset>
						</div>
					</div>
					<br class="visible-xs-block visible-phone" />
					<div class="span6">
						<?php if($isShippableProductExist):?>
							<div class="pc-account-order-shipping-address">
								<fieldset>
									<legend><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING');?></legend>
									<div>
										<?php echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $shippingAddress);?>
									</div>
								</fieldset>
							</div>
						<?php else:?>
							<div class="pc-account-order-billing-address">
								<fieldset>
									<legend><?php echo JText::_('COM_PAYCART_ADDRESS_BILLING');?></legend>
									<div>
										<?php echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $billingAddress);?>
									</div>
								</fieldset>
							</div>
						<?php endif;?>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="pc-account-order-paymentdetail">			
						<fieldset>
							<legend><?php echo JText::_('COM_PAYCART_ACCOUNT_PAYMENT_DETAILS');?></legend>
							<div class="row-fluid">
								<div class="span6">
									<?php if(isset($cart->params['payment_gateway'])):?>
										<div><?php echo JText::_('COM_PAYCART_PAYMENT_METHOD');?> : <?php echo $cart->params['payment_gateway']['title'];?></div>
										<div><?php echo JText::_('COM_PAYCART_STATUS');?> :
											<span class="heading pc-uppercase">
											<?php if($invoice->status == PaycartHelperInvoice::STATUS_INVOICE_PAID): ?>
												<span class="text-success"><?php echo $invoiceStatusList[$invoice->status];?></span>
											<?php elseif($invoice->status == PaycartHelperInvoice::STATUS_INVOICE_INPROCESS):?>
												<span class="text-warning"><?php echo $invoiceStatusList[$invoice->status];?></span>
											<?php else:?>
												<span class="text-error"><?php echo $invoiceStatusList[$invoice->status];?></span>
											<?php endif;?>
											</span>
										</div>
									<?php else:?>
										<div><?php echo JText::_('COM_PAYCART_NOT_AVAILABLE');?></div>
									<?php endif;?>
								</div>
								<br class="visible-xs-block visible-phone" />
								<div class="span6">
									<table class="table">
										<thead>
											<tr>
												<td><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> :</td>
												<td><span class="pull-right"><?php echo $formatter->amount($cart->subtotal);?></span></td>
											</tr>								
											<?php if(!empty($cart->shipping)):?>
												<tr>
													<td><?php echo JText::_('COM_PAYCART_SHIPPING')?> :</td>
													<td><span class="pull-right"><?php echo $formatter->amount($cart->shipping);?></span></td>
												</tr>
											<?php endif;?>
											<?php if(!empty($cart->promotion)):?>
												<tr>
													<td><?php echo JText::_('COM_PAYCART_PROMOTION_DISCOUNT');?> :</td>
													<td><span class="pull-right"><?php echo $formatter->amount($cart->promotion);?></span></td>
												</tr>
											<?php endif;?>
											<?php if(!empty($cart->duties)):?>
												<tr>
													<td><?php echo JText::_('COM_PAYCART_TAX');?> :</td>
													<td>
														<span class="pull-right"><?php echo $formatter->amount($cart->duties);?></span>
														<br><small class="pull-right">(<?php echo JText::_("COM_PAYCART_CART_TAX_ON_TAX_DESC")?>)</small>
													</td>
												</tr>
											<?php endif;?>
											<tr>
												<td><span class="heading"><?php echo JText::_('COM_PAYCART_TOTAL');?> :</span></td>
												<td><span class="pull-right heading"><?php echo $formatter->amount($cart->total);?></span></td>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		
	<div class="pc-account-order-productdetails">
		<?php if(!empty($digitalProducts)):?>
		<div class="row-fluid">
				<div class="pc-account-order-productdetails-header well well-small muted">
					<div class="row-fluid">
						<div class="span6">
							<span class="heading pc-uppercase"><?php echo JText::_('COM_PAYCART_PRODUCT_DETAILS');?></span>
						</div>
						<div class="span6 hidden-phone">
							<span class="heading pc-uppercase">
								<span class="pull-left"><?php echo JText::_('COM_PAYCART_DOWNLOADS')?></span>
								<span class="pull-right pc-uppercase"><?php echo JText::_('COM_PAYCART_SUBTOTAL')?></span>
							</span>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="pc-account-order-product">
						<?php foreach($digitalProducts as $product) :?>
							<?php $digitalContent = $product->getDigitalContent();?>
							<?php $product_id = $product->getId();?>
				  			<?php $productParticular = $productCartParticulars[$product_id];?>
				  			<div class="shipment-row" data-pc-selector="shipment-row">
								<div class="row-fluid">
									<div class="span6">
										<table class="table">
	              							<thead>
	                							<tr>
							                  		<td>
											  			<div class="text-center">
											  			<?php $image = $product->getCoverMedia(); ?>
											  			<img class="img-polaroid" src="<?php echo !empty($image) ? $image['thumbnail'] : '';?>" title="<?php echo !empty($image) ? $image['title'] : '';?>" alt="<?php echo !empty($image) ? $image['title'] : '';?>">
											  			</div>
											  		</td>
											  		<td>
			  											<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product_id);?>"><?php echo $product->getTitle();?></a>
			  											<div class="muted">
			  												<ul class="inline">
				  												<?php $postionedAttributes = (array)$product->getPositionedAttributes();?>
																<?php $attributes = $product->getAttributes();?>								 
													 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>			 			
													 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
													 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
													 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
																 			<li><small><?php echo $instance->getTitle();?>&nbsp;:&nbsp;<?php $options = $instance->getOptions(); echo $options[$attributes[$attributeId]]->title;?>,</small></li>
																		<?php endif?>	                         
													 				<?php endforeach;?>			 				
													 			<?php endif;?>
						 										<li><small><?php echo JText::_('COM_PAYCART_QUANTITY');?>: <?php echo $productParticular->quantity;?></small></li>
					 										</ul>
					 									</div>
					 									<strong class="visible-phone"><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> : <?php echo $formatter->amount($productParticular->total);?></strong>
					 								</td>
		 										</tr>
		 									</thead>
		 								</table>
		 							</div>
		 							
		 							<div class="span6">
										<table class="table">
											<thead>
												<tr>
													<td>
													<?php if($cart->status == paycart::STATUS_CART_PAID):?>
														<?php foreach ($digitalContent as $data):?>
															<?php $mainId=base64_encode('file-'.$data['main']['media_id']);?>
															<?php $extension = Jfile::getExt($data['main']['filename'])?>
															<p>
																<a href="javascript:void(0);" onClick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=serveFile&file_id='.$mainId.'&cart_id='.$cart->cart_id.'&key='.$secureKey.'&returnUrl='.base64_encode($currentUrl))?>')"><i class="fa fa-download dwn-icon"></i> <?php echo $data['main']['title']?></a>
																<span class="muted"><?php echo ' ('.$extension.')';?></span>
															</p>
														<?php endforeach;?>
													<?php else:?>
														<?php echo JText::_('COM_PAYCART_NOT_AVAILABLE')?>
													<?php endif;?>
													</td>
													
													<td class="hidden-phone">
			 											<div class="text-right">
			  												<span class="lead"><?php echo $formatter->amount($productParticular->total);?></span>
			  												<span>
			  												<a href="#" onclick="return false;" data-toggle="popover" data-placement="left" data-trigger="click" 
			  													data-content="	<span class='muted'>			  														
																	<span class='pull-right'><?php echo JText::_('com_paycart_unit_price').' : '.$formatter->amount($productParticular->unit_price);?></span><br/>
																	<span class='pull-right'><?php echo JText::_('com_paycart_quantity').' : x'.$productParticular->quantity;?></span>																		
																	<?php if($productParticular->total !=  $productParticular->price):?>
																		<hr/><span class='pull-right'><?php echo JText::_('com_paycart_subtotal').' : '.$formatter->amount($productParticular->price);?></span><br/>
																	<?php endif;?>																		
																	<?php if($productParticular->discount < 0):?>
																		<span class='pull-right'><?php echo JText::_('com_paycart_discount').' : '.$formatter->amount($productParticular->discount);?></span><br/>
																	<?php endif;?>
																	<?php if($productParticular->tax > 0):?>
																		<span class='pull-right'><?php echo JText::_('com_paycart_tax').' : '.$formatter->amount($productParticular->tax);?></span><br/>
																	<?php endif;?>
																	</span>
																	<hr/><span class='pull-right'><?php echo JText::_('com_paycart_total').' : '.$formatter->amount($productParticular->total);?></span>
																	">
			  													<i class="fa fa-question-circle"></i>
			  												</a>
			  												</span>
			  											</div>
			  										</td>
			  									</tr>
			  								</thead>
			  							</table>
			  						</div>
		  						</div>
		  					</div>
							<?php endforeach;?>
							<hr />
						</div>
				</div>			
			</div>
		<?php endif;?>
			
		<?php if(!empty($productShipments)):?>
		<div class="row-fluid">
				<div class="pc-account-order-productdetails-header well well-small muted">
					<div class="row-fluid">
						<div class="span6">
							<span class="heading pc-uppercase"><?php echo JText::_('COM_PAYCART_PRODUCT_DETAILS');?></span>
						</div>
						<div class="span6 hidden-phone">
							<span class="heading pc-uppercase">
								<span class="pull-left"><?php echo JText::_('COM_PAYCART_DELIVERY')?></span>
								<span class="pull-right pc-uppercase"><?php echo JText::_('COM_PAYCART_SUBTOTAL')?></span>
							</span>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="pc-account-order-product">
						<?php foreach($productShipments as $product_id => $productShipment) :?>
							<?php foreach ($productShipment as $shipment):?>
				  			<?php $product = $products[$product_id];?>
				  			<?php $productParticular = $productCartParticulars[$product_id];?>
				  			<div class="shipment-row" data-pc-selector="shipment-row">
								<div class="row-fluid">
									<div class="span6">
										<table class="table">
	              							<thead>
	                							<tr>
							                  		<td>
											  			<div class="text-center">
											  			<?php $image = $product->getCoverMedia(); ?>
											  			<img class="img-polaroid" src="<?php echo !empty($image) ? $image['thumbnail'] : '';?>" title="<?php echo !empty($image) ? $image['title'] : '';?>" alt="<?php echo !empty($image) ? $image['title'] : '';?>">
											  			</div>
											  		</td>
											  		<td>
			  											<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product_id);?>"><?php echo $product->getTitle();?></a>
			  											<div class="muted">
			  												<ul class="inline">
				  												<?php $postionedAttributes = (array)$product->getPositionedAttributes();?>
																<?php $attributes = $product->getAttributes();?>								 
													 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>			 			
													 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
													 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
													 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
																 			<li><small><?php echo $instance->getTitle();?>&nbsp;:&nbsp;<?php $options = $instance->getOptions(); echo $options[$attributes[$attributeId]]->title;?>,</small></li>
																		<?php endif?>	                         
													 				<?php endforeach;?>			 				
													 			<?php endif;?>
						 										<li><small><?php echo JText::_('COM_PAYCART_QUANTITY');?>: <?php echo $shipment['quantity'];?></small></li>
					 										</ul>
					 									</div>
					 									<strong class="visible-phone"><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> : <?php echo $formatter->amount($productParticular->total);?></strong>
					 								</td>
		 										</tr>
		 									</thead>
		 								</table>
		 							</div>
		 							
		 							<div class="span6">
										<table class="table">
											<thead>
												<tr>
													<td>
														<?php if(isset($shipments[$shipment['shipment_id']]) && $shipments[$shipment['shipment_id']]->status == Paycart::STATUS_SHIPMENT_DELIVERED) :?>
															<p><i class="fa fa-check-circle"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_DELIVERED');?></p>
			 												<div class="progress progress-success">
																<div class="bar" style="width: 100%"></div>
															</div>
			  												<p class="muted"><span class="pc-lowercase"><?php echo JText::_('COM_PAYCART_ON');?> <?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->delivered_date));?></span></p>
			  											<?php elseif(isset($shipments[$shipment['shipment_id']]) && $shipments[$shipment['shipment_id']]->status == Paycart::STATUS_SHIPMENT_DISPATCHED) :?>
															<p><i class="fa fa-truck"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_DISPATCHED');?></p>
			 												<div class="progress progress-info">
																<div class="bar" style="width: 66%"></div>
															</div>
			  												<p class="muted"><span><?php echo JText::_('COM_PAYCART_SHIPPMENT_STD_DELIVERY_DATE');?> : <?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->est_delivery_date));?></span></p>
			  											<?php elseif(isset($shipments[$shipment['shipment_id']]) && $shipments[$shipment['shipment_id']]->status == Paycart::STATUS_SHIPMENT_FAILED) :?>
															<p><i class="fa fa-times-circle"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_FAILED');?></p>
			 												<div class="progress progress-danger">
																<div class="bar" style="width: <?php echo $shipments[$shipment['shipment_id']]->dispatched_date == '0000-00-00 00:00:00' ? '33' : '66';?>%"></div>
															</div>
			  												<p class="muted"><del><?php echo JText::_('COM_PAYCART_SHIPPMENT_STD_DELIVERY_DATE');?> : <?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->est_delivery_date));?></del></p>
			  											<?php else :?>
			  												<p><i class="fa fa-spinner"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_PENDING');?></p>
			 												<div class="progress progress-warning">
																<div class="bar" style="width: 33%"></div>
															</div>
															<?php // IMP : In case if shipment is not created yet, then we can not show std delivery date?>
															<p class="muted"><span><?php echo JText::_('COM_PAYCART_SHIPPMENT_STD_DELIVERY_DATE');?> : 
															<?php if(isset($shipments[$shipment['shipment_id']])) :?>
			  													<?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->est_delivery_date));?>
			  												<?php else:?>
			  													<?php echo $estimatedDeliveryDate ? $formatter->date($estimatedDeliveryDate) : JText::_('COM_PAYCART_SOON') ;?>
			  												<?php endif;?>
			  												</span></p>
			  											 <?php endif;?>
			  											 
			  											 <?php if(!empty($shipment['notes'])):?>
			  											 	<a data-pc-selector='pc-track-table'><?php echo Jtext::_("COM_PAYCART_CART_TRACK_PACKAGE")?> <i class="fa fa-angle-double-down"></i></a>
			  											 <?php endif;?>
													</td>
													
													<td class="hidden-phone">
			 											<div class="text-right">
			  												<span class="lead"><?php echo $formatter->amount($productParticular->total*$shipment['quantity']/$productParticular->quantity);?></span>
			  												<span>
			  												<a href="#" onclick="return false;" data-toggle="popover" data-placement="left" data-trigger="click" 
			  													data-content="	<span class='muted'>			  														
																	<span class='pull-right'><?php echo JText::_('com_paycart_unit_price').' : '.$formatter->amount($productParticular->unit_price);?></span><br/>
																	<span class='pull-right'><?php echo JText::_('com_paycart_quantity').' : x'.$shipment['quantity'];?></span>																		
																	<?php if($productParticular->total !=  $productParticular->price):?>
																		<hr/><span class='pull-right'><?php echo JText::_('com_paycart_subtotal').' : '.$formatter->amount($productParticular->price*$shipment['quantity']/$productParticular->quantity);?></span><br/>
																	<?php endif;?>																		
																	<?php if($productParticular->discount < 0):?>
																		<span class='pull-right'><?php echo JText::_('com_paycart_discount').' : '.$formatter->amount($productParticular->discount*$shipment['quantity']/$productParticular->quantity);?></span><br/>
																	<?php endif;?>
																	<?php if($productParticular->tax > 0):?>
																		<span class='pull-right'><?php echo JText::_('com_paycart_tax').' : '.$formatter->amount($productParticular->tax*$shipment['quantity']/$productParticular->quantity);?></span><br/>
																	<?php endif;?>
																	</span>
																	<hr/><span class='pull-right'><?php echo JText::_('com_paycart_total').' : '.$formatter->amount($productParticular->total*$shipment['quantity']/$productParticular->quantity);?></span>
																	">
			  													<i class="fa fa-question-circle"></i>
			  												</a>
			  												</span>
			  											</div>
			  										</td>
			  									</tr>
			  								</thead>
			  							</table>
			  						</div>
		  						</div>
		  						<div class="row-fluid hide pc-track-shipment" data-pc-selector="track-details">
		  							<div class="well pc-track-arrow">
		  								<?php foreach ($shipment['notes'] as $note):?>
											<div class="row-fluid pc-track-tbl-border">
												<div class="span12 img-polaroid">
													<div class="row-fluid">
														<div class="span6">
															<table class="table">
													              <tbody>
													                <tr>
													                  <td>
																		<div><strong><i class="fa fa-clock-o"></i> <?php echo $note['time']?></strong></div>
																		<p>(<?php echo $formatter->date(new Rb_date($note['date']))?>)</p>
																	  </td>
													                  <td>
																		<div><strong><?php echo JString::strtoupper(JText::_('COM_PAYCART_STATUS'))?></strong></div>
																		
																		<?php 
																			$class = '';
																			$successClass='';
																			switch($note['status']){
																	 		 case paycart::STATUS_SHIPMENT_PENDING : $class = 'fa fa-spinner'; break;
																	 		 case paycart::STATUS_SHIPMENT_DISPATCHED : $class = 'fa fa-truck'; break;
																	 		 case paycart::STATUS_SHIPMENT_DELIVERED : $class = 'fa fa-check-circle'; $successClass = 'text-success';break;
																	 		 case paycart::STATUS_SHIPMENT_FAILED : $class = 'fa fa-times-circle';$successClass = 'text-error';
																		    }
																		?>
																		<p class="<?php echo $successClass;?>"><i class="<?php echo $class;?>"></i> <?php echo JString::ucfirst($note['status']);?></p>
																	</td>
																   </tr>
																  </tbody>
															</table>
														</div>
														<div class="span6 pc-track-note">
															<strong><?php echo JString::strtoupper(JText::_("COM_PAYCART_SHIPMENT_NOTE")); ?>:</strong>
															<p><small><?php echo $note['text']?></small></p>
														</div>									
													</div>
												</div>
											</div>
										<?php endforeach;?>
									</div>
		  						</div>
		  					</div>
							<?php endforeach;?>
							<hr />
						<?php endforeach;?>
					</div>
				</div>
			</div>
		<?php endif;?>
		<div class="row-fluid">
				<div class="span6 pull-right">
					<table class="table">
						<thead>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> :</td>
								<td><span class="pull-right"><?php echo $formatter->amount($cart->subtotal);?></span></td>
							</tr>								
							<?php if(!empty($cart->shipping)):?>
								<tr>
									<td><?php echo JText::_('COM_PAYCART_SHIPPING')?> :</td>
									<td><span class="pull-right"><?php echo $formatter->amount($cart->shipping);?></span></td>
								</tr>
							<?php endif;?>
							<?php if(!empty($cart->promotion)):?>
								<tr>
									<td><?php echo JText::_('COM_PAYCART_PROMOTION_DISCOUNT');?> :</td>
									<td><span class="pull-right"><?php echo $formatter->amount($cart->promotion);?></span></td>
								</tr>
							<?php endif;?>
							<?php if(!empty($cart->duties)):?>
								<tr>
									<td><?php echo JText::_('COM_PAYCART_TAX');?> :</td>
									<td>
										<span class="pull-right"><?php echo $formatter->amount($cart->duties);?></span>
										<br><small class="pull-right">(<?php echo JText::_("COM_PAYCART_CART_TAX_ON_TAX_DESC")?>)</small>
									</td>
								</tr>
							<?php endif;?>
							<tr>
								<td><span class="heading"><?php echo JText::_('COM_PAYCART_TOTAL');?> :</span></td>
								<td><span class="pull-right heading"><?php echo $formatter->amount($cart->total);?></span></td>
							</tr>
						</thead>
					</table>
				</div>
		</div>
		
		<div class="row-fluid">
			<?php 			    
			$position = 'pc-order-pdf-action';
		    if(isset($plugin_result) && isset($plugin_result[$position])):?>
		    	<hr>
				<div class=<?php echo $position;?> >
				<?php echo $plugin_result[$position]; ?>
				</div>
		    <?php endif;?>
		</div>
	</div>
    </div>
</div>
<?php 