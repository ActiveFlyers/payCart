<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>
<script>

(function($) {
	/**
	 * Payment Gateway specific Javascript
	 */
	paycart.admin.paymentgateway = {};			
	paycart.admin.paymentgateway.getProcessorConfigHtml = function(){
	        var data = {'processor_type' 	:	$('#paycart_paymentgateway_form_processor_type').val(),
	                    'processor_id'		:	$('#paycart_paymentgateway_form_paymentgateway_id').val()
	                    };
	        var url	= 'index.php?option=com_paycart&view=paymentgateway&task=getConfigHtml';	
	        paycart.ajax.go(url, data);
	};
	
	paycart.admin.paymentgateway.setProcessorConfigHtml = function(response_data){
	        $('#pc-paymentgateway-processorconfig').html(response_data['html']);
	        paycart.jQuery('.hasTooltip').tooltip();	
	};	
	
	$(document).ready(function(){
		paycart.admin.paymentgateway.getProcessorConfigHtml();
		$('[data-pc-paymentgateway="processor"] select').change(function() {					
			paycart.admin.paymentgateway.getProcessorConfigHtml();				
		});		
	});		
})(paycart.jQuery);
	
</script>
<?php  
	