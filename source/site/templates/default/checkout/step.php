<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

$counter = 1;
?>

		<div class="lead text-center pc-grid-12 ">
			<hr class="clearfix pc-grid-12" />
			<?php foreach($available_steps as $step ):?>
			
				<div class="pc-grid-3 pc-checkout-step muted <?php echo $step->class; ?>">
					<p class="fa-stack  ">
				    	<i class="fa fa-circle fa-stack-2x"></i>
				    	<i class="fa <?php echo $step->icon; ?> fa-stack-1x fa-inverse"></i>
				    </p>
				    
			    	<p class="">
			    		<?php echo $counter++; ?>
			    		<span class='hidden-phone'> <?php echo JText::_($step->title)?></span>
			    		
			    		<?php if(0&& $isCurrent):?>
			    			<i class="fa fa-caret-down"></i>
		    			<?php endif;?>
			    	</p>
		    	</div>
			<?php endforeach;?>
			<hr class="clearfix pc-grid-12" />
		</div>
		
		<script>

							
			(function($) {
	
			})(paycart.jQuery);
			
		</script>

<?php

