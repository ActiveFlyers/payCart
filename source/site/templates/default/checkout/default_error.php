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
}

// @TODO:: class set according to message type
$class = 'alert alert-error';

?>	
	<div class="pc-checkout-error-page">
		<div class="<?php echo $class;?>" > 
			<?php  echo $message;		?>
		</div>
	</div>	
	 
	

<?php

