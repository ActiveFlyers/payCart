<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
<div id="pc-cart-products">
 	<div class="row-fluid row-fluid text-center">
 		<h4 class="muted"><?php echo JText::_('COM_PAYCART_CART_EMPTY')?></h4>
 		<div class="row-fluid">
 			<a class="btn btn-lg btn-primary" href="<?php echo JRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>"> <i class="fa fa-chevron-left"></i> &nbsp; <?php echo JText::_("COM_PAYCART_CONTINUE_SHOPPING");?></a>
 		</div>
 	</div>
</div>