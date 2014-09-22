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
 	 	 	if (handlers[handler].exec($el.val()) !== true) {
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
 	 	setHandler('username', function(value) {
 	 	 	regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
 	 	 	return !regex.test(value);
 	 	});
 	 	setHandler('password', function(value) {
 	 	 	regex = /^\S[\S ]{2,98}\S$/;
 	 	 	return regex.test(value);
 	 	});
 	 	setHandler('numeric', function(value) {
 	 	 	regex = /^(\d|-)?(\d|,)*\.?\d*$/;
 	 	 	return regex.test(value);
 	 	});
 	 	setHandler('email', function(value) {
 	 	 	regex = /^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
 	 	 	return regex.test(value);
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
