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
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

$records = (array)$products;
?>

<script type="text/javascript">
(function($){
	$(document).ready(function(){
		paycart.product.filter.bindActions();
	})
})(paycart.jQuery);
</script>

<!-- ==================================================================
       1. Top section : Search word & total result count and sorting
     ================================================================== -->
	<div class="row-fluid">
		<div class="pull-left">
			<h3><?php echo $filters->searchWord?> : <?php echo $count;?> <?php echo ($count > 1)?JText::_("COM_PAYCART_ITEMS"):JText::_("COM_PAYCART_ITEM");?></h3>
		</div>
		<div class="pull-right">
			<?php echo PaycartHtml::_('select.genericlist',$sortingOptions, 'filter_sort', 'data-pc-result="filter" data-pc-filter="sort-source"','','',$appliedSort);?>
		</div>
	</div>
	
<!-- =============================================
       2. Top section :  Applied filters 
     ============================================= -->
	<div class="row-fluid">
		<?php echo JLayoutHelper::render('paycart_attribute_applied_filter', $filters);?>
	</div>
	
<!-- =============================================
       3. Search result content 
     ============================================= -->
	<div class="row-fluid">
		<!-- =============================================
       		      3.1 Left section : Product filters 
             ============================================= -->
		<div class="span4 pc-product-filter">
			<form class="pc-form-product-filter" method="post">
				<?php if(!empty($records)): ?>
					<h2><?php echo JText::_("COM_PAYCART_FILTER_RESULT")?></h2>
					
					<!-- category filters -->
					<?php echo JLayoutHelper::render('paycart_attribute_category_filter',$filters);?>	
					<hr>
					
					<!-- custom attribute filterHtml -->
					<?php foreach ($filters->attribute->filterHtml as $id=>$filter):?>
						<h4><?php echo $filter['name']; ?></h4>
						<?php echo $filter['html'];?>
						<hr>
					<?php endforeach;?>
					
					<!-- range filters -->
					<?php echo JLayoutHelper::render('paycart_attribute_range_filter',$filters);?>
					
					<!-- exclude out-of-stock -->
					<h4><?php echo JText::_("COM_PAYCART_AVAILABILITY")?></h4>
					<input type="checkbox" name="filters[core][in_stock]" value="In-Stock" data-pc-result="filter"
					       <?php echo (!empty($filters->core->appliedInStock))?'checked=checked':'';?>/> 
					<?php echo JText::_("COM_PAYCART_FILTER_EXCULDE_OUT_OF_STOCK");?>
				
					<input type="hidden" name="filters[sort]" data-pc-filter="sort-destination" value="<?php echo $appliedSort;?>" />
					<input type="hidden" name="q" value="<?php echo $filters->searchWord?>"/>
					<input type="hidden" name="pagination_start" value="<?php echo $start;?>"/>
				<?php endif;?>
			</form>
		</div>
		
		<!-- =====================================================
       		     3.2 Right section : search result product list 
             ===================================================== -->
		<?php if(!empty($records)):?>
			<div class="span8 clearfix">
				<div class='pc-products-wrapper row-fluid clearfix'>
					<div id="pc-products" class ='pc-products' data-columns> 
					<?php $data           = new stdclass();?>
					<?php $data->products = $products;?>
					<?php $data->pagination_start    = $start;?>
						<?php echo JLayoutHelper::render('paycart_product_list', $data);?>
					</div>
				</div>
				
				<?php if($count > $start):?>
					<div class="text-center pc-loadMore">
						<button class="btn btn-large" data-pc-loadMore="click">
							<?php echo JText::_("COM_PAYCART_FILTER_SHOW_MORE_PRODUCTS")?>
						</button>
					</div>
				<?php endif;?>
			</div>
			
		<?php else:?>
			<div class="span8 clearfix muted center well"> 
				<h3><?php echo JText::_("COM_PAYCART_FILTER_NO_MATCHING_RECORD");?></h3>
			</div>
		<?php endif;?>
	</div>
<?php 