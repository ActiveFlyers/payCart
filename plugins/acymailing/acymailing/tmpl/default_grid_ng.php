<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		garima
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">	
	paycart.ng.productCategory = angular.module('pcngAcymailingApp', []);
	paycart.ng.productCategory.controller('pcngAcymailingCtrl', function($scope, $http, $timeout, $window){

		//variable initialization
		$scope.productCategories= productCategories;
		$scope.acymailingList	= acymailingList;
		$scope.listData 		=  listData;   
		$scope.showSaveButton	= false;
		$scope.errMessage		= [];
		$scope.message 			= [];
		$scope.state 			= [];
		$scope.list =  			  [];   

		//if any index is undefined, the first define with empty variable
		//this function will push the checked value in the temporary variable
		$scope.change = function(check,value){
		if(angular.isUndefined($scope.listData[$scope.selectedIndex])){
			$scope.listData[$scope.selectedIndex] = [];
		}

		
			
        if(check){
            $scope.list.push(value);
        }else{
             $scope.list.splice($scope.list.indexOf(value), 1);
        }
		};

		//when click on the click to add button.
		// 1. It will check whether any acymailing list is selected or not.
		// 2. It will check whether any category block is open or not
		// 3. It everything is OK, it will update the listData variable, which get show list in frontend
		$scope.showSelectedAcyList = function(){


		   //2.
		   angular.forEach($scope.state, function(value, key) {
				 showError = true;
				 if(value == true){
					 showError = false;
				 }
			});

			if(showError == true){
				alert("<?php echo JText::_("PLG_PAYCART_ACYMAILING_SELECT_LIST_OR_CATEGORY");?>");
				return false;
			}

			//3.
		 angular.forEach($scope.list, function(value, key) {
			if ($scope.listData[$scope.selectedIndex].indexOf(value)  == -1){
					$scope.listData[$scope.selectedIndex].push(value);
		 	}
		 });
		  // $scope.listData[$scope.selectedIndex] =  angular.copy( $scope.list[$scope.selectedIndex]);   

		 $scope.saveCategoryList($scope.selectedIndex);
		};

		//this function set the current active index category and also define the state of category block.
		//whether it is in open or close state. 
		 $scope.selectedIndex = 1;
		 $scope.state[1]      = true
		 $scope.select= function(index) {
		       if(angular.isUndefined($scope.state[index]) || $scope.selectedIndex != index){
		      		 $scope.state[index] = false;
		 		}
		       $scope.selectedIndex = index;
		       $scope.state[index] = !$scope.state[index];     
		 };

		 //function to remove acylist variable temprorily.
		 $scope.removeItem = function(index){
			 $scope.listData[$scope.selectedIndex].splice(index, 1);
			 $scope.showSaveButton	= true;
			 $scope.saveCategoryList($scope.selectedIndex);
			
		 }

		 //function to collect the data and save it to database
		 //once save show success or error message depending upon the result.
		 $scope.saveCategoryList = function(category_id) {
			 $http({
			        method  : 'POST',
			        url     : 'index.php?option=com_paycart&view=acymailing&task=saveCategoryList&format=json',
			        data    : paycart.jQuery.param({'category_id': category_id,'acyList':$scope.listData[category_id]}),  // pass in data as strings
			        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
			    }) 
			    .success(function(data) {										         
					data = rb.ajax.junkFilter(data);
		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage[category_id] = data.message;

		                $timeout(function() {
			              	 $scope.errMessage[category_id] = false;
			            }, 1500);
		            } else {
		            	// if successful, bind success message to message
		                $scope.message[category_id] = data.message;

		                $timeout(function() {
			              	 $scope.message[category_id] = false;
			              	$scope.showSaveButton	= false;
			            }, 1500);
		         									                										                
		            } 
			});
		 }
		
	});

</script>
<?php 
