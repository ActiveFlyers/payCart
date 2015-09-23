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
var inFocus = false;
(function($) {
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
                       
                        
		callable_methods.insert_at_cursor = function(tokenElement){ 
			var token = $(tokenElement).data('pc-selector');
			var input = $(current_cursor.selector),     // Element selector
            start = current_cursor.pointer_at,      // where token will be inserted 
            val = input.val();                      // get input selector value

			if(inFocus){
				// write new string with token replacement
				input.val(val.substring(0, start) + token + val.substring(start, val.length));
	                                
	            // reset cursor pointer
	            current_cursor.pointer_at = start + token.length;
	                                
	            //focus element
	            input.focus();
		    }else{
	            //check if not in to cc bcc and subject
	            jInsertEditorText(token,'body');
		    }
		} ;
                      
        return callable_methods;                            
	})();
})(paycart.jQuery);
	
</script>
<?php 