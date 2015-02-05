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

$appliedPriceRange  = $displayData->core->appliedPriceRange;
$appliedWeightRange = $displayData->core->appliedWeightRange;
$minPriceRange  	= $displayData->core->minPriceRange;
$maxPriceRange  	= $displayData->core->maxPriceRange;
$minWeightRange 	= $displayData->core->minWeightRange;
$maxWeightRange 	= $displayData->core->maxWeightRange;
$currency   		= $displayData->currency;
$weightUnit 		= $displayData->weightUnit;
?>
	
<?php if($minPriceRange != $maxPriceRange):?>
	<?php $sliderValue = (!empty($appliedPriceRange))?key($appliedPriceRange):$minPriceRange.','.$maxPriceRange;?>
	<?php $priceValue  = (!empty($appliedPriceRange))?key($appliedPriceRange):'';?>
	
	<h4><?php echo JText::_("COM_PAYCART_PRICE").' ( '.$currency.' )'?></h4>
	<b><?php echo $minPriceRange?></b> &nbsp;&nbsp;
	   <input id="pc-filter-price" name="filters[core][price]" type="hidden" class="span12 pc-range-slider" value="<?php echo $priceValue;?>" data-slider-min="<?php echo $minPriceRange;?>" 
	   data-slider-max="<?php echo $maxPriceRange;?>"
	   data-slider-value="[<?php echo $sliderValue;?>]" 
	   />
	   &nbsp;&nbsp;<b><?php echo $maxPriceRange;?></b> 
	<hr>
<?php endif;?>

<?php if($minWeightRange != $maxWeightRange):?>
	<?php $sliderValue = (!empty($appliedWeightRange))?key($appliedWeightRange):$minWeightRange.','.$maxWeightRange;?>
	<?php $weightValue = (!empty($appliedWeightRange))?key($appliedWeightRange):'';?>
	<h4><?php echo JText::_("COM_PAYCART_WEIGHT").' ( '.$weightUnit.' )'?></h4>
	<b><?php echo $minWeightRange;?></b> &nbsp;&nbsp;
	   <input id="pc-filter-weight" name="filters[core][weight]" type="hidden" class="span12 pc-range-slider" value="<?php echo $weightValue?>" data-slider-min="<?php echo $minWeightRange;?>" 
	   data-slider-max="<?php echo $maxWeightRange;?>" 
	   data-slider-value="[<?php echo $sliderValue?>]"
	   />
	   &nbsp;&nbsp;<b><?php echo $maxWeightRange;?></b> 
	<hr>
<?php endif;?>
<?php 