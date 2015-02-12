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
	<div class="row">
		<div class="pull-left">
			<div class="col-sm-12">
			<h3><?php echo !empty($filters->searchWord)?$filters->searchWord:JText::_('COM_PAYCART_TOTAL')?> : <?php echo $count;?> <?php echo ($count > 1)?JText::_("COM_PAYCART_ITEMS"):JText::_("COM_PAYCART_ITEM");?></h3>
			</div>
		</div>
		<div class="pull-right">
			<div class="col-sm-12">
			<?php echo PaycartHtml::_('select.genericlist',$sortingOptions, 'filter_sort', 'data-pc-result="filter" data-pc-filter="sort-source"','','',$appliedSort);?>
			</div>
		</div>
	</div>
	
<!-- =============================================
       2. Top section :  Applied filters 
     ============================================= -->
	<div class="row">
		<div class="col-sm-12">
		<?php echo JLayoutHelper::render('paycart_attribute_applied_filter', $filters);?>
		</div>
	</div>
	<hr/>
<!-- =============================================
       3. Search result content 
     ============================================= -->
	<div class="row">
		<!-- =============================================
       		      3.1 Left section : Product filters 
             ============================================= -->
        <?php 
		 	ob_start();
		?>
		 	<!-- category filters -->
			<?php echo JLayoutHelper::render('paycart_attribute_category_filter',$filters);?>
			<?php if($showFilters):?>	
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
				<input type="hidden" name="query" value="<?php echo $filters->searchWord?>"/>
				<input type="hidden" name="pagination_start" value="<?php echo $start;?>"/>
			<?php endif;?>
		 <?php 
			 $filterHtml = ob_get_contents();
			 ob_get_clean();
		 ?>
             
		<div class="col-md-3 col-sm-4 pc-product-filter navbar">
			<ul class="nav"> 				
				<li class="visible-xs-block"> 
				  	<a href="javascript:void(0);" data-toggle="collapse" data-target=".nav-collapse.pc-nav-filters">
						<i class="fa fa-bars"></i> <?php echo JText::_("COM_PAYCART_FILTER_BY")?>
				 	</a>
				</li>
			</ul>
	
			<div class="nav-collapse pc-nav-filters collapse visible-xs-block" style="height:0px;">
				<form class="pc-form-product-filter navbar-form" data-pc-filter-form="mobile" method="post">
					<?php echo $filterHtml?>
				</form>
			</div>
		
			<div class="hidden-xs">
				<form class="pc-form-product-filter" data-pc-filter-form="desktop" method="post">
					<h2><?php echo JText::_("COM_PAYCART_FILTER_BY")?></h2>
					<?php echo $filterHtml?>
				</form>
			</div>
		</div>
		
		<!-- =====================================================
       		     3.2 Right section : search result product list 
             ===================================================== -->
		<?php if(!empty($records)):?>
			<div class="col-md-9 col-sm-8  clearfix">
				<div class='pc-products-wrapper row clearfix'>
					<div id="pc-products" class ='pc-products' data-columns> 
					<?php $data           = new stdclass();?>
					<?php $data->products = $products;?>
					<?php $data->pagination_start    = $start;?>
						<?php echo JLayoutHelper::render('paycart_product_list', $data);?>
					</div>
				</div>
				
				<?php if($count > $start):?>
					<div class="text-center pc-loadMore">
						<button class="btn btn-lg" data-pc-loadMore="click">
							<?php echo JText::_("COM_PAYCART_FILTER_SHOW_MORE_PRODUCTS")?>
						</button>
					</div>
				<?php endif;?>
			</div>
			
		<?php else:?>
			<div class="col-md-9 col-sm-8 clearfix muted center well"> 
				<h3><?php echo JText::_("COM_PAYCART_FILTER_NO_MATCHING_RECORD");?></h3>
			</div>
		<?php endif;?>
	</div>
<?php 