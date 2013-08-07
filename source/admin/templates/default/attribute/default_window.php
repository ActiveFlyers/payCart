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
 * 
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
	<div id="rbWindowTitle">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel"><?php echo Rb_Text::_('COM_PAYCART_ADD_NEW_ATTRIBUTE'); ?></h3>
		</div>
	</div>
	
	<div class="modal-body" id="rbWindowBody">
		<div class="row-fluid">
		
			<div class="span8" style="border-right:1px solid">
				<div >
					<h3>New Attribute container</h3> 
				</div>
			</div>
				
			<div class="span3">
					Available Attribute
			</div>
		</div>
	</div>
	<div id="rbWindowFooter">
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  			<button class="btn btn-primary">Save changes</button>
		</div>
	</div>

<?php 