<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
* @author		rimjhim jain
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );?>

<script type="text/javascript">

(function($){
	paycart.product = {};
	paycart.product.filter = {};

	paycart.product.filter.init = function(searchWord,filters){
		var link = 'index.php?option=com_paycart&view=search&task=filter&query='+searchWord;
		//parseJSON is used because filters are being passed as json string, so need to convert it object
		paycart.ajax.go(link , {'filters':$.parseJSON(filters), 'spinner_selector' : '#paycart-ajax-spinner'} );
		return false;
	};

	paycart.product.filter.bindActions = function(){
		paycart.product.arrange('add');
		
		$('[data-pc-result="filter"]').on('change',function(){
			paycart.product.filter.getResult();
		});

		$('[data-pc-loadMore="click"]').on('click', function(){
			paycart.product.loadMore();
		});

		$('[data-pc-category="click"]').on('click', function(){
			paycart.category.redirect($(this).data('pc-categorylink'),$(this).data('pc-categoryid'));
		});

		$('[data-pc-filter="remove"]').on('click', function(){			
			paycart.product.filter.remove(this);
		});	

		//slider related script
		$(".pc-range-slider").slider({});

		$(".pc-range-slider").on('slideStop', function (ev) {
			$('input[name="'+this.name+'"]').attr('value',ev.value);
			$('input[name="pagination_start"]').attr('value',0);

			paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
		});
		
		//remove the unwanted (duplicate) form
		//otherwise it creates issue when deselecting any checkbox of filter attributes
		wrapper_width = $('pc-product-search-content').width();
		screen_width  = $(window).width();
		if(screen_width < 768 && wrapper_width < 720){
			$('[data-pc-filter-form="desktop"]').remove();
		}
		else if(screen_width >= 768 || wrapper_width >= 720){
			$('[data-pc-filter-form="mobile"]').remove();
		}

		//scroll to first element
		var elem  = $('#pc-product-search-content');
		paycart.jQuery('html, body').animate({
			   scrollTop: elem.offset().top
			  }, 100);
		return true;
	};
	
	paycart.product.filter.getResult = function(){
		//set the sorting option to hidden input so that it get post with form
		var source = $('[data-pc-filter="sort-source"]').val();
		$('[data-pc-filter="sort-destination"]').val(source);
		$('input[name="pagination_start"]').attr('value',0);

		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
	};

	//each elem have data attribute pc-filter-applied-ref
	paycart.product.filter.remove = function(elem){
		var name = $(elem).data('pc-filter-applied-ref');

		$('input[name="pagination_start"]').attr('value',0);
		$('[name="'+name+'"]').attr('value','');
		$('[name="'+name+'"]').prop('checked',false);

		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=filter');
	};

	paycart.product.filter.submit = function(link){
		var disabledElem = $('[data-pc-result="filter"]:disabled:checked');

		//remove disabled + checked property so that disabled values can be posted
		$.each(disabledElem, function(k,v) {
			$('[name="'+v.name+'"]').removeAttr('disabled');
		});
		
		var postData 	= $('.pc-form-product-filter').serializeArray();

		//again make modified elements disabled 
		$.each(disabledElem, function(k,v) {
			$('[name="'+v.name+'"]').attr('disabled',true);
		});
		
		postData.spinner_selector = '#paycart-ajax-spinner';
		paycart.ajax.go(link, postData);
		return false;
	};

	paycart.product.loadMore = function(){
		paycart.product.filter.submit('index.php?option=com_paycart&view=search&task=loadMore');
	};

	paycart.product.loadMore.success = function(data){
		var response = $.parseJSON(data);
		
		$(".pc-products-wrapper").append(response.html);
		$('input[name="pagination_start"]').val(response.pagination_start);
		
		paycart.product.arrange('update');
	};	

	paycart.product.arrange = function(mode){
		// setup paycart-wrap size
		var sizeclass = paycart.helper.do_apply_sizeclass('.pc-products-wrapper');
		// arrange item layout
		if(mode=="add"){
			paycart.helper.do_grid_layout('#pc-products[data-columns]','.pc-product-outer', '.pc-product', sizeclass);
		}else{
			var start = $('input[name="pagination_start"]').val();
			paycart.helper.update_grid_layout('#pc-products[data-columns]','.pc-product-outer', '.pc-product-outer.pc-next-'+start, sizeclass);
		}
	};

	paycart.category = {};
	paycart.category.redirect = function(link,categoryId){
		$('.pc-form-product-filter').attr('action',link);
		$('input[name="filters[core][category]"]').val(categoryId);
		$('.pc-form-product-filter').submit();
	};
			
})(paycart.jQuery);
</script>