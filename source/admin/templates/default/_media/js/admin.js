/**
* @copyright	Copyright (C) 2009-2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PAYCART
* @contact 		team@readybytes.in
*/

//define paycart, if not defined.
if (typeof(paycart)=='undefined'){
	var paycart = {}
}

// all admin function should be in admin scope 
if(typeof(paycart.admin)=='undefined'){
	paycart.admin = {};
}

//all admin function should be in admin scope 
if(typeof(Joomla)=='undefined'){
	Joomla = {};
}


(function($){
// START : 	
// Scoping code for easy and non-conflicting access to $.
// Should be first line, write code below this line.	
	
	
/*--------------------------------------------------------------
paycart.admin.grid
	submit
	filters
--------------------------------------------------------------*/
paycart.admin.grid = {
		
		//default submit function
		submit : function( view, action, validActions){
			
			// try views function if exist
			var funcName = view+'_'+ action ; 
			if(this[funcName] instanceof Function) {
				if(this[funcName].apply(this) == false)
					return false;
			}
			
			// then lastly submit form
			//submitform( action );
			if (action) {
		        document.adminForm.task.value=action;
		    }
			
			// validate actions
			//XITODO : send values as key of array , saving a loop
			validActions = eval(validActions);
			var isValidAction = false;
			for(var i=0; i < validActions.length ; i++){
				if(validActions[i] == action){
					isValidAction = true;
					break;
				}
			}
			
			if(isValidAction){
				if (!$('#adminForm').find("input,textarea,select").jqBootstrapValidation("hasErrors")) {
					Joomla.submitform(action, document.getElementById('adminForm'));
				}
				else{
					$('#adminForm').submit();
				}
			}else{
				Joomla.submitform(action, document.getElementById('adminForm'));
			}
		},
		
		filters : {
			reset : function(form){
				 // loop through form elements
			    var str = new Array();
                            var i=0;
			    for(i=0; i<form.elements.length; i++)
			    {
			        var string = form.elements[i].name;
			        if (string && string.substring(0,6) == 'filter' && (string!='filter_reset' && string!='filter_submit'))
			        {
			            form.elements[i].value = '';
			        }
			    }
				this.submit(view,null,validActions);
			}
		}
};


paycart.admin.invoice = {
		item : {
			add : function (item_description, quantity, price, total){
						if(total == ''){
							total = 0;
						}
									
						var counter = $('#paycart-invoice-item-add').attr('counter'); 
						var html = $('.paycart-invoice-item:first').html();
						html = html.replace(/##counter##/g, counter);
						html = html.replace(/##item_description##/g, item_description);
						html = html.replace(/##quantity##/g, quantity);
						
						if(!isNaN(parseFloat(price))){
							price = parseFloat(price).toFixed(2);
						}
						html = html.replace(/##price##/g, price);
						
						if(!isNaN(parseFloat(total))){
							total = parseFloat(total).toFixed(2)
						}
						else{
							total = '0.00';
						}
						
						html = html.replace(/##total##/g, total);
						$('<div class="paycart-invoice-item">' + html + '</div>').appendTo('.paycart-invoice-items').show();
						$('#paycart-invoice-item-add').attr('counter', parseInt(counter) + 1);
						
						// apply validation on added item
						$('.paycart-item-quantity, .paycart-item-price, .paycart-item-title').jqBootstrapValidation();						
						
						return false;
			}
		},
			
		calculate_total : function(){
					var subtotal = 0;
					$('.paycart-item-total:visible').each(function(e){
						subtotal = subtotal + parseFloat($(this).val());
					});
					$('#paycart-invoice-subtotal').val(parseFloat(subtotal).toFixed(2));
					
					var discount = parseFloat($('#paycart-invoice-discount').val());
					var tax 	 = parseFloat($('#paycart-invoice-tax').val());
					
					var total = subtotal - discount;
					if(tax > 0){
						total = total + total * tax / 100;
					}
					$('#paycart-invoice-total').val(parseFloat(total).toFixed(2));
		},
	
		on_currency_change : function(currency){
					var currency   = {'event_args' :{'currency' : currency} };
					var url		   = 'index.php?option=com_paycart&view=invoice&task=ajaxchangecurrency';
					paycart.ajax.go(url,currency);
					return false;
		},
		
		on_buyer_change : function(buyer){
				var buyer   = {'event_args' :{'buyer' : buyer} };
				var url 	= 'index.php?option=com_paycart&view=invoice&task=ajaxchangebuyer';
				paycart.ajax.go(url, buyer);
		},
		
		email : {	
			confirm : function(invoice_id){
				var url 	= 'index.php?option=com_paycart&view=invoice&task=email&invoice_id='+invoice_id;
				paycart.url.modal(url);
			},
			
			send : function(invoice_id){
				paycart.ui.dialog.body('<div class="center"><span class="spinner">&nbsp;</span></div>');
				// XITODO : use bootstarp to disable the button click
				$('#paycart-invoice-email-confirm-button').attr('disabled', 'disabled');
				var url 	= 'index.php?option=com_paycart&view=invoice&task=email&confirmed=1&invoice_id='+invoice_id;
				paycart.ajax.go(url);
			}			
		}
};

/*--------------------------------------------------------------
  on Document ready 
--------------------------------------------------------------*/
$(document).ready(function(){
	
});

//ENDING :
//Scoping code for easy and non-conflicting access to $.
//Should be last line, write code above this line.
})(paycart.jQuery);