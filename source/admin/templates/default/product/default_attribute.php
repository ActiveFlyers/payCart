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

?>

	<script>
		paycart.jQuery(document).ready(function($) {

			$('.paycart_attribute_add_window').click( function()
			{
				paycart.admin.product.attribute.window();
				
			});
		});
	</script>
	
	<div class="row-fluid">
		<div class="span8">
			<div class="span3">&nbsp;</div>
			
			<div class="span6 pc-blank-heading">
				<p class="muted"><?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_BLANK'); ?></p>
				<div class="center">
					<a href="#" class="btn btn-success paycart_attribute_add_window"><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_ADD_NEW');?></a>
					<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-question-sign "></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_SUPPORT_LINK');?></a>
					<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-book"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_DOCUMENTATION_LINK');?></a>
				</div>
			</div>
		</div>
		
		<div class="span4">
			<a href="#" class="btn btn-success paycart_attribute_add_window"><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_ADD_NEW');?></a>
			<a href="#" class="btn btn-success paycart_attribute_list_window"><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_ATTRIBUTE_LIST');?></a>
		</div>
		
	</div>

	
