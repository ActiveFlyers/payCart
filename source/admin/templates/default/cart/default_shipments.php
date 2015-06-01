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

?>


<div  data-ng-controller="pcngCartShipmentCtrl">
	
	<script>
		var pc_shipment_cartId    		= <?php echo $cart->getId();?>;
		var pc_shipment_shippingMethods = <?php echo json_encode($shippingMethods)?>;
		var pc_shipment_status 			= <?php echo json_encode($status);?>;
		var pc_shipment_products		= <?php echo json_encode(array_values($product_particular));?>;
		var pc_shipment_tempArray		= [{}]; // used to store indexes of two shipment to show in one row
		var pc_shipment_tempStatus		= [];
		var pc_shipment_notes			= [{}];
		
		<?php if($shipments):?>
			var pc_shipments   = <?php echo json_encode($shipments, true);?>;
		<?php else :?>
			var pc_shipments   = [ {'products':[{}]} , {'notes' :[{}]}];
		<?php endif;?>
	</script>
		
	<div class="row-fluid form-horizontal" ><br>
		<button class="btn btn-primary" data-ng-click="addNewShipment();" onclick="return false;"> 
			<i class="fa fa-plus">&nbsp;&nbsp;<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_ADD_NEW")?></i>
		</button><br><br>
		<div data-ng-show="message" class="alert alert-success">{{ message }}</div>
		<hr>
		
		<div data-ng-repeat="(index,shipment) in shipments" > 
		
			<!-- show 2 elements in one loop, so that row fluid class can be applied properly -->
			<span data-ng-if="index%2 == 0">
				<div class="row-fluid">
					
					<div class="span6 well" data-ng-repeat="(key,value) in tempArray=init(index)">
						<div data-ng-show="shipments[value].message" class="alert alert-success">{{ shipments[value].message }}</div>
						<div data-ng-show="shipments[value].errMessage" class="alert alert-danger">{{ shipments[value].errMessage }}</div>
						
						<span data-ng-if="shipments[value].status != '<?php echo paycart::STATUS_SHIPMENT_DELIVERED?>'" class="pull-right">
							<button type="button" class="btn btn-small btn-primary" data-ng-click="save(value);">
								<?php echo JText::_('COM_PAYCART_ADMIN_SAVE')?>
							</button>
							<button type="button" class="btn btn-small" data-ng-class="{true:'hide'}[shipments[value].status=='failed']" data-ng-click="remove(value)">
								<?php echo JText::_('COM_PAYCART_ADMIN_DELETE')?>
							</button>
						</span>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_CART_SHIPPING_METHOD');?>
							</label>
							<div class="controls">
								<select data-ng-model="shipments[value].shippingrule_id" data-ng-options="value as title for (value, title) in shippingMethods" 
										required="true" class="pc_shipment_selectbox">
										<option value=""></option>
								</select>
								<input type="hidden" data-ng-model="shipments[value].shipment_id">
								<input type="hidden" data-ng-model="shipments[value].cart_id" data-ng-init="shipments[value].cart_id = cartId">
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_WEIGHT');?>
							</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><?php echo PaycartFactory::getConfig()->get('catalogue_weight_unit');?></span>
									<input type="text" class="input-mini" data-ng-model="shipments[value].weight">
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
									<input type="text" class="input-mini" data-ng-model="shipments[value].actual_shipping_cost">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_TRACKING_NUMBER');?>
							</label>
							<div class="controls">
								<input type="text" class="input-mini" data-ng-model="shipments[value].tracking_number"> 
<!--								<a data-ng-if="shipments[value].shipment_id" href="javascript:void(0);" class="hasTooltip" title="<?php echo JText::_('COM_PAYCART_ADMIN_SAVE')?>" data-ng-click="save(value);">-->
<!--								<i class="fa fa-check"></i>-->
<!--							</a>-->
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_STATUS');?>
							</label>
							<div class="controls" data-ng-init="shipments[value].status = shipments[value].status || 'pending'">
								<div data-ng-init="tempStatus[value] = getStatus(shipments[value].status, shipments[value].shipment_id)">
									<span data-ng-repeat="s in tempStatus[value]"  data-ng-class="{muted : s.disabled}">
									    <input ng-if="shipments[value].status != s.value" data-ng-disabled="{{s.disabled}}"
									        name="status{{value}}"
									        type="radio"
									        value="{{s.value}}"
									        data-ng-model="shipments[value].status" data-ng-confirm-click="<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_STATUS_CHANGE_CONFIRMATION");?>" confirmed-click="save(value)" />

									    <input ng-if="shipments[value].status == s.value" data-ng-disabled="{{s.disabled}}"
									        name="status{{value}}"
									        type="radio"
									        value="{{s.value}}"
									        data-ng-model="shipments[value].status"/>
									        {{ s.title }} 
									</span>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_EST_DELIVERY_DATE');?>
							</label>
							<div class="controls">
								{{ shipments[value].est_delivery_date }}									
								<input type="hidden" class="input-mini" data-ng-model="shipments[value].est_delivery_date">								
							</div>
						</div>
						
						<div class="control-group">
							<label title="" class="hasTooltip control-label">
								<?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_PRODUCT_QUANTITY');?>
							</label>
							<div class="controls">
								<span data-ng-repeat="product in shipments[value].products">
									<select data-ng-model="product.product_id" data-ng-options="p.particular_id as p.title for p in products" 
									        required="true" class="pc_shipment_selectbox">
											<option value=""></option>
									</select>
									: <input type="text" class="input-mini" data-ng-model="product.quantity" required="true">
									<a data-ng-show="$index != 0" href="javascript:void(0);" data-ng-click="removeProduct(value, $index)" 
									    data-ng-class="{false:'hide'}[shipments[value].status=='pending']" >
										<i class="fa fa-trash-o"></i>
									</a>
									<a data-ng-show="$index == 0" href="javascript:void(0);" data-ng-click="addMoreProduct(value)" data-ng-class="{false:'hide'}[shipments[value].status=='pending']">
										<i class="fa fa-plus"></i>
									</a>
									<br><br>
								</span>
								
							</div>
						</div>
						
						<!-- New note section -->
						<hr>
						<div class="text-center">
							<h3><?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_TRACKING_NOTES');?></h3>
							<p><small>(<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_TRACKING_NOTES_DETAIL")?>)</small></p>
							
							<textarea data-ng-init="shipments[value].noteNew.text=''" rows="" cols="" data-ng-model="shipments[value].noteNew.text"></textarea>
							<select data-ng-init="shipments[value].noteNew.status=null" data-ng-model="shipments[value].noteNew.status" data-ng-options="sts.value as sts.title for sts in status">
									<option value=""></option>
							</select>
							<button data-ng-click="addMoreNote(value);" class="btn btn-small btn-primary"><?php echo JText::_('COM_PAYCART_ADMIN_SHIPMENT_ADD_NEW')?></button>
						</div>
						
						<div data-ng-init="shipments[value].notes = shipments[value].notes || [{}]">
							<div data-ng-if="shipments[value].notes">
								<table class="table table-responsive" data-ng-repeat="note in shipments[value].notes">
									<tr>
										<td width="25%">{{note.date}}</td>
										<td width="20%">{{note.status}}</td>
										<td width="50%">{{note.text}}</td>
										<td width="5%">
											<a data-ng-show="note.text" href="javascript:void(0);" data-ng-click="removeNote(value, $index)">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
									</tr>
								</table>
							</div>	
						</div>
					</div>				
				</div>
				<!-- end of a row -->
			 </span>
		 </div>
	</div>
</div>
<?php 
