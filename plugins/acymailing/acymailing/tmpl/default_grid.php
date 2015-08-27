<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		garima agal
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

JHtml::_('behavior.formvalidation');
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=productcategory'); 
	?>
</div>
<?php 
//echo $this->loadTemplate('grid_js');
echo $this->loadTemplate('grid_ng');
?>

<div class="pc-acymailing row-fluid" data-ng-app="pcngAcymailingApp">
<script type="text/javascript">
var productCategories = <?php echo json_encode($categories);?>;
var acymailingList 	  = <?php echo json_encode($acymailingList);?>;
var listData	      = <?php  echo json_encode($categoryAcymailingMapData);?>;

</script>
	<div data-ng-controller="pcngAcymailingCtrl" class="span10">		
		
		
		<!----------------------------category List--------------------------------->
		<div id="accordion2" class="accordion span5" >
		<legend><?php echo JText::_("PLG_PAYCART_ACYMAILING_CATEGORY_LIST")?></legend>
		<div class="accordion-group" data-ng-repeat="category in productCategories">
                  <div class="accordion-heading muted" ng-click="select(category.productcategory_id)" >
                    <a ng-href="#collapse{{category.productcategory_id}}" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed" style="background-color: #f5f5f5;">
                      {{category.htmlToShow}}{{ category.title }}
                    </a>
                  </div>
                  
                  <div data-ng-show="message[category.productcategory_id]" class="alert alert-success">{{ message[category.productcategory_id] }}</div>
                  <div data-ng-show="errMessage[category.productcategory_id]" class="alert alert-danger">{{ errMessage[category.productcategory_id] }}</div>
                  <div class="accordion-body collapse"  ng-attr-id="collapse{{category.productcategory_id}}" ng-class="($index==0) ? 'in' : ''">
                  
                    <div class="accordion-inner"  >
                       <ul>
                       <li data-ng-repeat="key in listData[category.productcategory_id]">
                       {{acymailingList[key].name}}     
                       <a data-ng-click="removeItem($index)"><?php echo JText::_("PLG_PAYCART_ACYMAILING_REMOVE_BUTTON");?></a>
                        </li>
                        </ul>	
                    </div>
                  </div>
        	 </div>
 	 	</div>
 	 	
		<!----------------------------Spacer------------------------------------------>
 	 	<div class="span1">&nbsp;</div>
 	 	
 	 	<!----------------------------Acymailing List--------------------------------->
 	 	
 	 	<div class="span6">
 	 		<legend><?php echo JText::_("PLG_PAYCART_ACYMAILING_ACYMAILING_LIST");?></legend>
 	 		
 	 		<input type="button" value="<?php echo JText::_("PLG_PAYCART_ACYMAILING_ADD_TO_LIST_AND_SAVE"); ?>" class="btn btn-success btn-small" id="acy-assign-group" data-ng-click="showSelectedAcyList()">
 	 		<br><br>
 	 		<div data-ng-repeat="data in acymailingList">
 	 			<input type="checkbox" name="acy_list_group[]" ng-value="{{data.list_id}}" ng-model="list" ng-change="change(list,data.list_id)">{{data.name}}<br>
 	 		</div>
 	 		
 	 	</div>

	</div>	

</div>
