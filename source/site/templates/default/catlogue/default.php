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

$check = JRequest::getCmd('check');

switch($check)
{
	case 'product':
		include __DIR__.'/product.php';
		break;
		
	default:
		include __DIR__.'/categories.php';
		include __DIR__.'/products.php';
		break;
}