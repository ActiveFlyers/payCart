<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		manish@readybytes.in
* @author 		Manish Trivedi
*/
defined('_JEXEC') or die();
?>

<script>
	var pc_attributes_available = <?php echo json_encode($availableAttributes);?>;
	var pc_attributes_added		= <?php echo json_encode($addedAttributes);?>;
</script>
<div class="row-fluid" data-ng-controller="pcngProductAttributeCtrl" id="pcngProductAttributeCtrl">
	<div class="span6">
		<h1 class="center"><?php echo JText::_('COM_PAYCART_ADMIN_ATTRIBUTE_DRAG_BELOW');?></h1>
		<div class="pc-product-attribute-list well">			
			<br/>
					
			<div data-ng-repeat="attribute in added" class="row-fluid">
			<hr/>				
					<div class="span9" ng-include="getUrl(attribute.productattribute_id, attribute.value);">
					</div>				
					<div class="span1">	
						<a href="#" onclick="return false;" data-ng-click="removeFromProduct(attribute.productattribute_id)"><i class="fa fa-times">&nbsp;</i></a>
					</div>				
			</div>			
		</div>
	</div>
	
	<div class="span6">		
		<div class="row-fluid">
			<h1><?php echo JText::_('COM_PAYCART_ADMIN_ATTRIBUTES');?></h1>
			<ul class="pc-attribute-list">
				<li data-ng-repeat="attribute in available" ng-class="{ 'pc-attribute-draggable' : addedAtIndex(added, attribute.productattribute_id) == null }" class="pc-attribute" data-attribute-id="{{ attribute.productattribute_id }}">
					<div class="row-fluid">
					<div class="pull-left">						
						<i data-ng-hide="addedAtIndex(added, attribute.productattribute_id) != null" class="fa fa-arrows"></i>
						<i data-ng-show="addedAtIndex(added, attribute.productattribute_id) != null" class="fa fa-ban"></i>						
						&nbsp;
						&nbsp;
						<a href="#pc-product-attribute-create-modal" data-toggle="modal" data-ng-click="edit(attribute.productattribute_id)">
						{{ attribute.title }}
						</a>
					</div>			
					<div class="pull-right">
						<a href="#pc-product-attribute-create-modal" data-toggle="modal" data-ng-click="edit(attribute.productattribute_id)">
							<i class="fa fa-edit"></i>
						</a>						
						<a href="#" onclick="return false;" class="text-error" data-ng-click="remove(attribute.productattribute_id)">
							<i class="fa fa-trash-o"></i>
						</a>
					</div>		
					</div>
				</li>
			</ul>
		</div>
		
		<div class="row-fluid">
			<a href="#pc-product-attribute-create-modal" data-toggle="modal" class="btn btn-success paycart-attribute-add-window" data-ng-click="edit(0)">
				<i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo JText::_('COM_PAYCART_ADMIN_ATTRIBUTE_CREATE');?>
			</a>
		</div>		
	</div>	
	
	<div id="pc-product-attribute-create-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:800px; margin-left:-400px;" data-backdrop="static" data-keyboard="false">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-ng-click="cancel();">×</button>
			<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_ATTRIBUTE_CREATE');?></h3>
		</div>
		
		<div class="modal-body">	
			<div data-ng-show="message" class="alert alert-success">{{ message }}</div>
			<div data-ng-show="errMessage" class="alert alert-danger">{{ errMessage }}</div>
																						
			<div ng-bind-html-unsafe="edit_html">
			</div>
		</div>
		
		<div class="modal-footer text-center">
			<button class="btn" data-ng-click="cancel();" onclick="return false;" data-dismiss="modal"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CANCEL')?></button>
			<button class="btn btn-primary" data-ng-click="save();" onclick="return false;"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_SAVE')?></button>												
		</div>
	</div>
</div>