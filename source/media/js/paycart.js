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
	paycart.queue	= 	new Array();
	paycart.ng		=	{};
	paycart.formvalidator = new Rb_FormValidator();
}

if (typeof(paycart.element)=='undefined'){
	paycart.element = {}
}

(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.
	$(document).ready(function(){
		// Assuption :: we will auto initialize validation when form have 'pc-form-validate' class
		paycart.formvalidator.initialize('form.pc-form-validate');
		setTimeout(paycart.loadTab, 100);
	});

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
		
		// display spinner 
		var spinner_selector = false;
		// before ajax start, check any spinner selector availble or not if yes then on it
		if ( typeof request['spinner_selector'] != "undefined" &&  $(request['spinner_selector']).length > 0 ) {
			spinner_selector = request['spinner_selector'];
			// remove from data otherwise it will be post
			delete  request['spinner_selector'];
			//show spinner
			$(spinner_selector).show();
		}	
		
		$.ajax({

			url		: paycart.url.route(request['url']) ,
						
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
			    			
			    		// stop spinner
						if ( spinner_selector ) {
							$(spinner_selector).hide();
						}

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

				    	if( typeof response['isValid'] != "undefined" && response['isValid'] == false) { 
							console.log ( {" response contain error :  " : response } );
				    		return false;
						}
						
						return true;
				    },

				error : function( response ) {
				    	
				    	// stop spinner
						if ( spinner_selector ) {
							$(spinner_selector).hide();
						}

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

			paycart.ajax.go( link, 
							{ 	'country_id' : $(country_selector).val(), 'state_selector' : state_selector, 
								'default_state' : default_selected_state,  'spinner_selector' :'#paycart-ajax-spinner'  
							}
					);
		}
	};
	
	//Paycart JS Event
	paycart.event = {};

	// define all cart related trigger 
	paycart.event.cart = {};
	paycart.event.cart.updateproduct = function()
	{
		$.event.trigger( "onPaycartCartUpdateproduct");
	};

// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(paycart.jQuery);



// Maintain Tab State
(function($){
	
    paycart.loadTab = function() {

        $('.paycart a[data-toggle="tab"]').on('click', function(e) {
            // Store the selected tab href in localstorage
            window.localStorage.setItem('tab-href', $(this).attr('href'));
        });

        var activateTab = function(href) {
            var $el = $('.paycart a[data-toggle="tab"]a[href*=' + href + ']');
            $el.tab('show');
        };

        var hasTab = function(href){
            return $('.paycart a[data-toggle="tab"]a[href*=' + href + ']').length;
        };

        $(document).ready(function(){
        if (localStorage.getItem('tab-href') && localStorage.getItem('tab-href') !== 'undefined') {
            // When moving from tab area to a different view
            if(!hasTab(localStorage.getItem('tab-href'))){
                localStorage.removeItem('tab-href');
                return true;
            }
            // Clean default tabs
            $('.paycart a[data-toggle="tab"]').parent().removeClass('active');
            var tabhref = localStorage.getItem('tab-href');
            
            // Add active attribute for selected tab indicated by url
            activateTab(tabhref);
            // Check whether internal tab is selected (in format <tabname>-<id>)
            var seperatorIndex = tabhref.indexOf('-');
            if (seperatorIndex !== -1) {
                var singular = tabhref.substring(0, seperatorIndex);
                var plural = singular + "s";
                activateTab(plural);
            }
        }});
    };
    
})(paycart.jQuery);