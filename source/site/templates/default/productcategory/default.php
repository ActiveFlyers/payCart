<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<?php if(!empty($categories)):?>
	<div class="row-fluid"><h2 class=" span12 page-header"><?php echo JText::_("COM_PAYCART_CATEGORIES");?></h2></div>
	<?php echo $this->loadTemplate('categories', compact('categories'));?>
<?php endif;?>

<?php if(!empty($products)):?>
	<div class="row-fluid"><h2 class=" span12 page-header"><?php echo JText::_("COM_PAYCART_PRODUCTS");?></h2></div>
	<?php echo $this->loadTemplate('products', compact('products'));?>
<?php endif;?> 
<?php 
