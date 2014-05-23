<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>



<div class='pc-checkout-wrapper clearfix'>
	
	 <div class="pc-checkout-confirm">
		 <h3>	I'm waiting for mockup :) :)	</h3>
	 </div>	 
</div>
<script>
(function($){
			paycart.checkout.confirm = 
			{
				
				
			};

			paycart.checkout.step.change('<?php echo $step_ready; ?>');				
			
		})(paycart.jQuery);
</script>
<?php

