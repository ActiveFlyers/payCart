<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

?>
	
<script>
	(function($) {
            
            
            
           /**
            * Notification specific Javascript
            */
            paycart.admin.notification = {};

            paycart.admin.notification.window = 
                    function(notification_id)
                        {
                            var link  = 'index.php?option=com_paycart&task=edit&view=notification&id='+notification_id;
                            paycart.url.modal(link, null, '800px');
                        };

            paycart.admin.notification.update = 
                {
                    go  :function(notification_id)
                    {
                        //Validation Checking
                        if($("#paycart_notification_form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
                            // Our validation work on submit call therefore first we will ensure that form is not properly fill 
                                            // then we will call submit method. So proper msg display and focus on required element. 
                                            $("#paycart_notification_form").submit();
                                            return false;
                        }

                        var link  = 'index.php?option=com_paycart&view=notification';

                        // get all form data for post	
                        var postData = $("#paycart_notification_form").serializeArray();

                        // Override task value to ajax task
                        postData.push({'name':'task','value':'save'});

                        paycart.ajax.go(link, postData, paycart.admin.notification.update.success, paycart.admin.notification.update.error);

                    },

                    success : function(data)
                    {
                        // 1#.Close Model window
                        rb.ui.dialog.autoclose(1);
                    },

                    error : function(data)
                    {
                        var response = $.parseJSON(data);
                        alert(response.message);
                    }


                }
    
    
    
    
                
                //tokenInsertAtCaret
		paycart.token =  (function()
                    {
                        var current_cursor =  
                            {   pointer_at : 0 ,    // Pointer location
                                selector : ''       // Input slector
                            };
                        
                        var callable_methods = {}; // all public methods
                        
                        // set current cursor details
                        callable_methods.set_cursor_position = 
                                function(active_element)
                                {
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
                                        var sel = document.selection.createRange();
                                        var selLen = document.selection.createRange().text.length;
                                        sel.moveStart('character', -input.value.length);
    //                                    $("#sel").html(sel);
    //                                    $("#selLen").html(selLen);
                                        current_cursor.pointer_at = sel.text.length - selLen;
                                    }
                                    
                                };
                       
                        
                        callable_methods.insert_at_cursor = 
                            function(token) 
                            { 
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
