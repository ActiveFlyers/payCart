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

$configure_steps	= PaycartFactory::getHelper('checkout')->getSequence();
$steps 				=  Array();


$step  = new stdClass();
$step->icon		= 'fa-user';
$step->class	= 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_LOGIN;
$steps[Paycart::CHECKOUT_STEP_LOGIN]= $step;

$step  = new stdClass();
$step->icon = 'fa-truck';
$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_ADDRESS;
$steps[Paycart::CHECKOUT_STEP_ADDRESS]= $step;

$step  = new stdClass();
$step->icon = 'fa-thumbs-up';
$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_CONFIRM;
$steps[Paycart::CHECKOUT_STEP_CONFIRM]= $step;

$step  = new stdClass();
$step->icon = 'fa-credit-card';
$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_PAYMENT;
$steps[Paycart::CHECKOUT_STEP_PAYMENT]= $step;

$counter = 1;
?>

		<div class="lead text-center pc-grid-12 ">
			<hr class="clearfix pc-grid-12" />
			<?php foreach($configure_steps as $step_name => $step_next ):?>
			
				<div class="pc-grid-3 pc-checkout-step muted <?php echo $steps[$step_name]->class; ?>">
					<p class="fa-stack  ">
				    	<i class="fa fa-circle fa-stack-2x"></i>
				    	<i class="fa <?php echo $steps[$step_name]->icon; ?> fa-stack-1x fa-inverse"></i>
				    </p>
				    
			    	<p class="">
			    		<?php echo $counter++; ?>
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

				paycart.checkout.step.change('<?php echo $step_ready; ?>');
	
			})(paycart.jQuery);
			
		</script>

<?php

