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
* Notification specific Javascript
*/
paycart.admin.notification = {};
paycart.admin.notification.window = function(notification_id){
		var link  = 'index.php?option=com_paycart&task=edit&view=notification&id='+notification_id+'&lang_code='+pc_current_language;
		paycart.url.modal(link, null, '800px');
	};

paycart.admin.notification.update = {}; 
paycart.admin.notification.update.go = function(notification_id){
		//Validation Checking
        if(!paycart.formvalidator.isValid(document.id('paycart_notification_form'))){
        	return false;
		}

        var link  = 'index.php?option=com_paycart&view=notification&lang_code='+pc_current_language;

        // get all form data for post	
        var postData = $("#paycart_notification_form").serializeArray();
        // Override task value to ajax task
        postData.push({'name':'task','value':'save'});
        paycart.ajax.go(link, postData, paycart.admin.notification.update.success, paycart.admin.notification.update.error);
	};

paycart.admin.notification.update.success = function(data){
		// 1#.Close Model window
        rb.ui.dialog.autoclose(1);
	};

paycart.admin.notification.update.error = function(data){
		var response = $.parseJSON(data);
        alert(response.message);
	};
    
 
                
//tokenInsertAtCaret
paycart.token =  (function(){
		var current_cursor =
						 {   pointer_at : 0 ,    // Pointer location
                             selector : ''       // Input slector
                         };
                        
		var callable_methods = {}; // all public methods
                        
        // set current cursor details
        callable_methods.set_cursor_position = function(active_element){
            var input = active_element.get(0);
                                    
            //console.log({'input is' : input});
			if (!input) return; // No (input) element found

            // set selector
            current_cursor.selector = input;

            //set pointer
            if ('selectionStart' in input) {
            	// Standard-compliant browsers
                current_cursor.pointer_at = input.selectionStart;
            } else if (document.selection) {
	             //@PCTODO :: Testing required
	             // IE
	             input.focus();
	             
	             var selLen = document.selection.createRange().text.length;
	             sel.moveStart('character', -input.value.length);
	    //       $("#sel").html(sel);
	    //       $("#selLen").html(selLen);
	             current_cursor.pointer_at = sel.text.length - selLen;
             }
		};
                       
                        
		callable_methods.insert_at_cursor = function(token){ 
			var input = $(current_cursor.selector),     // Element selector
            start = current_cursor.pointer_at,      // where token will be inserted 
            val = input.val();                      // get input selector value
                                    
            // write new string with token replacement
			input.val(val.substring(0, start) + token + val.substring(start, val.length));
                                
            // reset cursor pointer
            current_cursor.pointer_at = start + token.length;
                                
            //focus element
            input.focus();
		} ;
                      
        return callable_methods;                            
	})();
})(paycart.jQuery);
	
</script>
