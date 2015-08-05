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

	 $(document).on('change','#paycart_product_form_type',function(){
		if( $(this).val() == '20'){ //use constant
			$('[data-pc-selector="measurement-details"]').addClass('hide').removeClass('show');	
			$('[data-pc-selector="downloadable_files"]').addClass('show').removeClass('hide');		
		}else{
			$('[data-pc-selector="measurement-details"]').addClass('show').removeClass('hide');
			$('[data-pc-selector="downloadable_files"]').addClass('hide').removeClass('show');
		}
	 });

	paycart.admin.product = {};
	paycart.admin.product.digital = {};

	paycart.admin.product.digital.window = function(main_id,teaser_id){
		var link  = 'index.php?option=com_paycart&task=editDigitalContent&view=product&main_id='+main_id+'&teaser_id='+teaser_id+'&lang_code='+pc_current_language;
		paycart.url.modal(link, null);
	};
	
	paycart.admin.product.digital.save = function(){
		if(typeof FormData == 'undefined'){
			//show error that try with a html5 supported browser
			$('[data-pc-seletor="digi-error-msg"]').removeClass('hide').addClass('show').html('<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_TRY_WITH_HTML5_SUPPORTED_BROWSER')?>');
		}
			
		var data     = new FormData();			
		var main     = paycart.jQuery('[name="paycart_product_file_form[digital][main_file]"]')[0].files[0];
		var teaser   = paycart.jQuery('[name="paycart_product_file_form[digital][teaser_file]"]')[0].files[0];
		
		data.append('main',main);
		data.append('teaser',teaser);
		
		data.append('title',paycart.jQuery('[name="paycart_product_file_form[digital][title]"]').val());
		data.append('media_id',paycart.jQuery('[name="paycart_product_file_form[digital][media_id]"]').val());
		data.append('teaser_media_id',paycart.jQuery('[name="paycart_product_file_form[digital][teaser_media_id]"]').val());
		data.append('media_lang_id',paycart.jQuery('[name="paycart_product_file_form[digital][media_lang_id]"]').val());
		data.append('teaser_media_lang_id',paycart.jQuery('[name="paycart_product_file_form[digital][teaser_media_lang_id]"]').val());
		data.append('lang_code',paycart.jQuery('[name="paycart_product_file_form[digital][lang_code]"]').val());

		// properly route the url
		ajax_url = rb.url.route('index.php?option=com_paycart&view=product&task=saveDigitalContent') + '&format=ajax';
		
		$.ajax(ajax_url, {
			type	: "POST",
			cache	: false,
			data	: data,
			processData: false,
	        contentType: false,
			timeout	: 600000,
			success	: function(msg) 
						{
							rb.ajax.success(msg,rb.ajax.default_success_callback,rb.ajax.default_error_callback); 
						},
			error	: function(Request, textStatus, errorThrown)
						{
							rb.ajax.error(Request, textStatus, errorThrown, rb.ajax.default_error_callback);
						}
		});
		    
	};

	paycart.admin.product.digital.save.success = function(response){
		var main_id   = response.files.main;
		var teaser_id = response.files.teaser;
		
		if(main_id){
			$('[data-pc-selector="pc-product-digital-files"]').append('<input type="hidden" name="paycart_product_form[digital]['+main_id+'][main]" value="'+main_id+'"/>');
		}
		if(teaser_id){
			$('[data-pc-selector="pc-product-digital-files"]').append('<input type="hidden" name="paycart_product_form[digital]['+main_id+'][teaser]" value="'+teaser_id+'"/>');
		}

		var html = "<tr>"+
				   "<td>"+response.files.title+"</td>"+
				   "<td>"+response.files.main_filename+"</td>"+
				   "<td>"+response.files.teaser_filename+"</td>"+
				   "<td>&nbsp;</td>"+
				   "</tr>";
		$('[data-pc-selector="digital-content-table"]').append(html);
		
		$('[data-pc-selector="digital-help-save"]').html('<span class="alert alert-warning"><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_SAVE_PRODUCT_TO_ATTACH_FILE_WITH_PRODUCT')?></span>');
		rb.ui.dialog.close();
	};

	paycart.admin.product.digital.save.error = function(response){
		var errorMsg = response.errorMsg;
		$('[data-pc-seletor="digi-error-msg"]').removeClass('hide').addClass('show').html(errorMsg);
	}	

	paycart.admin.product.deleteDigitalData = function(productId, mediaId, teaserMediaId){
		var link  = 'index.php?option=com_paycart&view=product&task=deleteDigitalContent';
		var data  = {'main_id' : mediaId, 'product_id' : productId, 'teaser_id' :teaserMediaId}

		paycart.ajax.go(link, data);			
	};

	paycart.admin.product.deleteDigitalData.success = function(response){
		var id = response.main_id;
		$('[data-pc-selector=row-'+id+']').remove();
	};
	
	paycart.admin.product.deleteDigitalData.error = function(response){
		$('[data-pc-selector="digital-help-save"]').html('<span class="alert alert-warning"><?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_DIGITAL_ERROR_DELETING_FILES')?></span>');
	};
		
})(paycart.jQuery);	
</script>

<?php 