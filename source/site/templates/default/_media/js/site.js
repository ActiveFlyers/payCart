/**
* @copyright	Copyright (C) 2009-2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PAYCART
* @contact 		team@readybytes.in
*/

if (typeof(paycart)=='undefined'){
	var paycart = {}
}

// all admin function should be in admin scope 
if(typeof(paycart.site)=='undefined'){
	paycart.site = {};
}

//all admin function should be in admin scope 
if(typeof(Joomla)=='undefined'){
	Joomla = {};
}


(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.

	paycart.helper = {};
	
	paycart.helper.do_vertical_center = function(selector){
		$(selector).each(function(index){
			var parent_height = $(this).parent().innerHeight();
			$(this).css('line-height',parent_height+'px');
			//alert('height is '+ parent_height);
		});
	};
	
	paycart.helper.do_grid_layout = function(selector,wrapper, item, sizeclass){
		// setup size-xl case
		if(sizeclass == 'pc-size-xl'){
			var wrapper_width 	= $(wrapper).width();
			var item_width 		= $(item).width();
			var count 			= parseInt( wrapper_width / item_width) ;
			
			//alert('calc : '+wrapper_width + ' , ' + item_width + ' , ' + count);
					
			// add the real rule
			$('<style type="text/css">'+selector+':before { content : "' + count+' .pull-left" ;  } </style>').appendTo($('head'));
			document.styleSheets[0].insertRule(selector+':before { content : "'+ count+' .pull-left" ;  }', 0);
						
		}
		
		// all CSS is ready now we can setup salvattore
		var gridElements = document.querySelectorAll(selector);
		try{
			Array.prototype.forEach.call(gridElements, salvattore.register_grid);
		}catch(e){
			
		}
		
		
	};
	
	
	paycart.helper.do_apply_sizeclass = function(selector){
		var wrapper_width = $(selector).width();
		// catlogue parent wrapper CATEGORIZATION 
		// .pc-size-xs  320px to  479px
		// .pc-size-sm  480px to  719px
		// .pc-size-lg  720px to  979px;
		// .pc-size-xl  980px to all
	
		var sizeclass = 'pc-size-xl';
		if(wrapper_width < 480){
			sizeclass = 'pc-size-xs';
		}else {
			if (wrapper_width < 720) {
				sizeclass = 'pc-size-sm';
			}else {
				if (wrapper_width < 980) {
					sizeclass = 'pc-size-lg';
				}
			}
		}
		$(selector).addClass(sizeclass);
		return sizeclass;
	};	
/*--------------------------------------------------------------
  on Document ready 
--------------------------------------------------------------*/
$(document).ready(function(){

	// setup paycart-wrap size
	var sizeclass = paycart.helper.do_apply_sizeclass('.paycart-wrap');
	
	paycart.jui.defaults();
	
	paycart.helper.do_grid_layout('#pc-categories[data-columns]','.pc-categories-wrapper', '.pc-category', sizeclass);

	// also do resize category height = width
	$('.pc-category').height($('.pc-category').width());
	
	// vertical center align
	paycart.helper.do_vertical_center('.vertical-center-wrapper');
	
	// arrange item layout
	paycart.helper.do_grid_layout('#pc-products[data-columns]','.pc-products-wrapper', '.pc-product', sizeclass);
	
	
});

//ENDING :
//Scoping code for easy and non-conflicting access to $.
//Should be last line, write code above this line.
})(paycart.jQuery);

