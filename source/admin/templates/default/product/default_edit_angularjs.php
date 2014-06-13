<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">	
	pcProductApp.controller('pcProductImagesCtrl', function($scope, $http){
		$scope.images 		= pc_product_images;
		$scope.activeIndex 	= '';
		$scope.activeImage 	= '';
		$scope.message 		= '';
		$scope.errMessage 	= '';
		$scope.productId 	= pc_product_id;
		
		$scope.setActiveImage = function(index){
				$scope.message = '';
				$scope.errMessage = '';
				$scope.activeIndex = index;
				$scope.activeImage = angular.copy($scope.images[index]);																						
		};

		// process the form
		$scope.save = function() {
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&view=media&task=save&format=json',
		        data    : paycart.jQuery.param({'paycart_form' : $scope.activeImage}),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         

		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage = data.message;											                											                
		            } else {
		            	// if successful, bind success message to message
		                $scope.message = data.message;
		                $scope.images[$scope.activeIndex] = angular.copy($scope.activeImage);											                										                
		            }
			});
		};

		$scope.cancel = function(){												
			$scope.activeIndex = '';
			$scope.activeImage = '';
		};

		$scope.remove = function(index){			
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=deleteImage&view=product&format=json',
		        data    : paycart.jQuery.param({'image_id': $scope.images[index].media_id, 'product_id':$scope.productId}),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         

		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage = data.message;
		            } else {
		            	// if successful, bind success message to message
		                $scope.message = data.message;
		                delete $scope.images[index];											                										                
		            }
			});			
		};
	});
</script>
<?php 