<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/

/**
 * List of Populated Variables
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>

<?php
		include_once 'default.js.php'; 
	?>
<div class='pc-checkout-wrapper clearfix'>

<!--	Checkout flow state	-->
	<div class="pc-checkout-state row-fluid clearfix">
		<?php include_once 'step.php';?>
	</div>
	
<!--	Checkout step	-->
	<div class="pc-checkout-step-form row-fluid clearfix">
		<form class="pc-checkout-form" id="pc-checkout-form" name="pc-checkout-form" >

			<!-- Change on ajax request 			-->
			<div class="pc-checkout-step-html row-fluid clearfix"> 
				<?php
					echo $this->loadTemplate($step_ready); 
				?>
			</div>
			
			<input type="hidden"	name='task' value='process'		/>
			<input type="hidden"	name='view' value='checkout'	/>			
		</form>
	</div>
</div>


