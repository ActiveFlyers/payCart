<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

//@PCTODO: mention all populated variables

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>

<form action="<?php echo $uri; ?>" method="post" name="paycart_buyeraddress_form" id="paycart_buyeraddress_form" class="rb-validate-form" enctype="multipart/form-data">


	<fieldset>
		
		<?php
				//@FIXME : use constant for layout path
				$layout = new JLayoutFile('paycart_edit_buyeraddress', JPATH_ROOT.'/components/com_paycart/templates/default/layouts');
				echo $layout->render($display_data); 
		?>
		
		
	</fieldset>
	
	<!-- @FIXME:: validate form and submit it	-->
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />
	
	<!-- JavaScript -->
	<script>

		(function($)
				{
					var callBackOnSuccess = function(data)
						{
							alert('//PCTODO: GOOD!! Buyeraddress successfully save. Now you need to fetch buyeraddress html and append into buyeraddreess template ');
							// @PCTODO::
							// 1#.Close Model window
							// 2#.Fetch html of new created buyeraddress
							// 3# append into buyeraddreess template
							// 4#.Good Job

							//close modal window
							rb.ui.dialog.autoclose(1)
						};


					var callBackOnError = function(data)
						{
							alert('//PCTODO: Oops!! Buyeraddress fail to save. :( Please check ajax response data');
							// @PCTODO::
							// 1#.Close Model window and handle error
							// 2#.Good Job
							
							//close modal window
							rb.ui.dialog.autoclose(1)
						};
					
					$(document).ready(function($) {

						// form validation required 
						$("#paycart_buyeraddress_form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
						
						$("#paycart_buyeraddress_add").click(function() {
							// IMP:: Override submit function otherwise Validation will not work 
							$("#paycart_buyeraddress_form").submit();
						});
						
						$("#paycart_buyeraddress_form").submit(function(){
							//Validation Checking
							if($("#paycart_buyeraddress_form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
								return false;
							}
							
							// get all form data for post	
							var postData = $("#paycart_buyeraddress_form").serializeArray();

							// Override task value to ajax task
							postData.push({'name':'task','value':'add	'});
							paycart.admin.buyeraddress.add(postData, callBackOnSuccess, callBackOnError);

							// Must be return false otherwise it will redirect
							return false;
						});
						
					});
				})(paycart.jQuery);

	</script>
</form>




