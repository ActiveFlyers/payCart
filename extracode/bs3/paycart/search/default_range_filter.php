<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
defined('_JEXEC') or die();

/**
 * List of Populated Variables
 * $displayData : object of stdclass containing data to show applied filters
 */

$appliedPriceRange  = $filters->core->appliedPriceRange;
$appliedWeightRange = $filters->core->appliedWeightRange;
$minPriceRange  	= $filters->core->minPriceRange;
$maxPriceRange  	= $filters->core->maxPriceRange;
$minWeightRange 	= $filters->core->minWeightRange;
$maxWeightRange 	= $filters->core->maxWeightRange;
?>
	
<?php if($minPriceRange != $maxPriceRange):?>
	<?php $sliderValue = (!empty($appliedPriceRange))?key($appliedPriceRange):$minPriceRange.','.$maxPriceRange;?>
	<?php $priceValue  = (!empty($appliedPriceRange))?key($appliedPriceRange):'';?>
	
	<div class="accordion" id="accordion-id-price">
	 	<div class="accordion-group">
	 		<div class="accordion-heading">
	 			<a class="accordion-toggle pc-cursor-pointer" data-toggle="collapse" data-parent="#accordion-id-price" data-target=".accordion-body-id-price">		 				
	 				<h3><i class="fa fa-angle-down"></i><span>&nbsp;<?php echo JText::_("COM_PAYCART_PRICE").' ( '.$currency.' )'?></span></h3>
	 			</a>		
	 		</div>
	 		<!-- use class "in" for keeping it open -->
	 		 <div class="accordion-body collapse in accordion-body-id-price">
	 		 	<div class="accordion-inner">
	 		 	   <div class="clearfix hidden-xs">
		 		 	   	<span class="pull-left"><strong><?php echo $minPriceRange?></strong></span>
		 		 	   	<span class="pull-right"><strong><?php echo $maxPriceRange;?> </strong></span>
	 		 	   </div>
	 		 	
				   <div class="visible-xs-block">
				   		<?php $array = explode(',', $sliderValue);?>
				   		<input type="number" class="input-mini" name="filterPriceMin" min="<?php echo $minPriceRange?>" max="<?php echo $maxPriceRange?>" value="<?php echo $array[0];?>"/> - 
				   		<input type="number" class="input-mini" name="filterPriceMax" min="<?php echo $minPriceRange?>" max="<?php echo $maxPriceRange?>" value="<?php echo $array[1];?>"/>
				   </div>
					
				   <div class="clearfix hidden-xs">
					   <input name="filters[core][price]" type="hidden" class="pc-range-slider" value="<?php echo $priceValue;?>" data-slider-min="<?php echo $minPriceRange;?>" 
					   data-slider-max="<?php echo $maxPriceRange;?>"
					   data-slider-value="[<?php echo $sliderValue;?>]"
					   />
				   </div>
				   
				    <input name="filters[core][price]" type="hidden" value="<?php echo $priceValue;?>" class="visible-xs-block"/>
	 		 	</div>
	 		 </div>
	 	 </div>
	</div>
	<hr>
<?php endif;?>

<?php if($minWeightRange != $maxWeightRange):?>
	<?php $sliderValue = (!empty($appliedWeightRange))?key($appliedWeightRange):$minWeightRange.','.$maxWeightRange;?>
	<?php $weightValue = (!empty($appliedWeightRange))?key($appliedWeightRange):'';?>
	
	<div class="accordion" id="accordion-id-price">
	 	<div class="accordion-group">
	 		<div class="accordion-heading">
	 			<a class="accordion-toggle pc-cursor-pointer" data-toggle="collapse" data-parent="#accordion-id-price" data-target=".accordion-body-id-price">		 				
	 				<h3><i class="fa fa-angle-down"></i><span>&nbsp;<?php echo JText::_("COM_PAYCART_WEIGHT").' ( '.$weightUnit.' )'?></span></h3>
	 			</a>		
	 		</div>
	 		<!-- use class "in" for keeping it open -->
	 		<div class="pc-product-filter-body">
		 		 <div class="accordion-body collapse in accordion-body-id-price">
		 		 	<div class="accordion-inner">
			 		 	 <div class="hidden-xs clearfix">
			 		 	   	<span class="pull-left"><strong><?php echo $minWeightRange?></strong></span>
			 		 	   	<span class="pull-right"><strong><?php echo $maxWeightRange;?> </strong></span>
			 		 	 </div>
						 <div class="visible-xs-block">
					   		<?php $array = explode(',', $sliderValue);?>
					   		<input type="number" class="input-mini" name="filterWeightMin" min="<?php echo $minWeightRange?>" max="<?php echo $maxWeightRange?>" value="<?php echo $array[0];?>"/> - 
					   		<input type="number" class="input-mini" name="filterWeightMax" min="<?php echo $minWeightRange?>" max="<?php echo $maxWeightRange?>" value="<?php echo $array[1];?>"/>
						 </div>
						 <div class="clearfix hidden-xs">
							   <input id="pc-filter-weight" name="filters[core][weight]" type="hidden" class="pc-range-slider" value="<?php echo $weightValue;?>" data-slider-min="<?php echo $minWeightRange;?>" 
							   data-slider-max="<?php echo $maxWeightRange;?>"
							   data-slider-value="[<?php echo $sliderValue;?>]" 
							   />
					   	</div>
					   	
					   	<input name="filters[core][weight]" type="hidden" value="<?php echo $weightValue;?>" class="visible-xs-block"/>
		 		 	</div>
		 		 </div>
		 	</div>
	 	 </div>
	</div>
	<hr>
<?php endif;?>
<?php 