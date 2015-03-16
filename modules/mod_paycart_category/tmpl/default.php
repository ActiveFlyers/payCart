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

// load bootstrap, font-awesome
$config = PaycartFactory::getConfig();
$load = array('jquery', 'rb', 'font-awesome');
if(isset($config->template_load_bootstrap) && $config->template_load_bootstrap){
	$load[] = 'bootstrap';
}
Rb_HelperTemplate::loadMedia($load);
Rb_HelperTemplate::loadSetupEnv();
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/paycart.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.css');
Rb_Html::stylesheet('mod_paycart_category/style.css', array());
?>

<script>
(function($){
	$(document).ready(function() {
	 
	$("#pc-mod-categories-<?php echo $module->id;?>").owlCarousel({
	 
	autoPlay: false, //Set AutoPlay to 3 seconds
	 
	items : <?php echo $params->get('xl_cols', 5);?>,
	itemsDesktop : [1199,<?php echo $params->get('lg_cols', 4);?>],
	itemsDesktopSmall : [979,<?php echo $params->get('md_cols', 4);?>],
	itemsTablet : [768,<?php echo $params->get('sm_cols', 3);?>],
	itemsMobile : [400,<?php echo $params->get('xs_cols', 1);?>],
	navigation : false,
	pagination : false	
	});
	
	// Custom Navigation Events
	$("#pc-mod-cat-<?php echo $module->id;?> .next").click(function(){
		var owl = $("#pc-mod-categories-<?php echo $module->id;?>").data('owlCarousel'); 
		owl.next();
	 	});
	$("#pc-mod-cat-<?php echo $module->id;?> .prev").click(function(){
		var owl = $("#pc-mod-categories-<?php echo $module->id;?>").data('owlCarousel'); 
		owl.prev();
	});

	$('.pc-mod-category').height($('.pc-mod-category').width());
	});
})(paycart.jQuery);


</script>
<?php if(!empty($selected_categories)):?>
	<?php $ids = explode(',', $selected_categories);?>	
<?php else:?>
	<?php $ids = array_keys($categories);?>
<?php endif;?>

<div class="pc-mod-cat<?php echo $class_sfx;?>" id="pc-mod-cat-<?php echo $module->id;?>">
	<div class="clearfix">
		<h3 class="pull-left product-head"><?php echo $module->title;?></h3>						
		<ul class="customNavigation pull-right inline list-inline">
		  <li><i class="prev fa fa-angle-left fa-3x"></i></li>
		  <li><i class="next fa fa-angle-right fa-3x"></i></li>
		</ul>
	</div>
	<div class="">
		<div id="pc-mod-categories-<?php echo $module->id;?>" class="pc-mod-categories">
			<?php foreach($ids as $id) : ?>
				<?php $instance 	= PaycartProductcategory::getInstance($categories[$id]->productcategory_id);?>
				<?php $media 		= $instance->getCoverMedia(true);?>
				<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$categories[$id]->productcategory_id);?>"
					title="<?php echo $categories[$id]->title;?>">
					<div class="pc-mod-category item">
						<?php if(!empty($media['squared'])):?>
						<img class="img-thumbnail" src="<?php echo $media['squared'];?>" alt="<?php echo $categories[$id]->title;?>">
						<?php endif;?>
						<span class="pc-mod-category-caption">
							<span class="pc-mod-ellipsis"><?php echo $categories[$id]->title;?>
							</span>
						</span>
					</div>
				</a>
			<?php endforeach;?>				
		</div>
	</div>
</div>
<?php 
