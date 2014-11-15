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



/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Unobtrusive Form Validation library
 *
 * Inspired by: Chris Campbell <www.particletree.com>
 *
 * @package     Joomla.Framework
 * @subpackage  Forms
 * @since       1.5
 */
var PCFormValidator = function() {
	var $, handlers, inputEmail, custom,

 	setHandler = function(name, fn, en) {
 	 	en = (en === '') ? true : en;
 	 	handlers[name] = {
 	 	 	enabled : en,
 	 	 	exec : fn
 	 	};
 	},

 	findLabel = function(id, form){
 	 	var $label, $form = jQuery(form);
 	 	if (!id) {
 	 	 	return false;
 	 	}
 	 	$label = $form.find('#' + id + '-lbl');
 	 	if ($label.length) {
 	 	 	return $label;
 	 	}
 	 	$label = $form.find('label[for="' + id + '"]');
 	 	if ($label.length) {
 	 	 	return $label;
 	 	}
 	 	return false;
 	},

 	handleResponse = function(state, $el, type, msg) {
 		
 		if($el.attr('id')){
 			var $label = $el.data('label');
 			var $error = $('[for="'+$el.attr('id')+'"]').not($label);
 		}
 		else{
 			var $error = $('[for="'+$el.selector+'"]');
 			var $label = false; 
 		}
 		
 		type = typeof type !== 'undefined' ? type : 'error';
 		msg = typeof msg !== 'undefined' ? msg : $el.attr('error-message');
 		
 	 	
 	 	var $error = $('[for="'+$el.attr('id')+'"]').not($label);

 	 	// Set the element and its label (if exists) invalid state
 	 	if (state === false) {
 	 	 	$el.addClass('invalid').attr('aria-invalid', 'true'); 	 	 	
 	 	 	if($error){
 	 	 		$error.addClass('show').html(msg);
 	 	 	}
 	 	 	if($label){
 	 	 	 	$label.addClass('invalid').attr('aria-invalid', 'true');
 	 	 	}
 	 	} else {
 	 	 	$el.removeClass('invalid').attr('aria-invalid', 'false'); 	 	 	
 	 	 	if($error){
 	 	 		$error.removeClass('show');
 	 	 	}
 	 	 	if($label){
 	 	 	 	$label.removeClass('invalid').attr('aria-invalid', 'false');
 	 	 	}
 	 	}
 	},

 	validate = function(el) {
 	 	var $el = jQuery(el), tagName, handler;
 	 	// Ignore the element if its currently disabled, because are not submitted for the http-request. For those case return always true.
 	 	if ($el.attr('disabled')) {
 	 	 	handleResponse(true, $el);
 	 	 	return true;
 	 	}
 	 	// If the field is required make sure it has a value
 	 	if ($el.attr('required') || $el.hasClass('required')) {
 	 	 	tagName = $el.prop("tagName").toLowerCase();
 	 	 	if (tagName === 'fieldset' && ($el.hasClass('radio') || $el.hasClass('checkboxes'))) {
 	 	 	 	if (!$el.find('input:checked').length){
 	 	 	 	 	handleResponse(false, $el);
 	 	 	 	 	return false;
 	 	 	 	}
 	 	 	//If element has class placeholder that means it is empty.
 	 	 	} else if (!$el.val() || $el.hasClass('placeholder') || ($el.attr('type') === 'checkbox' && !$el.is(':checked'))) {
 	 	 	 	handleResponse(false, $el);
 	 	 	 	return false;
 	 	 	}
 	 	}
 	 	// Only validate the field if the validate class is set
 	 	handler = ($el.attr('class') && $el.attr('class').match(/validate-([a-zA-Z0-9\_\-]+)/)) ? $el.attr('class').match(/validate-([a-zA-Z0-9\_\-]+)/)[1] : "";
 	 	if (handler === '') {
 	 	 	handleResponse(true, $el);
 	 	 	return true;
 	 	}
 	 	// Check the additional validation types
 	 	if ((handler) && (handler !== 'none') && (handlers[handler]) && $el.val()) {
 	 	 	// Execute the validation handler and return result
 	 	 	if (handlers[handler].exec($el, $el.val()) !== true) {
 	 	 	 	handleResponse(false, $el);
 	 	 	 	return false;
 	 	 	}
 	 	}
 	 	// Return validation state
 	 	handleResponse(true, $el);
 	 	return true;
 	},

 	isValid = function(form) {
 	 	var valid = true, i, message, errors, error, label;
 	 	// Validate form fields
 	 	jQuery.each(jQuery(form).find('input, textarea, select, fieldset, button'), function(index, el) {
 	 	 	if ($(el).is(':visible') && validate(el) === false) {
 	 	 	 	valid = false;
 	 	 	}
 	 	});
 	 	// Run custom form validators if present
 	 	jQuery.each(custom, function(key, validator) {
 	 	 	if (validator.exec() !== true) {
 	 	 	 	valid = false;
 	 	 	}
 	 	});
 	 	if (!valid) {
 	 	 	message = Joomla.JText._('JLIB_FORM_FIELD_INVALID');
 	 	 	
 	 	 	scrollToError(form);
 	 	 	
// 	 	 	Joomla.renderMessages(error);
 	 	}	 	
 	 	
 	 	return valid;
 	},
 	
 	scrollToError = function(form){
 		errors = jQuery(form).find("input.invalid, textarea.invalid, select.invalid, fieldset.invalid, button.invalid");
 	 	 	
 	 	var el = $(errors[0]);
		var elOffset = el.offset().top;
 	    var elHeight = el.height();
 	    var windowHeight = $(window).height();
 	    var offset;

 	    if (elHeight < windowHeight) {
 	    	offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
 	    }
 	    else {
 	    	offset = elOffset;
 	    }
 	  
 	 	$('html, body').animate({
 	        scrollTop: offset
 	    }, 1000);
 	},
 	
 	attachToForm = function(form) {
 	 	var inputFields = [];
 	 	// Iterate through the form object and attach the validate method to all input fields.
 	 	$(form).find('input, textarea, select, fieldset, button').each(function() {
 	 	 	var $el = $(this), id = $el.attr('id'), tagName = $el.prop("tagName").toLowerCase();
 	 	 	if(!$el.is(':visible')){
 	 	 		return true;
 	 	 	}
 	 	 	if ($el.hasClass('required')) {
 	 	 	 	$el.attr('aria-required', 'true').attr('required', 'required');
 	 	 	}
 	 	 	if ((tagName === 'input' || tagName === 'button') && $el.attr('type') === 'submit') {
 	 	 	 	if ($el.hasClass('validate')) {
 	 	 	 	 	$el.on('click', function() {
 	 	 	 	 	 	return isValid(form);
 	 	 	 	 	});
 	 	 	 	}
 	 	 	} else {
 	 	 	 	if (tagName !== 'fieldset') {
 	 	 	 	 	$el.on('blur', function() {
 	 	 	 	 	 	return validate(this);
 	 	 	 	 	});
 	 	 	 	 	if ($el.hasClass('validate-email') && inputEmail) {
 	 	 	 	 	 	$el.get(0).type = 'email';
 	 	 	 	 	}
 	 	 	 	}
 	 	 	 	$el.data('label', findLabel(id, form));
 	 	 	 	inputFields.push($el);
 	 	 	}
 	 	});
 	 	$(form).data('inputfields', inputFields);
 	},

 	initialize = function(selector) {
 	 	$ = jQuery.noConflict();
 	 	handlers = {};
 	 	custom = custom || {};

 	 	inputEmail = (function() {
 	 	 	var input = document.createElement("input");
 	 	 	input.setAttribute("type", "email");
 	 	 	return input.type !== "text";
 	 	})();
 	 	// Default handlers
 	 	setHandler('username', function(element, value) {
 	 	 	regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
 	 	 	return !regex.test(value);
 	 	});
 	 	setHandler('password', function(element, value) {
 	 	 	regex = /^\S[\S ]{2,98}\S$/;
 	 	 	return regex.test(value);
 	 	});
 	 	setHandler('numeric', function(element, value) {
 	 	 	regex = /^(\d|-)?(\d|,)*\.?\d*$/;
 	 	 	return regex.test(value);
 	 	});
 	 	setHandler('integer', function(element, value) {
 	 	 	regex = /^(\d|-)?(\d|,)*\d*$/;
 	 	 	return regex.test(value);
 	 	});
 	 	setHandler('email', function(element, value) {
 	 	 	regex = /^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
 	 	 	return regex.test(value);
 	 	});
 	 	setHandler('image', function (element, value) {
			var imageSize  = 0;
			var fileField = element[0]; //as element is an object
			for (i = 0; i < fileField.files.length; i++){
				  //inputField.files[0].size gets the size of your file.
				  imageSize +=  fileField.files[i].size;
			}
			return (element.data('fileuploadlimit') > imageSize); 
	    });
 	 	// Attach to forms with class 'form-validate'
 	 	jQuery(selector).each(function() {
 	 	 	attachToForm(this);
 	 	}, this);
 	};
 
 	return {
 		initialize : initialize,
 	 	isValid : isValid,
 	 	validate : validate,
 	 	setHandler : setHandler,
 	 	attachToForm : attachToForm,
 	 	custom: custom,
 	 	handleResponse : handleResponse,
 	 	scrollToError : scrollToError
 	};
};

paycart.formvalidator = null;
jQuery(function() {
	paycart.formvalidator = new PCFormValidator();
	paycart.formvalidator.initialize('form.pc-form-validate');
});
