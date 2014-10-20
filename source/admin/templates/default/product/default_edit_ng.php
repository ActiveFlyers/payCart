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
// Depends on jQuery UI
JHtml::_('jquery.ui', array('core', 'sortable'));
Rb_Html::script('com_paycart/jquery.ui.draggable.js');
Rb_Html::script('com_paycart/jquery.ui.droppable.js');		
		
?>
<script type="text/javascript">	
	(function($){
		$(document).ready(function(){
			var attrStartIndex = 0;
			var attrstartPosition = '';
			
			$('.pc-attribute').draggable({helper: "clone", revert: 'invalid'});

			$('.pc-product-attribute-list')
				.sortable({
					connectWith : '.pc-product-attribute-list', 
					placeholder: "ui-state-highlight",
					start:function (e, ui) {
						attrStartIndex = ($(ui.item).index());
						attrstartPosition = $(ui.item).parent().attr('data-pc-product-position');
					},
					stop:function (e, ui) {
						var newIndex = ($(ui.item).index());
						var newPosition = $(ui.item).parent().attr('data-pc-product-position');
						var scope = angular.element(document.getElementById('pcngProductAttributeCtrl')).scope();
						scope.reorder(attrStartIndex, attrstartPosition, newIndex, newPosition);
						scope.$apply();
					}
				});
			
			$('.pc-product-attribute-list').droppable({
					accept : '.pc-attribute-draggable',
					hoverClass: "pc-droppable-highlight",
					drop:function(e,source){
						var position  = $(this).attr('data-pc-product-position');
						var scope = angular.element(document.getElementById('pcngProductAttributeCtrl')).scope();				
						scope.addToProduct(position, source.draggable.attr('data-attribute-id'));
						scope.$apply();
			    	}
			});

			var startIndex = 0;
			$(".thumbnails").disableSelection();
			$(".thumbnails").sortable({						
				start:function (e, ui) {
					startIndex = ($(ui.item).index());
				},
				stop:function (e, ui) {
					var newIndex = ($(ui.item).index());
					var scope = angular.element(document.getElementById('pcngProductImagesCtrl')).scope();
					scope.reorder(startIndex, newIndex);
					scope.$apply();
				}
			});			
		});
	})(rb.jQuery);

	paycart.ng.product = angular.module('pcngProductApp', []);
	paycart.ng.product.controller('pcngProductImagesCtrl', function($scope, $http){
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
					data = paycart.ajax.junkFilter(data);
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
					data = paycart.ajax.junkFilter(data);
		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage = data.message;
		            } else {
		            	// if successful, bind success message to message
		                $scope.message = data.message;
		                $scope.images.splice(index, 1);                										                
		            }
			});			
		};

		$scope.reorder = function(startIndex, newIndex){
			var toMove = $scope.images[startIndex];
			$scope.images.splice(startIndex,1);			
			$scope.images.splice(newIndex,0,toMove);

			var imageids = [];
			for(var index in $scope.images){
				if($scope.images.hasOwnProperty(index)){
					imageids.push($scope.images[index].media_id);
				}
			} 
			
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=reorderImages&view=product&format=json',
		        data    : paycart.jQuery.param({'image_ids': imageids, 'product_id':$scope.productId}),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         
					data = paycart.ajax.junkFilter(data);
		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                alert('Error');
		            } else {
		            											                										                
		            }
			});
		};		
	});


	paycart.ng.product.controller('pcngProductAttributeCtrl', function($scope, $timeout, $http){
		$scope.available = pc_attributes_available;
		$scope.added = pc_attributes_added;
		$scope.positions = pc_positions;		
		$scope.edit_html = '';
		$scope.message 		= '';
		$scope.errMessage 	= '';

		// IMP : this variable is required to update the url of getting editHtml of attribute
		//  	 Once you update the attribute then it must get reflected in the added attribute also, so random is used for this purpose
		var random = Math.random();
		
		$scope.getUrl = function(attribute_id, value){
			return 'index.php?option=com_paycart&task=getEditHtml&view=productattribute&productattribute_id=' + attribute_id +'&format=json&value='+value+'&r=' + random;
		};
		
		$scope.addToProduct = function(position, attribute_id){
			if(typeof($scope.added[position]) == 'undefined'){
				$scope.added[position] = [];	
			}
			else{
				var index = $scope.addedAtIndex($scope.added[position], attribute_id);
				if(index != null){
					// do nothing
					return false;
				}
			}
			
			var data = {};			
			data.productattribute_id 	= attribute_id;
			data.value			 	= '';
			
			$scope.added[position].push(data);
			return false;			
		};

		$scope.isAlreadyAdded = function(added, attribute_id){
			for(var i in $scope.positions){
				if($scope.positions.hasOwnProperty(i)){
					if(typeof(added[$scope.positions[i]]) == 'undefined'){
						continue;
					}
					
					var index = $scope.addedAtIndex(added[$scope.positions[i]], attribute_id);
					if(index != null){
						return true;
					}
				}
			}
			
			return false;			
		};
		
		$scope.removeFromProduct = function(position, index){
			$scope.added[position].splice(index, 1);			
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
		    	data = paycart.ajax.junkFilter(data);
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
			if(!paycart.formvalidator.isValid(document.id('paycart_productattribute_form'))){
				return false;
			}
			
			var elem = paycart.jQuery('[data="pc-json-attribute-edit"]');
			var data = paycart.jQuery(elem).serializeArray();
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=save&view=productattribute&format=json',
		        data    : paycart.jQuery.param(data),  // pass in data as strings
		        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
		    })
		    .success(function(data) {										         
		    		data = paycart.ajax.junkFilter(data);
		    	
		            if (!data.success) {					            	
		            	// if not successful, bind errors to error variables
		                $scope.errMessage = data.message;
		                if(data.error_fields.length > 0){
			                for(var field_id in data.error_fields){
								if(data.error_fields.hasOwnProperty(field_id) == false){
									continue;
								}
								paycart.formvalidator.handleResponse(false, paycart.jQuery('#'+data.error_fields[field_id]));
							}	                
		            	}
		            } else {
		            	// if successful, bind success message to message
		                $scope.message = '';

		            	var index = $scope.addedAtIndex($scope.available, data.productattribute_id);
		                if( index != null){
		                	$scope.available[index] = data.productattribute; 
		                }
		                else{			
		                	$scope.available.push(data.productattribute);				                		                	
		                }

			            // $time out will call the fucntion automatically when $digest is done
	                	// replace ment of $scope.$apply();
	                	$timeout(function() {
	                	// load draggable
			            	paycart.jQuery('[data-attribute-id="'+data.productattribute_id+'"]').draggable({helper: "clone", revert: 'invalid'});
	                	}); 
	                	
			            $scope.edit(data.productattribute_id);
		                
		                random = Math.random();

		                // close model after saving
		                paycart.jQuery('#pc-product-attribute-create-modal').modal('hide');
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
		    		data = paycart.ajax.junkFilter(data);
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
			$scope.message = '';
			 $scope.errMessage = '';
		};					         

		$scope.reorder = function(attrStartIndex, attrstartPosition, newIndex, newPosition){
			var toMove = $scope.added[attrstartPosition][attrStartIndex];
			$scope.added[attrstartPosition].splice(attrStartIndex,1);

			if(typeof($scope.added[newPosition]) == 'undefined'){
				$scope.added[newPosition] = [];	
			}
			$scope.added[newPosition].splice(newIndex,0,toMove);
		};
	});
</script>
<?php 
