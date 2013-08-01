/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PAYCART
* @subpackage	Javascript
* @contact 		team@readybytes.in
*/

if (typeof(paycart)=='undefined'){
	var paycart 	= {};
	paycart.$ 	= paycart.jQuery = rb.jQuery;
	paycart.ajax	= rb.ajax;
	paycart.ui	= rb.ui;
}

if (typeof(paycart.element)=='undefined'){
	paycart.element = {}
}

(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.

/*--------------------------------------------------------------
  URL related to works
   	url.modal 		: open a url in modal window
   	url.redirect 	: redirect current window to new url
   	url.fetch		: fetch the url and replace to given node 
--------------------------------------------------------------*/
paycart.url = {
  	modal: function( theurl, options){
		if( typeof options=== "undefined" ){
			var ajaxCall = {'url':theurl, 'data': {}, 'iframe': false};
		}	
		else{
		    var ajaxCall = {'url':theurl, 'data':options.data, 'iframe' : false};
		}

		paycart.ui.dialog.create(ajaxCall, '', 650, 300);
	},
		
	redirect:function(url){
		        document.location.href=url;
	}
};


/** ===================================================
 *  Common Javascript For Paycart
 *  ===================================================
 */
paycart.category =
	{
		add : function(categoryName, CallbackOnSuccess, CallbackOnError)
			{
				var link  = 'index.php?option=com_paycart&task=save&view=category';
				var data  = {'category_name': categoryName };
				paycart.ajax.go(link,data, CallbackOnSuccess, CallbackOnError);
			}
	};

paycart.alias =
	{
		add : function(title, CallbackOnSuccess, CallbackOnError)
			{
				var link  = 'index.php?option=com_paycart&task=go&view=product';
				var data  = {'title': title , 'method' : 'getalias' };
				paycart.ajax.go(link,data, CallbackOnSuccess, CallbackOnError);
			}
	};
paycart.attribute = 
	{
		add : function()
		{
	
		},
		
		display : function(CallbackOnSuccess, CallbackOnError)
		{
			var link  = 'index.php?option=com_paycart&task=go&view=attribute';
			var data  = {'method' : 'display' };
			paycart.ajax.go(link,data, CallbackOnSuccess, CallbackOnError);
		}
	}

// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(paycart.jQuery);