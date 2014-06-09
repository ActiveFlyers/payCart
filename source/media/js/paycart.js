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
	
	
	paycart.state = 
	{
		/**
		 * response : [state_selector, state_options]
		 */
		html : function(response)
		{
			var state_selector = response['state_selector'];
			$(state_selector).html(response['state_options']);
			
		},

		onCountryChange	:	function(country_selector, state_selector)
		{
			var link = rb_vars.url.root +'index.php?option=com_paycart&view=state&task=getoptions';

			paycart.ajax.go( link, {'country_id' : $(country_selector).val(), 'state_selector' : state_selector });
		}
	};
	
	
	paycart.notification = 
	{
		// copy console API reference
		console : console,
		
		user : function(response) 
		{
		
		}
		
							
	};

	paycart.queue = 
		(function()
		{
			//private variable
			var _queue = [];
			
			// contain all available object
			var method	= {};
			
			method.enqueue = 
				function(script_content)
				{	
					//enqueue script into queue
					_queue.push(script_content)
				};
			
			method.dequeue = 
				function()
				{	
					//dequeue script from queue and execute
					eval(_queue.shift());
				};
			
			method.execute =
				function()
				{
					for (index = 0 ; index < _queue.length ; index++){
						method.dequeue();
					}
				}
				
			return method;
				
		})(); 

// ENDING :
// Scoping code for easy and non-conflicting access to $.
// Should be last line, write code above this line.
})(paycart.jQuery);