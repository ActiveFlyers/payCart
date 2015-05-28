<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		Rimjhim Jain
*/

/**
 * List of Populated Variables
 * $heading = COM_PAYCART_ADMIN_BLANK_CART
 * $msg 	= COM_PAYCART_ADMIN_BLANK_CART_MSG
 * $model	= Instance of PaycartModelCart
 * $filters = Array of availble filters
 * $uri		= Current URL (SITE_NAME/administrator/index.php?option=com_paycart&view=cart&task=display)
 * 
 */
defined('_JEXEC') or die();
?>
<div class="pc-cart-wrapper clearfix">
<div class="pc-cart row-fluid">

<!-- CONTENT START -->

	<!-- ADMIN MENU -->
	<div class="span2">
		<?php
				$helper = PaycartFactory::getHelper('adminmenu');			
				echo $helper->render('index.php?option=com_paycart&view=transaction'); 
		?>
	</div>
	
	<div class="span10">

		<form action="<?php echo $uri; ?>" method="post" name="adminForm">
			<?php //echo $this->loadTemplate('filter'); ?>
	
			<div class="row-fluid">
				<div class="center muted">
					<div>
						<h1>&nbsp;</h1>
						<i class="fa fa-money fa-5x"></i>
					</div>
					
					<div>
						<h3><?php echo JText::_('COM_PAYCART_ADMIN_TRANSACTION_GRID_BLANK_MSG');?></h3>
					</div>
				</div>
			</div>
			
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
	</div>
</div>
</div>
<?php 
