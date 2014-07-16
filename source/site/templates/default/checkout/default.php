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

//	$content = "
//				(function($) {
//					//$('#pc-checkout-loader').hide();
//					$( document ).ajaxStart(function() {
//						  $('#pc-checkout-loader').show();
//						}).ajaxStop(function() {
//						  $('#pc-checkout-loader').hide();
//						});
//				})(paycart.jQuery);
//				";
	//.ajaxStart() isn't registered until after the ajax request.
	// so add it on script declaration part
	//PaycartFactory::getDocument()->addScriptDeclaration($content);
?>

<?php
		include_once 'default.js.php'; 
?>


<!-- @PCTODO :: move style to proper location -->
<style>
	.paycart .pc-checkout-loader{
		position: absolute;
		top: 0;
		padding : 50%;
		background-color: rgba(255, 255, 255, 0.9);
	}
	
	.paycart .position-relative 
	{
		position: relative;
	}
	
	.paycart .pc-checkout-wrapper label.required:after
	{
	    content: '*';
	}
</style>


<div class='pc-checkout-wrapper clearfix position-relative'>
	
	<div>
	<!--	Checkout flow state	-->
		<div class="pc-checkout-state row-fluid clearfix">
			<?php include_once 'step.php';?>
		</div>
		
	<!--	Checkout step	-->
		<div class="pc-checkout-step-form row-fluid clearfix">
		
			<form class="pc-checkout-form rb-validate-form" id="pc-checkout-form"   name="pc-checkout-form" method="post" action="index.php">
	
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
	
	<div class="pc-checkout-loader hide" id="pc-checkout-loader">
    	<i class="fa fa-spinner fa-3x fa-spin"></i>
	</div>			
</div>

