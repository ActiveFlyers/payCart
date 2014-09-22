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
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">	
	paycart.ng.product = angular.module('pcngProductApp', []);
	paycart.ng.productCategory.controller('pcngProductCategoryImagesCtrl', function($scope, $http){
		$scope.message 		= '';
		$scope.errMessage 	= '';
		$scope.productCategoryId 	= pc_productCategory_id;
		$scope.cover_media  = pc_cover_media;

		$scope.remove = function(){			
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=deleteImage&view=productCategory&format=json',
		        data    : paycart.jQuery.param({'productCategory_id':$scope.productCategoryId}),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         
					data = rb.ajax.junkFilter(data);
		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage = data.message;
		            } else {
		            	// if successful, bind success message to message
		                $scope.message = data.message;
		                 $scope.cover_media = 0;											                										                
		            }
			});			
		};
	});
</script>
<?php 
