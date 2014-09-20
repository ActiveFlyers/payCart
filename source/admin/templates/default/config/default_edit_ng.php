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
	paycart.ng.config.controller('pcngConfigLogoCtrl', function($scope, $http){
		$scope.message 		= '';
		$scope.errMessage 	= '';
		$scope.company_logo = pc_company_logo;

		$scope.remove = function(image_id){			
			$http({
		        method  : 'POST',
		        url     : 'index.php?option=com_paycart&task=deleteCompanyLogo&view=config&format=json',
		        data    : paycart.jQuery.param({'company_logo':image_id}),  // pass in data as strings
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
		                $scope.company_logo = 0;											                										                
		            }
			});			
		};
	});
</script>
<?php 
