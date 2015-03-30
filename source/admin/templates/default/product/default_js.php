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
?>
<script type="text/javascript">

Joomla.submitbutton = function(task){
	if (task == 'save' || task == 'apply' ) {
		paycart.jQuery('[data-pc-selector="quantity"]').val('');	
	}
	var view 			= '<?php echo $this->getName();?>' ;
    var validActions 	= '<?php echo json_encode($this->_validateActions);?>' ;

	paycart.admin.grid.submit(view, task, validActions);
};

(function($){
	$(document).ready(function(){
	<?php if(!empty($error_fields)):?>
				var error_fields = <?php echo json_encode($error_fields);?>;
				for(var field_id in error_fields){
					if(error_fields.hasOwnProperty(field_id) == false){
						continue;
					}
					paycart.formvalidator.handleResponse(false, $('#'+error_fields[field_id]));
				}  

				paycart.formvalidator.scrollToError('#adminForm');	
	<?php endif;?>

		$('[data-pc-selector="addQuantity"]').on('click',function(){
			var newValue = $('[data-pc-selector="quantity"]').val();

			//check if form is valid
			if(paycart.formvalidator.isValid('#adminForm')){
				var oldValue = $('[data-pc-selector="paycart_product_form[quantity]"]').val();

				var currentValue = +newValue + +oldValue;
				
				$('[data-pc-selector="quantity"]').val('');
				$('[data-pc-selector="paycart_product_form[quantity]"]').val(currentValue);
				$('[data-pc-selector="quantityBadge"]').html(currentValue);

				var postData = $('#adminForm').serializeArray();
				postData.spinner_selector = '#paycart-ajax-spinner';
				paycart.ajax.go('index.php?option=com_paycart&view=product&task=save&format=ajax', postData);
			}			
		});
	
		$('[data-pc-selector="reduceQuantity"]').on('click',function(){
			var newValue = $('[data-pc-selector="quantity"]').val();

			//check if form is valid
			if(paycart.formvalidator.isValid('#adminForm')){
				var oldValue = $('[data-pc-selector="paycart_product_form[quantity]"]').val();
				
				var currentValue = ((+oldValue - +newValue) > 0)?(+oldValue - +newValue):0;
				
				$('[data-pc-selector=quantity]').val('');
				$('[data-pc-selector="paycart_product_form[quantity]"]').val(currentValue);
				$('[data-pc-selector="quantityBadge"]').html(currentValue);
				
				var postData = $('#adminForm').serializeArray();
				postData.spinner_selector = '#paycart-ajax-spinner';
				paycart.ajax.go('index.php?option=com_paycart&view=product&task=save&format=ajax', postData);
			}
		});
	});

	//script for quantity filter
	 $(document).on('change','[data-pc-selector="filter_paycart_product_quantity_range"]', function(){
		 	switch($(this).val()){
		 		//both
		 		case "0" : $('[data-pc-selector="filter_paycart_product_quantity_from"]').attr('value','');
						   $('[data-pc-selector="filter_paycart_product_quantity_to"]').attr('value','');
						   break;		 
				//in-stock		
		 		case "1" : $('[data-pc-selector="filter_paycart_product_quantity_to"]').attr('value','1');
						   $('[data-pc-selector="filter_paycart_product_quantity_from"]').attr('value','');
						   break;
				//out-of-stock	
		 		case "2" : $('[data-pc-selector="filter_paycart_product_quantity_from"]').attr('value','0');
				           $('[data-pc-selector="filter_paycart_product_quantity_to"]').attr('value','');
				           break;		
		 	}
		 	document.adminForm.submit();
	 });
})(paycart.jQuery);	
</script>

<?php 