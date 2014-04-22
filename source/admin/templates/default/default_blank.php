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
 * List of Populate Variables
 * $heading = COM_PAYCART_ADMIN_BLANK_PRODUCT
 * $msg 	= COM_PAYCART_ADMIN_BLANK_PRODUCT_MSG
 * $model	= Instance of PaycartModelProduct
 * $filters = Array of availble filters
 * $uri		= Current URL (SITE_NAME/administrator/index.php?option=com_paycart&view=product&task=display)
 * 
 */

defined('_JEXEC') or die();

?>
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<p class="lead center"><?php echo $heading; ?></p>
			<p class="center"><?php echo $msg; ?></p>
		</div>
		
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<?php 