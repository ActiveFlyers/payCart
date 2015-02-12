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
$appliedAttr        = $displayData->attribute->appliedAttr;
$attributeOptions   = $displayData->attribute->appliedAttrDetail;
$appliedPriceRange  = $displayData->core->appliedPriceRange;
$appliedWeightRange = $displayData->core->appliedWeightRange;
$appliedInStock     = $displayData->core->appliedInStock;
?>

<!-- Custom attributes -->
<?php foreach ($appliedAttr as $id=>$data):?>
	<?php if(!empty($data)):?>
		<?php foreach ($data as $key=>$value):?>
			<span class="label label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[attribute][<?php echo $id?>][<?php echo $value?>]">
				<?php echo $attributeOptions[$id][$value]->title ;?>&nbsp;&nbsp;<i class="fa fa-times"></i>
			</span>&nbsp;
		<?php endforeach;?>
	<?php endif; ?>
<?php endforeach;?>

<!-- applied Price Range -->
<?php if(!empty($appliedPriceRange)):?>
    <?php $key   = key($appliedPriceRange);?>
    <?php $value = $appliedPriceRange[$key];?>
	<span class="label label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[core][price]">
		<?php echo $value;?>&nbsp;&nbsp;<i class="fa fa-times"></i>
	</span>&nbsp;
<?php endif;?>

<!-- applied Weight Range -->
<?php if(!empty($appliedWeightRange)):?>
	<?php $key   = key($appliedWeightRange);?>
    <?php $value = $appliedWeightRange[$key];?>
	<span class="label label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[core][weight]">
		<?php echo $value;?>&nbsp;&nbsp;<i class="fa fa-times"></i>
	</span>&nbsp;
<?php endif;?>

<!-- In stock -->
<?php if(!empty($appliedInStock)) :?>
	<?php $key   = key($appliedInStock);?>
    <?php $value = $appliedInStock[$key];?>
	<span class="label label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[core][<?php echo $key?>]">
		<?php echo $value;?>&nbsp;&nbsp;<i class="fa fa-times"></i>
	</span>&nbsp;
<?php endif;?>
<?php 