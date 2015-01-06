<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

// load angular
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">	

(function($)
	{

	paycart.ng.cart = angular.module('pcngCartApp', []);

	paycart.ng.cart.controller('pcngCartShipmentCtrl', function($scope, $http, $timeout){
		$scope.message 		   = false;
		$scope.shipments       = pc_shipments;
		$scope.cartId	       = pc_shipment_cartId;
		$scope.shippingMethods = pc_shipment_shippingMethods;
		$scope.status	       = pc_shipment_status;
		$scope.products		   = pc_shipment_products;
		$scope.tempArray	   = pc_shipment_tempArray;
		$scope.tempStatus	   = pc_shipment_tempStatus;
		
		/*
	     * save new/existing shipment
		 */
		$scope.save = function(index){
		    //it is required to copy the data, otherwise hash key will also get posted
			var postData = angular.copy($scope.shipments[index]);
						
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&view=cart&task=saveShipment&format=json',
		        data    : paycart.jQuery.param({'shipmentDetails':postData}),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         
					data = rb.ajax.junkFilter(data);
					$scope.initChosenToolTip();
					
		            if (!data.valid) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.shipments[index].errMessage = data.message;

		                //remove message after timeout
			            $timeout(function() {
			              	 $scope.shipments[index].errMessage = false;
			            }, 1500);
			               
		            } else {
		               // if successful, bind success message to message
		               $scope.shipments[index] = data.data;
		               $scope.shipments[index].message = data.message;
		               $scope.shipments[index].errMessage = false;
   
					   //update status
					   $scope.tempStatus[index] = $scope.getStatus(data.data.status, data.data.shipment_id);	
		               
					   //remove message after timeout
		               $timeout(function() {
		                	 $scope.shipments[index].message = false;
		               }, 1500);		                
		            }
			});			
		};

		/*
	     * delete the selected shipment
		 */
		$scope.remove = function(index){			
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&view=cart&task=removeShipment&format=json',
		        data    : paycart.jQuery.param({'shipment_id': $scope.shipments[index].shipment_id}),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         
					data = rb.ajax.junkFilter(data);
					$scope.initChosenToolTip();
					 
		            if (!data.valid) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.shipments[index].errMessage = data.message;

		            	//if the shipment is not yet saved then just remove shipment and do nothing
		            	if(!$scope.shipments[index].shipment_id){
							$scope.shipments.splice(index,1);
							return false;
			            }

		                //remove message after timeout
			            $timeout(function() {
			              	 $scope.shipments[index].errMessage = false;
			            }, 1500);									                										                
		                
		            } else {
		            	// if successful, bind success message to message
		                $scope.message  = data.message;
		                $scope.shipments.splice(index,1);

		                //remove message after timeout
			            $timeout(function() {
			              	 $scope.message = false;
			            }, 1500);										                										                
		            }
			});			
		};

		/*
	     * Add a blank box for adding new shipment
		 */
		$scope.addNewShipment = function(){
			var data = {};
			data = {'products' :[{}]};
			$scope.shipments.push(data);
			$scope.initChosenToolTip();
			return false;
		};

		/*
	     * Add elements to attach more product+quantity to a shipment  
		 */
		$scope.addMoreProduct = function(index){
			var data = {};
			$scope.shipments[index].products.push(data);
			$scope.initChosenToolTip();
			return false;
		};

		/*
	     * Detach the given product+quantity option from a shipment 
		 */
		$scope.removeProduct = function(shipmentIndex,productIndex){
			var data = {};
			$scope.shipments[shipmentIndex].products.splice(productIndex,1);
			return false;
		};

		/*
		 * Initiate chosen and tooltip
		 */
		$scope.initChosenToolTip = function(){
			$timeout(function() {
				paycart.jQuery('.hasTooltip').tooltip();
				
				paycart.jQuery('select').chosen({
				    "disable_search_threshold": 10,
				    "allow_single_deselect": true
				});
			});
		};

		$scope.init = function(index) {
			if($scope.shipments[index+1]){
				$scope.tempArray = [index,index+1];
				return $scope.tempArray;	
			}
			$scope.tempArray = [index];
			return $scope.tempArray;
		};

		$scope.getStatus = function(currentStatus, shipmentId){

			var shipmentStatus = angular.copy($scope.status);

			for(a=0; a<shipmentStatus.length; a++){
				shipmentStatus[a].disabled = false;
				if(!shipmentId && shipmentStatus[a].value != "<?php echo Paycart::STATUS_SHIPMENT_PENDING?>"){
					shipmentStatus[a].disabled = true;
					continue;
				}

				if((currentStatus == "<?php echo Paycart::STATUS_SHIPMENT_PENDING?>") && 
				   (shipmentStatus[a].value !== "<?php echo Paycart::STATUS_SHIPMENT_PENDING?>" &&  
					shipmentStatus[a].value !== "<?php echo Paycart::STATUS_SHIPMENT_DISPATCHED?>")){
					shipmentStatus[a].disabled = true;
					continue;
				}

				if((currentStatus == "<?php echo Paycart::STATUS_SHIPMENT_DISPATCHED?>") && 
				   (shipmentStatus[a].value == "<?php echo Paycart::STATUS_SHIPMENT_PENDING?>")){
					shipmentStatus[a].disabled = true;
					continue;
				}

				if((currentStatus == "<?php echo Paycart::STATUS_SHIPMENT_DELIVERED?>") && 
				   (shipmentStatus[a].value !== "<?php echo Paycart::STATUS_SHIPMENT_DELIVERED?>")){
					shipmentStatus[a].disabled = true;
					continue;
				}

				if((currentStatus == "<?php echo Paycart::STATUS_SHIPMENT_FAILED?>") && 
				   (shipmentStatus[a].value !== "<?php echo Paycart::STATUS_SHIPMENT_FAILED?>")){
					shipmentStatus[a].disabled = true;
					continue;
				}
			}

			return shipmentStatus;
		}
		
	});
	
	paycart.ng.cart.directive('ngConfirmClick', [
        function(){
              return{
                    link: function (scope, element, attr) {
                           var msg = attr.ngConfirmClick || "<?php echo JText::_("COM_PAYCART_ADMIN_SHIPMENT_STATUS_CHANGE_CONFIRMATION");?>";
                           var clickAction = attr.confirmedClick;
                           element.bind('click',function (event) {
	                           if ( window.confirm(msg) ) {
	                              	 scope.$apply(clickAction)
	                           }
                           });
                     }
              };
    }]);


	paycart.ng.cart.controller
		('pcngCartNextActionCtrl', 
				
				function($scope, $http, $timeout)
				{
					
					$scope.selected = null;	// action
					$scope.task = null; 	// task
					$scope.task_value='';

					// On Any Action Selection						
					$scope.onActionSelection = function(selected)
					{	
						// select action
						$scope.selected  = selected;

						// set task according to action
						switch($scope.selected) {
							case 'approve' :
								$scope.task = 'approve';
								$scope.task_value= '<?php echo JText::_('COM_PAYCART_ADMIN_APRROVE_TASK');?>';
								break;
							case 'pay_by_transaction_id' :
							case 'pay_by_anymean' :
								$scope.task = 'pay';
								$scope.task_value= '<?php echo JText::_('COM_PAYCART_ADMIN_PAY_TASK');?>' ;	
								break;
							default :
								$('input[name="action"]').prop('checked', false); 	//unchecked radio button
								$scope.task = null; 	
								$scope.task_value= '';
						} 
					};

					// On Any Action Execution
					$scope.onActionExecute = function() 
					{
						// if form is validate then submit it 
						if (paycart.formvalidator.isValid(document.id('pc-cart-action-form'))) {
							$("#pc-cart-action-form").submit();
						}
						return false;
					}					
				}
		);
    
        
	})(paycart.jQuery)



</script>
<?php 