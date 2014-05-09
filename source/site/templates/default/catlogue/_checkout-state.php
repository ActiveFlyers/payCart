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
$step->text = '1';
$steps[]= $step;

$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-truck';
$step->text = '2';
$steps[]= $step;

$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-thumbs-up';
$step->text = '3';
$steps[]= $step;

$step  = new stdClass();
$step->index = $counter++;
$step->icon = 'fa-credit-card';
$step->text = '4';
$steps[]= $step;



?>

<div class='pc-checkout-wrapper clearfix'>
	<div class="pc-checkout-state row-fluid clearfix">
		<div class="lead text-center pc-grid-12 ">
			<hr class="clearfix pc-grid-12" />
			<?php foreach($steps as $s):?>
				<?php 
						$isEnabled = $s->index <= $current ; 
						$isCurrent = $s->index == $current ;
						
						$stepStateClass = 'muted';  
						if($isEnabled){
							if($isCurrent){
								$stepStateClass = '';
							}else{
								$stepStateClass = 'text-success';
							}
						}
						
				?>
				<div class="pc-grid-3 <?php echo $stepStateClass; ?>">
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
	</div>
</div>

<?php

