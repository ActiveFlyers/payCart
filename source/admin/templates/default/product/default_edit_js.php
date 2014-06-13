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
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'addvariant' && !setVariant()) {
			return false;
		}

		if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};

	
	(function($){

		// Change Attributes on bases of product type		
		typeAttributes = function(type) 
		{
			switch(type) 
			{//@PCTODO :: use constant
				case '10':	// Physical type
					$('.paycart_product_digital_file').hide();
					$('.paycart_product_quantity').show();
					break;
				case '20' :	// Digital Type
					$('.paycart_product_digital_file').show();
					$('.paycart_product_quantity').hide();
					break;
			}

		};	

		setVariant = function()
		{
			var variantOf = $('#product_id').val();

			// Creating Variant without Product Creation
			if(!variantOf) {
				// @PCTODO :: Alert to create Product first
				return false;
			}
			//set variation_of var on admin form beofre submit it
			$('#adminForm').append("<input type='hidden' name='variant_of' value='"+
					 variantOf+"' />");
 			return true;			
		};

		$(document).ready(function($){
			
			typeAttributes($('#paycart_form_type').val());

			$('#paycart_form_type').change( function() {
				typeAttributes($(this).val());
			});

			<!-- Callback function when Alias successfully generated				-->
			var callbackOnSuccess = function(data)
			{	// Add alias to element
				$('#paycart_form_alias').val(data[0][1]);
			};
			
			// When product title assign then create alias			
			$('#paycart_form_title').blur( function() 
			{
					var title = $(this).val();
			
					// if title empty or alias pre-define
					if (!title || $('#paycart_form_alias').val()) {
						return true;
					}

					// Get Product id
					var id = $('input[name="id"]').val();
					
					// pass title, Product ID, callbackOnSuccess,  callbackOnError
					//@PCTODO :: Proper Error-handling in callbackOnError
					paycart.admin.product.alias.add(	title, id,callbackOnSuccess,
										function(){alert('error in alias generating')}
							);
			});

			// When alias is empty then create new alias
			$('#paycart_form_alias').blur( function() 
			{
				var alias = $(this).val();
				var title = $('#paycart_form_title').val();

				// if alias pre-define Or product title empty 
				if (alias || !title) {
					return true;
				}

				// Get Product id
				var id = $('input[name="id"]').val();

				// pass title, Product ID , callbackOnSuccess,  callbackOnError
				//@PCTODO :: Proper Error-handling in callbackOnError
				paycart.admin.product.alias.add(	title, id,callbackOnSuccess,
									function(){alert('error in alias generating')}
						);
			});
			
		});
			
	 	
	})(paycart.jQuery);
	
	var pcProductApp = angular.module('pcProductApp', []);
</script>

<?php 