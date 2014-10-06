/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PAYCART
* @subpackage	Javascript
* @contact 		team@readybytes.in
*/

if (typeof(paycart)=='undefined'){
	var paycart		=	{};
	paycart.$		=	paycart.jQuery = rb.jQuery;
	paycart.ajax	=	rb.ajax;
	paycart.ui		=	rb.ui;
	paycart.url		=	rb.url;
	paycart.queue	= new Array();
	paycart.ng		= {};
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

	paycart.jui = {},
	
	paycart.jui.defaults = function(){
		
		// setup tooltips
		$('*[rel=tooltip]').tooltip();

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
			}
		});
	};
	
	/*
	 --------------------------------------------------------------
	  JSON request
	 --------------------------------------------------------------
	 */
	
	// @PCTODO :: Should be paycart.request.json
	// fetch json data
	paycart.request = function (request) 
	{
		if ( typeof request['url'] == "undefined"  ) {
			console.log('request url must be required')
			return;
		}
		
		request['url'] = request['url']+'&format=json';
		
		$.ajax({

			url		: request['url'] ,
						
		    cache	: ( typeof request['cache'] == "undefined" ) 
    					? false
						: request['cache'] ,
						
			data	: ( typeof request['data'] == "undefined" ) 
			    		? {}
						: request['data'] ,
						
			type 	: ( typeof request['type'] == "undefined" ) 
    					? 'POST'
						: request['type'] ,
						
		    success : function( response ) {

						//console.log ("Success:  " + response );

						//clear data (remove warnings and error)
						response = paycart.ajax.junkFilter(response);									

						// Any callback available
				    	if( typeof response['callback'] != "undefined"  && response['callback'] ) {
					    	//@PCTODO:: cross check function existing into paycart namespace  
				    		var callback = new Function(response['callback']);
				    		callback(response);
							return true;
						}

				    	// Any callback available
				    	if( typeof request['success_callback'] != "undefined"  && request['success_callback'] ) { 
				    		var callback = request['success_callback'];
				    		callback(response);
				    		return true;
						}

				    	if( typeof response['valid'] != "undefined" && response['valid'] == false) { 
							console.log ( {" response contain error :  " : response } );
				    		return false;
						}
						
						return true;
				    },

				error : function( response ) {

				    	console.log ({"Error on fetching JSON data :  " :response} );

				    	return response;
				    }
		  });
	};

	
/*--------------------------------------------------------------
  Address related to works
   	address.state 		: do all address related work
   		state.html	 	: set state options html on state selector
   		onCountryChange	: fetch states on country change
--------------------------------------------------------------*/
	
	paycart.address = {};
	
	paycart.address.state = 
	{
		/**
		 * response : [state_selector, state_options]
		 */
		html : function(response)
		{
			var state_selector = response['state_selector'];
			$(state_selector).html(response['state_option_html']);
			
			//reinitialte chosen (using in backend configuration) 
			$(state_selector).trigger("liszt:updated");			
		},

		onCountryChange	:	function(country_selector, state_selector, default_selected_state)
		{
			var link = rb_vars.url.root +'index.php?option=com_paycart&view=state&task=getoptions';

			paycart.ajax.go( link, {'country_id' : $(country_selector).val(), 'state_selector' : state_selector, 'default_state' : default_selected_state });
		}
	};
	
	//@PCTODO :: add seperatly JS file  
	// Paycart Triggers
	paycart.trigger = {};

	// define all cart related trigger 
	paycart.trigger.cart = {};
	paycart.trigger.cart.after = {};
	paycart.trigger.cart.after.updateproduct = function()
	{
		$.event.trigger( "onPaycartCartAfterUpdateproduct");
	};

	

// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(paycart.jQuery);