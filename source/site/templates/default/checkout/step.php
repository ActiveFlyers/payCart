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

$steps =  Array();


$counter = 1;


$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-user';
$step->class = 'pc-checkout-step-login';
$steps[]= $step;

$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-truck';
$step->class = 'pc-checkout-step-address';
$steps[]= $step;

$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-thumbs-up';
$step->class = 'pc-checkout-step-confirm';
$steps[]= $step;

$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-credit-card';
$step->class = 'pc-checkout-step-payment';
$steps[]= $step;

?>

		<div class="lead text-center pc-grid-12 ">
			<hr class="clearfix pc-grid-12" />
			<?php foreach($steps as $s):?>
			
				<div class="pc-grid-3 pc-checkout-step muted <?php echo $s->class; ?>">
					<p class="fa-stack  ">
				    	<i class="fa fa-circle fa-stack-2x"></i>
				    	<i class="fa <?php echo $s->icon; ?> fa-stack-1x fa-inverse"></i>
				    </p>
				    
			    	<p class="">
			    		<?php echo $s->index; ?>
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

				paycart.checkout.step =
				{
					change : function(step_ready)
					{
						// @PCTODO: do not use hard-coding
						// availble steps 
						var steps 		= ['login', 'address', 'confirm', 'payment'];
						var class_name	= '';
		
						// set incomplet class to all (remove previous class and add default classes);
						paycart.jQuery('.pc-checkout-step').removeClass('text-success').addClass('muted');
						
						for (i=0; i<steps.length; i++) {
		
							class_name = '.pc-checkout-step-'+steps[i];
	
							paycart.jQuery(class_name).removeClass('muted');
							
							if (step_ready == steps[i]){
								// active mark it
								//@PCTODO:: Acive mark
								paycart.jQuery(class_name).addClass('text-success');
								break; 
							}
		
							//Previous step mark completed
							paycart.jQuery(class_name).addClass('text-success');
						}
					}
						
				};
				

				paycart.checkout.step.change('<?php echo $step_ready; ?>');
	
			})(paycart.jQuery);
			
		</script>

<?php

