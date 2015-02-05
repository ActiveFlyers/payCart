<?php

/**
 * @copyright   Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Layouts
 * @contact	support+paycart@readybytes.in
 * @author 	Manish Trivedi  
 */

/**
 * List of Populated Variables
 * $displayData = have all required data 
 * $displayData->return_link		// link after logout 
 * 
 */

// no direct access
defined('_JEXEC') or die;
?>

<?php static $pc_mod_product_loaded = false; ?>
<?php if($pc_mod_product_loaded == false) :?>
	<style>
	
	.pc-module-product{
		position: relative;	
	}
	
	.pc-mod-products{	
		padding: 20px 0px;
	}
	
	.pc-mod-products .pc-mod-product{    
	    margin:10px;
	    position: relative;    
	}
	
	.pc-mod-products .pc-mod-product .pc-mod-product-img{    
	    overflow: hidden;
	    max-height: 300px;
	}  
	  
	.pc-mod-products .pc-mod-product .pc-mod-product-img img {
	    width : 100%;
	    -webkit-transition: all 300ms ease-out;  
	    -moz-transition: all 300ms ease-out;  
	    -o-transition: all 300ms ease-out;  
	    -ms-transition: all 300ms ease-out;  
	    transition: all 300ms ease-out;      
	} 
	
	.pc-mod-products .pc-mod-product .pc-mod-product-view{
		position: absolute;
		/*background-color: rgba(0, 0, 0, 0.8);*/
	    color: #FFFFFF;
		z-index: 100;
		left: 0px;	
		right: 0px;
		top: 0px;
		bottom:0px;
		-webkit-transition: all 300ms ease-out 0s;  
	    -moz-transition: all 300ms ease-out 0s;  
	    -o-transition: all 300ms ease-out 0s;  
	    -ms-transition: all 300ms ease-out 0s;  
		transition: all 300ms ease-out 0s;	
		text-align:center;
		opacity: 0;
	}
	
	.pc-mod-products .pc-mod-product .pc-mod-product-view span{
		position: absolute;
		z-index: 101;
		top: 50%;
		left: 50%;
		
		transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);  
	    -o-transform: translate(-50%, -50%);  
	    -webkit-transform: translate(-50%, -50%);
	}
	
		  
	.pc-mod-products .pc-mod-product:hover .pc-mod-product-view{    
	    opacity: 1;    
	}
	
	.pc-mod-products .pc-mod-product:hover img {  
	       -moz-transform: scale(1.2);  
	       -o-transform: scale(1.2);  
	       -webkit-transform: scale(1.2);  
	       transform: scale(1.2);  
	}
	  
	.pc-mod-ellipsis{
		text-overflow: ellipsis;
		max-width:98%;
		overflow:hidden;
	}
	</style>
	<?php $pc_mod_product_loaded = true;?>
<?php endif;?>

<script>
(function($){
	$(document).ready(function() {
	 
	$("#pc-mod-products-<?php echo $module->id;?>").owlCarousel({
	 
	autoPlay: false, //Set AutoPlay to 3 seconds
	 
	items : <?php echo $params->get('xl_cols', 5);?>,
	itemsDesktop : [1199,<?php echo $params->get('lg_cols', 4);?>],
	itemsDesktopSmall : [979,<?php echo $params->get('md_cols', 4);?>],
	itemsTablet : [768,<?php echo $params->get('sm_cols', 3);?>],
	itemsMobile : [400,<?php echo $params->get('xs_cols', 1);?>],
	navigation : true,
	pagination : false
	});
	 
	});
})(paycart.jQuery);


</script>
<?php if(!empty($selected_products)):?>
	<?php $ids = explode(',', $selected_products);?>	
<?php else:?>
	<?php $ids = array_keys($products);?>
<?php endif;?>

<div class="pc-module-product" id="pc-module-products-<?php echo $module->id;?>">
	<div id="pc-mod-products-<?php echo $module->id;?>" class="pc-mod-products">
		<?php foreach($ids as $id) : ?>
			<?php $instance 	= PaycartProduct::getInstance($products[$id]->product_id);?>
			<?php $media 		= $instance->getCoverMedia(true);?>
			<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$products[$id]->product_id);?>"
				title="<?php echo $products[$id]->title;?>">
				<div class="pc-mod-product img-thumbnail item">
					<div class="pc-mod-product-img">
						<img class="" src="<?php echo $media['optimized'];?>" alt="<?php echo $products[$id]->title;?>">							
					</div>
					<span class="pc-mod-product-view">
						<span class="btn btn-lg btn-primary">View</span>								
					</span>
					<h4 class="text-muted"><?php echo $products[$id]->title;?></h4>
					<h4 class="text-muted"><?php echo $products[$id]->price;?></h4>
				</div>
			</a>
		<?php endforeach;?>				
	</div>
</div>
<?php 
