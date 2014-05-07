<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<?php

$check = JRequest::getCmd('check', null);


if($check !== null){
	// catlogue
	if($check == 'catlogue'){	
		include __DIR__.'/categories.php';
		include __DIR__.'/products.php';
	}else{
		include __DIR__."/$check.php";
	}
}

echo '<div class=" clearfix span12" style="height:250px;">&nbsp;</div>';

echo "<div class='well'> <h3 class='page-header'>Paycart Test Menu</h3><ul>";
// show links to test
$links = array('product', 'catlogue','checkout-cart', 'checkout-login','checkout-shipping','checkout-confirm','checkout-payment');
foreach($links as $link){
	echo "<li> <a href='index.php?option=com_paycart&view=catlogue&check=$link'> $link </a> </li>";
}
echo "</ul></div>";