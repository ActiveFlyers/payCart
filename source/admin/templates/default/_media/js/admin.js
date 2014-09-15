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



/** ===================================================
 *  Javascript For Paycart Entity
 *  ===================================================
 */
paycart.admin.product =
	{
		category :
		{	
			add : function(categoryName, CallbackOnSuccess, CallbackOnError)
			{
				var link  = 'index.php?option=com_paycart&task=create&view=productcategory';
				var data  = {'category_name': categoryName };
				paycart.ajax.go(link,data, CallbackOnSuccess, CallbackOnError);
			}
		},
		alias  :
		{
			add : function(title, id,  CallbackOnSuccess, CallbackOnError)
			{
				var link  = 'index.php?option=com_paycart&task=getalias&view=product';
				var data  = {'title': title , 'product_id': id };
				paycart.ajax.go(link,data, CallbackOnSuccess, CallbackOnError);
			}
		},
		
		deleteImage : function(imageId)
		{
			var link  = 'index.php?option=com_paycart&task=deleteImage&view=product';
			var productId = $('#paycart_form_product_id').val();
			var data  = {'image_id': imageId, 'product_id':productId};
			paycart.ajax.go(link,data);
		}
	};

paycart.admin.attribute = {
		removeOption : function(type,counter){	
	        var optionId = $('#productattribute_option_id_'+counter).val();
			var url = 'index.php?option=com_paycart&view=productattribute&task=removeOption&attributeType='+type+'&counter='+counter+'&optionId='+optionId;
			paycart.ajax.go(url);
		},
			
		addOption : function(type){
			var totalRows = $('*[id^="option_row_"]').length
			var url = 'index.php?option=com_paycart&view=productattribute&task=addOption&attributeType='+type+'&totalRows='+totalRows;
			paycart.ajax.go(url);
		},
		
		getTypeConfig : function(type, id){
			var url  = 'index.php?option=com_paycart&task=getTypeConfig&view=productattribute&attributeType='+type+'&productattribute_id='+id;
			paycart.ajax.go(url);
		},
		
		addColorScript : function() {
			$('.wheel-color').each( function() {
				$(this).minicolors({
					control: $(this).attr('data-control') || 'hue',
					defaultValue: $(this).attr('data-defaultValue') || '',
					inline: $(this).attr('data-inline') === 'true',
					letterCase: $(this).attr('data-letterCase') || 'lowercase',
					opacity: $(this).attr('data-opacity'),
					position: $(this).attr('data-position') || 'bottom left',
					change: function(hex, opacity) {
						var log;
						try {
							log = hex ? hex : 'transparent';
							if( opacity ) log += ', ' + opacity;
							console.log(log);
						} catch(e) {}
					},
					theme: 'default'
				});
                
            });
		}
	};



// @PCTODO : Move it proper location so we can utilize it for front end
paycart.admin.buyer =
{
	shipping_address : 
	{
		update	:	function(shipping_address_id, buyer_id)
		{
			var json_object		= {};
			var paycart_form 	= {};
			
			// get all paycart_form data
			paycart_form['shipping_address_id'] 	= shipping_address_id;
			paycart_form['buyer_id']				= buyer_id ;
		
			// prepare paycart_form data
			json_object['paycart_form'] = paycart_form;

			var link  = 'index.php?option=com_paycart&view=buyer&task=save';

			paycart.ajax.go(link, json_object);
		},
		
		//callback function on shipping address successfully update
		success : function()
		{
			alert('Successfully update!!');
		},
		
		//callback function on shipping address updation fail
		error : function()
		{
			alert('Oop... :( updation fail!!');
		}
	},

	billing_address : 
	{
		update	:	function(billing_address_id, buyer_id)
		{
			var json_object		= {};
			var paycart_form 	= {};

			// get all paycart_form data
			paycart_form['billing_address_id'] 	= billing_address_id;
			paycart_form['buyer_id']			= buyer_id ;
		
			// prepare paycart_form data
			json_object['paycart_form'] = paycart_form;
			
			var link  = 'index.php?option=com_paycart&view=buyer&task=save';
	
			paycart.ajax.go(link, json_object);
		}, 
		
		//callback function on billing address successfully update
		success : function()
		{
			alert('Successfully update!!');
		},
		
		//callback function on billing address updation fail
		error : function()
		{
			alert('Oop... :( updation fail!!');
		}
	}
	
};


paycart.admin.buyeraddress =
{
	//open modal window to create new buyer-address
	window : function(buyer_id)
	{
		// domObject, use for element id which will be changed.  
		var link  = 'index.php?option=com_paycart&task=edit&view=buyeraddress&buyer_id='+buyer_id;
		paycart.url.modal(link, null);
	},
	
	add : 
	{
		go : function()
		{
			//Validation Checking
			if($("#paycart_buyeraddress_form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
				// Our validation work on submit call therefore first we will ensure that form is not properly fill 
				// then we will call submit method. So proper msg display and focus on required element. 
				$("#paycart_buyeraddress_form").submit();
				return false;
			}
			
			var link  = 'index.php?option=com_paycart&view=buyeraddress';
			// get all form data for post	
			var postData = $("#paycart_buyeraddress_form").serializeArray();
	
			// Override task value to ajax task
			postData.push({'name':'task','value':'add'});
	
			paycart.ajax.go(link, postData);
		},
		
		// data is json string		
		success : function(data)
		{
			var response = $.parseJSON(data);
			alert(response.message);
			// @PCTODO::
			// 1#.Close Model window
			rb.ui.dialog.autoclose(1);
			// 2#.Fetch html of new created buyeraddress
			// 3# append into buyeraddreess template
			// 4#.Good Job
		},
		
		// data is json string
		error : function(data)
		{
			var response = $.parseJSON(data);
			alert(response.message);
			// @PCTODO::
			// 1#.Close Model window and handle error
			// 2#.Good Job
			
			//close modal window
			rb.ui.dialog.autoclose(1);
		}
		
	},
	
	//open modal window and open existing buyer-address
	edit : function(buyeraddress_id)
	{
		// domObject, use for element id which will be changed.  
		var link  = 'index.php?option=com_paycart&task=edit&view=buyeraddress&buyeraddress_id='+buyeraddress_id;
		paycart.url.modal(link, null);
	}
},

paycart.admin.state =
{
	//open modal window to create new state or edit in existing state
	window : function(country_id, state_id)
	{
		var link  = 'index.php?option=com_paycart&task=edit&view=state&country_id='+country_id+'&state_id='+state_id;
		paycart.url.modal(link, null);
	},
	
	add : 
	{
		go : function()
		{
			//Validation Checking
			if($("#paycart_state_form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
				// Our validation work on submit call therefore first we will ensure that form is not properly fill 
				// then we will call submit method. So proper msg display and focus on required element. 
				$("#paycart_state_form").submit();
				return false;
			}
			
			var link  = 'index.php?option=com_paycart&view=state';
			
			// get all form data for post	
			var postData = $("#paycart_state_form").serializeArray();
	
			// Override task value to ajax task
			postData.push({'name':'task','value':'save'});
	
			paycart.ajax.go(link, postData);
		},
		
		// data is json string
		error : function(data)
		{
			var response = $.parseJSON(data);
			alert(response.message);
			// @PCTODO::
			// 1#.Close Model window and handle error
			// 2#.Good Job
			
			//close modal window
			rb.ui.dialog.autoclose(1);
		}
		
	},
	
	remove : 
	{
		go : function(state_id)
		{
			var link  = 'index.php?option=com_paycart&view=state&task=delete&state_id='+state_id;
			paycart.ajax.go(link);
		},
		
		// data is json string		
		success : function(data)
		{
			var response = $.parseJSON(data);
			alert(response.message);
			// @PCTODO::
			// 1#.Close Model window
			rb.ui.dialog.autoclose(1);
			// 2#.Fetch html of new created state
			// 3# append into state template
			// 4#.Good Job
		},
		
		// data is json string
		error : function(data)
		{
			var response = $.parseJSON(data);
			alert(response.message);
			// @PCTODO::
			// 1#.Close Model window and handle error
			// 2#.Good Job
			
			//close modal window
			rb.ui.dialog.autoclose(1);
		}
		
	}
},    
     
/**
 * Payment Gateway specific Javascript
 */
paycart.admin.paymentgateway = {};
		
paycart.admin.paymentgateway.getProcessorConfigHtml = function()
{
        var data	=	{
                                                'processor_type' 	:	$('#paycart_form_processor_type').val(),
                                                'processor_id'		:	$('#paycart_form_paymentgateway_id').val()
                                        };
        var url		=	'index.php?option=com_paycart&view=paymentgateway&task=getConfigHtml';

        paycart.ajax.go(url, data);
};

paycart.admin.paymentgateway.setProcessorConfigHtml = function(response_data)
{
        $('#pc-paymentgateway-processorconfig').html(response_data['html']);

};

paycart.form = 
	{
		validation : 
			{	// Proper Binding element for JQuery Bootstrape Validation
				init :	function(selector)
				{
					// form validation required 
					$(selector).find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
				}
			}
	};

paycart.radio = {
		init : function(){
			 //needed some fix to show radio button properly in front-end
		    // Turn radios into btn-group
			$('.radio.btn-group label').addClass('btn');
			
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
		},
		
		applyBtnClass : function(btn){
			  var label = $(btn);
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
		}
};

/*--------------------------------------------------------------
  on Document ready 
--------------------------------------------------------------*/
$(document).ready(function(){
	  /*
	   * catch click event
	   */
	  $(document).on('click','.btn-group label:not(.active)', function(){
		  paycart.radio.applyBtnClass(this);
	  });
	  
	  paycart.radio.init();
});
//ENDING :
//Scoping code for easy and non-conflicting access to $.
//Should be last line, write code above this line.
})(paycart.jQuery);