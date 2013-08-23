<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		Manish Trivedi
*/

/**
 * 
 * List of Populated Variables
 * $attributeView => Attribute View
 * 
 */
defined('_JEXEC') or die();


?>

<script src="<?php echo Rb_HelperTemplate::mediaURI(dirname(dirname(__FILE__))).'_media/js/template.js'; ?>" ></script>

<script>
	(function($)
		{
			var callBackOnSuccess = function(data)
				{
					alert('//PCTODO: GOOD!! Attribute is created. Now you need to fetch attribute html and append into custom-attribute template ');
					// @PCTODO::
					// 1#.Close Model window
					// 2#.Fetch html of new created attribute
					// 3# append into custom-attribute template
					// 4#.Good Job
				};

			
			$(document).ready(function($) {

				// form validation required 
				$("div#paycart_attribute_form_div form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
				
				$("#paycart_attribute_create").click(function() {
					// IMP:: Override submit function otherwise Validation will not work 
					$("div#paycart_attribute_form_div form").submit();
				});
				
				$("div#paycart_attribute_form_div form").submit(function(){
					//Validation Checking
					if($("div#paycart_attribute_form_div form").find("input,textarea,select").not('.no-validate').jqBootstrapValidation("hasErrors")){
						return false;
					}
					
					// get all form data for post	
					var postData = form.serializeArray();
					// Override task value to ajax task
					postData.push({'name':'task','value':'create'});
					paycart.admin.attribute.add(postData, callBackOnSuccess);
					// Must be return false otherwise it will redirect
					return false;
				});
				
			});
		})(paycart.jQuery);
</script>


<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_ADD_NEW'); ?></h3>
	</div>
</div>

<div class="modal-body" id="rbWindowBody">
	<!--  New_atrribute_creation body		-->
	<div class="" id="paycart_attribute_form_div">
	 
		<?php
			echo $attributeView->loadtemplate('edit');
		?> 
	</div>
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
		<button class="btn btn-primary " id="paycart_attribute_create"> create &amp; Add</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<?php 