<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">	
	paycart.ng.product.controller('pcngProductImagesCtrl', function($scope, $http){
		$scope.images 		= pc_product_images;
		$scope.activeIndex 	= '';
		$scope.activeImage 	= '';
		$scope.message 		= '';
		$scope.errMessage 	= '';
		$scope.productId 	= pc_product_id;
		$scope.cover_media  = pc_cover_media;
		
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
					data = rb.ajax.junkFilter(data);
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
					data = rb.ajax.junkFilter(data);
		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage = data.message;
		            } else {
		            	// if successful, bind success message to message
		                $scope.message = data.message;
		                delete $scope.images[index];	
		                $scope.cover_media = data.coverMedia;										                										                
		            }
			});			
		};
	});


	paycart.ng.product.controller('pcngProductAttributeCtrl', function($scope, $http){
		$scope.available = pc_attributes_available;
		$scope.added = pc_attributes_added;		
		$scope.edit_html = '';
		$scope.message 		= '';
		$scope.errMessage 	= '';

		// IMP : this variable is required to update the url of getting editHtml of attribute
		//  	 Once you update the attribute then it must get reflected in the added attribute also, so random is used for this purpose
		var random = Math.random();
		
		$scope.getUrl = function(attribute_id, value){
			return 'index.php?option=com_paycart&task=getEditHtml&view=productattribute&productattribute_id=' + attribute_id +'&format=json&value='+value+'&r=' + random;
		};
		
		$scope.addToProduct = function(attribute_id){
			var index = $scope.addedAtIndex($scope.added, attribute_id);
			if(index != null){
				// do nothing
				return false;
			}

			var data = {};			
			data.productattribute_id 	= attribute_id;
			data.value			 	= '';
			
			$scope.added.push(data);
			return false;			
		};
		
		$scope.removeFromProduct = function(attribute_id){
			var index = $scope.addedAtIndex($scope.added, attribute_id);
			if(index == null){
				// do nothing
				return false;
			}

			$scope.added.splice(index, 1);
			return false;			
		};

		$scope.edit = function(productattribute_id){
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=edit&view=productattribute&format=json&productattribute_id='+productattribute_id,
		        data    : '',
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {
		    	data = rb.ajax.junkFilter(data);
		    	$scope.edit_html = data;
		    });
		};			

		$scope.addedAtIndex = function(input, id) {
		    var i=0, len=input.length;
		    for (; i<len; i++) {
		      if (input[i].productattribute_id == id) {
		        return i;
		      }
		    }
		    
		    return null;
		 };
		  
		$scope.save = function(){
			var elem = rb.jQuery('[data="pc-json-attribute-edit"]');
			var data = rb.jQuery(elem).serializeArray();
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=save&view=productattribute&format=json',
		        data    : paycart.jQuery.param(data),  // pass in data as strings
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

		            	var index = $scope.addedAtIndex($scope.available, data.productattribute_id);
		                if( index != null){
		                	$scope.available[index] = data.productattribute; 
		                }
		                else{			
		                	$scope.available.push(data.productattribute);
		                }

		                $scope.edit(data.productattribute_id);
		                
		                random = Math.random();
		            }
			});	
		};

		$scope.remove = function(productattribute_id){			
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=deleteAttribute&view=productattribute&format=json',
		        data    : paycart.jQuery.param({'productattribute_id': productattribute_id}),  // pass in data as strings
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

		                var index = $scope.addedAtIndex($scope.available, productattribute_id);
		                if( index != null){
		                	$scope.available.splice(index, 1);		                	
		                }

		                index = $scope.addedAtIndex($scope.added, productattribute_id);
		                if( index != null){
		    				// do nothing
		                	$scope.added.splice(index, 1);
		    			}
		    			
		                return false;		                								                										                
		            }
			});			
		};
		
		$scope.cancel = function(){
			$scope.edit_html = '';
		};					         
			
	});
</script>
<?php 
