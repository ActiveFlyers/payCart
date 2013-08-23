<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		Puneet Singhal
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

<form action="<?php echo $uri; ?>" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span3">&nbsp;</div>
		<div class="span6 pc-blank-heading">
			<p class="muted"><?php echo $msg; ?></p>
		</div>
		<div class="span3">&nbsp;</div>
	</div>
	<div class="row-fluid">	
		<div class="center">
			<a href="<?php echo JUri::base().'index.php?option=com_paycart&view=cart&task=new';?>" class="btn btn-success"><i class="icon-shopping-cart icon-white"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_ADD_NEW_CART');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-question-sign "></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_SUPPORT_LINK');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-book"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_DOCUMENTATION_LINK');?></a>
		</div>
	</div>
</form>
<?php 