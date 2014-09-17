<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

PaycartHtml::_('behavior.formvalidation');

Rb_HelperTemplate::loadMedia(array('angular'));

$status = array( 
				array('value' => Paycart::STATUS_SHIPMENT_PENDING, 'title' => JText::_('COM_PAYCART_SHIPMENT_STATUS_PENDING')),
				array('value' => Paycart::STATUS_SHIPMENT_DISPATCHED, 'title' => JText::_('COM_PAYCART_SHIPMENT_STATUS_DISPATCHED')),
				array('value' => Paycart::STATUS_SHIPMENT_DELIVERED, 'title' => JText::_('COM_PAYCART_SHIPMENT_STATUS_DELIVERED')),
				array('value' => Paycart::STATUS_SHIPMENT_FAILED, 'title' => JText::_('COM_PAYCART_SHIPMENT_STATUS_FAILED')),
			   );

?>
<script type="text/javascript">
	paycart.ng.cart = angular.module('pcngCartApp', []);
</script>

<?php 
echo $this->loadTemplate('edit.ng');
?>

<div data-ng-app="pcngCartApp">
	
	<script>
		var cartId    		= <?php echo $cart->getId();?>;
		var shippingMethods = <?php echo json_encode($shippingMethods)?>;
		var status 			= <?php echo json_encode($status);?>;
		var products		= <?php echo json_encode(array_values($product_particular));?>;
		var errMessage		= false;
		
		<?php if($shipments):?>
			var shipments   = <?php echo json_encode($shipments, true);?>;
		<?php else :?>
			var shipments   = [ {'products':[{}]} ];
		<?php endif;?>
	</script>
		
	<div class="row-fluid form-horizontal" data-ng-controller="pcngCartShipmentCtrl"><br>
		<button class="btn btn-primary" data-ng-click="addNewShipment();" onclick="return false;"> 
			<i class="fa fa-plus">&nbsp;&nbsp;<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_ADD_NEW")?></i>
		</button><br><br>
		<div data-ng-show="message" class="alert alert-success">{{ message }}</div>
		<hr>
		
		<div data-ng-repeat="(index,shipment) in shipments" > 
		
			<!-- show 2 elements in one loop, so that row fluid class can be applied properly -->
			<span data-ng-if="index%2 == 0">
				<div class="row-fluid" data-ng-if="shipments[index+0]">
					
					<!-- 1st child  -->
					<div class="span6 well">
						<div data-ng-show="shipment.message" class="alert alert-success">{{ shipment.message }}</div>
						<div data-ng-show="shipment.errMessage" class="alert alert-danger">{{ shipment.errMessage }}</div>
						
						<span class="pull-right">
							<a href="javascript:void(0);" class="hasTooltip" title="<?php echo JText::_('COM_PAYCART_ADMIN_SAVE')?>" data-ng-click="save(index);">
								<i class="fa fa-check"></i>
							</a>
							&nbsp; | &nbsp; 
							<a href="javascript:void(0);"  class="hasTooltip" title="<?php echo JText::_('COM_PAYCART_ADMIN_DELETE')?>" data-ng-click="remove(index)">
								<i class="fa fa-trash-o"></i>
							</a>
						</span>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_CART_SHIPPING_METHOD');?>
							</label>
							<div class="controls">
								<select data-ng-model="shipment.shippingrule_id" data-ng-options="sm.value as sm.title for sm in shippingMethods" 
										required="true" class="pc_shipment_selectbox"></select>
								<input type="hidden" data-ng-model="shipment.shipment_id">
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_WEIGHT');?>
							</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><?php echo PaycartFactory::getConfig()->get('catalogue_weight_unit');?></span>
									<input type="text" class="input-mini" data-ng-model="shipment.weight">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_ACTUAL_COST');?>
							</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><?php echo $formatter->currency(PaycartFactory::getConfig()->get('localization_currency'));?></span>
									<input type="text" class="input-mini" data-ng-model="shipment.actual_shipping_cost">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_TRACKING_NUMBER');?>
							</label>
							<div class="controls">
								<input type="text" class="input-mini" data-ng-model="shipment.tracking_number">
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_STATUS');?>
							</label>
							<div class="controls">
								<select data-ng-model="shipment.status" data-ng-options="st.value as st.title for st in status" 
										required="true" class="pc_shipment_selectbox"></select>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_PRODUCT_QUANTITY');?>
							</label>
							<div class="controls">
								<span data-ng-repeat="product in shipment.products">
									<select data-ng-model="product.product_id" data-ng-options="p.particular_id as p.title for p in products" 
									        required="true" class="pc_shipment_selectbox"></select>
									: <input type="text" class="input-mini" data-ng-model="product.quantity" required="true">
									<a data-ng-show="$index != 0" href="javascript:void(0);" data-ng-click="removeProduct(index, $index)" 
									   class="hasTooltip" title="<?php echo JText::_('COM_PAYCART_ADMIN_DELETE')?>">
										<i class="fa fa-trash-o"></i>
									</a>
									<a data-ng-show="$index == 0" href="javascript:void(0);" data-ng-click="addMoreProduct(index)" 
									   class="hasTooltip" title="<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_ADD_NEW")?>">
										<i class="fa fa-plus"></i>
									</a>
									<br><br>
								</span>
								
							</div>
						</div >
					</div>
					<!-- End of 1st child  -->
						
					<!-- 2nd child if exist -->
					<div class="span6 well" data-ng-if="shipments[index+1]">
						<div data-ng-show="shipments[index+1].message" class="alert alert-success">{{ shipments[index+1].message }}</div>
						<div data-ng-show="shipments[index+1].errMessage" class="alert alert-danger">{{ shipments[index+1].errMessage }}</div>
						
						<span class="pull-right">
							<a href="javascript:void(0);" class="hasTooltip" title="<?php echo JText::_('COM_PAYCART_ADMIN_SAVE')?>" 
							   data-ng-click="save(index+1);"><i class="fa fa-check"></i></a>
							&nbsp; | &nbsp; 
							<a href="javascript:void(0);"  class="hasTooltip" title="<?php echo JText::_('COM_PAYCART_ADMIN_DELETE')?>" 
							   data-ng-click="remove(index+1)"><i class="fa fa-trash-o"></i></a>
						</span>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_CART_SHIPPING_METHOD');?>
							</label>
							<div class="controls">
								<select data-ng-model="shipments[index+1].shippingrule_id" 
									    data-ng-options="sm.value as sm.title for sm in shippingMethods" required="true" class="pc_shipment_selectbox"></select>
								<input type="hidden" data-ng-model="shipments[index+1].shipment_id">
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_WEIGHT');?>
							</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><?php echo PaycartFactory::getConfig()->get('catalogue_weight_unit');?></span>
									<input type="text" class="input-mini" data-ng-model="shipments[index+1].weight">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_ACTUAL_COST');?>
							</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><?php echo $formatter->currency(PaycartFactory::getConfig()->get('localization_currency'));?></span>
									<input type="text" class="input-mini" data-ng-model="shipments[index+1].actual_shipping_cost">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_TRACKING_NUMBER');?>
							</label>
							<div class="controls">
								<input type="text" class="input-mini" data-ng-model="shipments[index+1].tracking_number">
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_STATUS');?>
							</label>
							<div class="controls">
								<select data-ng-model="shipments[index+1].status" data-ng-options="st.value as st.title for st in status" 
										required="true" class="pc_shipment_selectbox"></select>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_PRODUCT_QUANTITY');?>
							</label>
							<div class="controls">
								<span data-ng-repeat="product in shipments[index+1].products">
									<select data-ng-model="product.product_id" data-ng-options="p.particular_id as p.title for p in products" 
											required="true" class="pc_shipment_selectbox"></select>
									: <input type="text" class="input-mini" data-ng-model="product.quantity" required="true">
									<a data-ng-show="$index != 0" href="javascript:void(0);" data-ng-click="removeProduct(index+1, $index)" class="hasTooltip" 
									   title="<?php echo JText::_('COM_PAYCART_ADMIN_DELETE')?>">
										<i class="fa fa-trash-o"></i>
									</a>
									<a data-ng-show="$index == 0" href="javascript:void(0);" data-ng-click="addMoreProduct(index+1)" class="hasTooltip"
									   title="<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_ADD_NEW")?>">
										<i class="fa fa-plus"></i>
									</a>
									<br><br>
								</span>
							</div>
						</div >
					</div>
					<!-- end of 2nd child if exist -->
				</div>
				<!-- end of a row -->
			 </span>
		 </div>
	</div>
</div>