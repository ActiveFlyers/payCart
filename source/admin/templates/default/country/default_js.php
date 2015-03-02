<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
<script>
(function($){
paycart.admin.state = {};

//open modal window to create new state or edit in existing state
paycart.admin.state.window = function(country_id, state_id){
		var link  = 'index.php?option=com_paycart&task=edit&view=state&country_id='+country_id+'&state_id='+state_id+'&lang_code='+pc_current_language;
		paycart.url.modal(link, null);
	};
	
paycart.admin.state.add = {}; 
paycart.admin.state.add.go = function(){
		//Validation Checking
		if(!paycart.formvalidator.isValid(document.getElementById('paycart_state_form'))){
			return false;
		}
		
		var link  = 'index.php?option=com_paycart&view=state&lang_code='+pc_current_language;
		
		// get all form data for post	
		var postData = $("#paycart_state_form").serializeArray();

		// Override task value to ajax task
		postData.push({'name':'task','value':'save'});

		paycart.ajax.go(link, postData);
	};
		
// data is json string
paycart.admin.state.add.error = function(data){
		var response = $.parseJSON(data);
		alert(response.message);
		// @PCTODO::
		// 1#.Close Model window and handle error
		// 2#.Good Job
		
		//close modal window
		rb.ui.dialog.autoclose(1);
	};
	
paycart.admin.state.remove = {}; 
paycart.admin.state.remove.go = function(state_id){
		var link  = 'index.php?option=com_paycart&view=state&task=delete&state_id='+state_id;
		paycart.ajax.go(link);
	};
		
// data is json string		
paycart.admin.state.remove.success = function(data){
		var response = $.parseJSON(data);
		alert(response.message);
		// @PCTODO::
		// 1#.Close Model window
		rb.ui.dialog.autoclose(1);
		// 2#.Fetch html of new created state
		// 3# append into state template
		// 4#.Good Job
	};

// data is json string
paycart.admin.state.remove.error = function(data){
		var response = $.parseJSON(data);
		alert(response.message);
		// @PCTODO::
		// 1#.Close Model window and handle error
		// 2#.Good Job
		
		//close modal window
		rb.ui.dialog.autoclose(1);
	};
})(paycart.jQuery);

</script>
<?php 