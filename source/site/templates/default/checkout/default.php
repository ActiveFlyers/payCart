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
	
	<script>


		(function($){

			paycart.checkout.submit =
			{
				do : function()
				{
					// get all form data for post	
					var postData 	= $("#pc-checkout-form").serializeArray();
					var link  		= 'index.php?option=com_paycart';

					console.log('paycart.checkout.submit.do');

					//@PCTODO :: Display Spinner{ request is processing }  


					paycart.ajax.go(link, postData);

					return false;
 					
				},

				/**
				 * data is json object 
				 */
				success: function(data)
				{
					console.log('paycart.checkout.submit.success : start');

					// replace string
					$(".pc-checkout-step-html").html(data.html);

					console.log('paycart.checkout.submit.success : end');
				},

				/**
				 * data is json object 
				 */
				error :function(data)
				{
					console.log('paycart.checkout.submit.error :: ' + data);
				}
			};
			
		})(paycart.jQuery);
	</script>
	
	
</div>


