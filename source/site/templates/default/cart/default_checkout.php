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

//load chosen when client platform is not mobile
if (!$is_platform_mobile) {
	JHtml::_('formbehavior.chosen', 'select.pc-chozen');
}
?>

<?php echo $this->loadTemplate('js'); ?>

<!-- @PCTODO :: move style to proper location -->
<style>
	
	.paycart .pc-checkout-loader{
		background-color: rgba(255, 255, 255, 0.9);
	
	}
	.paycart .pc-checkout-loader i.fa-spinner{
		position: fixed;
		top: 50%;
		left: 50%;
	}
	
	.paycart .position-relative 
	{
		position: relative;
	}
	
	.paycart .pc-checkout-wrapper label.required:after
	{
	    content: '*';
	}
	
	.paycart .pc-checkout-cursor-pointer
	{	
		cursor: pointer;
	}
	
</style>


<div class='pc-checkout-wrapper clearfix position-relative'>	
	<div>
	<!--	Checkout flow state	-->
		<div class="pc-checkout-state row-fluid clearfix">
			<?php //include_once 'step.php';?>
		</div>
		
	<!--	Checkout step	-->
		<div class="pc-checkout-step-form row-fluid clearfix">		
			<form class="pc-checkout-form pc-form-validate" id="pc-checkout-form"   name="pc-checkout-form" method="post" action="index.php">
	
				<!-- Change on ajax request 			-->
				<div id="pc-checkout-step-html" class="pc-checkout-step-html row-fluid clearfix"> 
					
				</div>
				
			</form>
			
		</div>
	</div>	
	
	<div class="modal-backdrop pc-checkout-loader hide" id="pc-checkout-loader">
    	<i class="fa fa-spinner fa-3x fa-spin"></i>
	</div>			
</div>

<script>
(function($){
	$(document).ready(function(){
			paycart.cart.login.get();
	});
	
})(paycart.jQuery);
</script>