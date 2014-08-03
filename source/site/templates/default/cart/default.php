<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 	rimjhim
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

include_once 'default.js.php';
/**
 * Available variables 
 * 
 * @param $products => array of product particulars
 * @param $cart => object of PaycartCart 
 */
?>

<form>
<div class='pc-cart-wrapper clearfix'>
	 <div class="pc-cart-products row-fluid">
		<?php echo $this->loadTemplate('products');?>
	 </div>	 
</div>
</form>
<?php 
