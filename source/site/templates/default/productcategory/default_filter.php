<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

//load assests that are required before loading related templates
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/css/slider.css');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/bootstrap-slider.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/salvattore.js');

echo $this->loadTemplate('filter_js');
?>

<style>
	.paycart .pc-product-filter-loader{
		background-color: rgba(255, 255, 255, 0.9);
	
	}
	.paycart .pc-product-filter-loader i.fa-spinner{
		position: fixed;
		top: 50%;
		left: 50%;
	}
</style>

<script type="text/javascript">
(function($){	
	 // Loader 
	$( document ).ajaxStart(function() {
		paycart.ajax.loader.show();
		}).ajaxStop(function() {
			paycart.ajax.loader.hide();
		});
	
	paycart.ajax.loader = 
	{
		show : function() 
		{
			$('#pc-filter-loader').show();
		},

		hide : function()
		{
			$('#pc-filter-loader').hide();
		}
	};
	
	$(document).ready(function(){
		paycart.product.filter.init('<?php echo isset($searchWord)?$searchWord:'';?>','<?php echo (isset($filters) && !empty($filters))?json_encode($filters):'';?>');
	});
})(paycart.jQuery);
</script>

<div id="pc-product-search-content">
	
<!-- ================================
	       Here comes the html 
	 ================================ -->
	 	 
</div>

<div class="modal-backdrop pc-product-filter-loader hide" id="pc-filter-loader">
    <i class="fa fa-spinner fa-3x fa-spin"></i>
</div>