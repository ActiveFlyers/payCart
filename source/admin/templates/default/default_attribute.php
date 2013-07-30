<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		manish@readybytes.in
* @author 		Manish Trivedi
*/

/**
 * List of Populated Variables
 * $heading = COM_PAYCART_ADMIN_BLANK_PRODUCT
 * $msg 	= COM_PAYCART_ADMIN_BLANK_PRODUCT_MSG
 * $model	= Instance of PaycartModelProduct
 * $filters = Array of availble filters
 * $uri		= Current URL (SITE_NAME/administrator/index.php?option=com_paycart&view=product&task=display)
 * 
 */
defined('_JEXEC') or die();

//if (isset($attributes) || !empty($attributes))  {
//	echo $this->loadtemplate('addattribute');
//}
?>

	<script>
		paycart.jQuery(document).ready(function($){

			<!-- Callback function when Alias successfully generated					-->
			var callbackOnSuccess = function(data)
			{	// Add alias to element
				alert('On call success');
			};
			
			<!-- Callback function when error occur during category adding operation	-->
			var callbackOnError = function ()
			{
				//@PCTODO :: Proper Error-handling 
				alert('Error');
			};
		
			$('.paycart_attribute_add').click( function()
			{
				alert('M here');

//				paycart.attribute.add();
				
			});
		});
	</script>
	<div class="row-fluid">
		<div class="span12">
			<p class="center"><?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_BLANK'); ?></p>
		</div>
		
		<div class="center">
			<a href="#" class="btn btn-success paycart_attribute_add"><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_ADD_NEW');?></a>
		</div>
	</div>
