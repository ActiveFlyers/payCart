<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		Garima Agal
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );?>

	<?php $current = current($images);?>
		<img id="zoomImages"  rel="gallery" src="<?php echo $current['optimized'];?>" data-zoom-image="<?php echo $current['original'];?>"/>
		
		<div id="pc-gallery">
		
			<?php 
			
			foreach ($images as $mediaId => $detail):?>
				 <a href="<?php echo $detail['original'];?>"  rel="gallery" data-image="<?php echo $detail['optimized'];?>" data-zoom-image="<?php echo $detail['original'];;?>" > 
					<img class="pc-fancybox" src="<?php echo $detail['thumbnail'];?>" />
				</a>
			<?php endforeach;?>
		</div>
<script>

		paycart.jQuery("#zoomImages").elevateZoom({ zoomType : "<?php echo $zoomType;?>", zoomWindowWidth : <?php echo $zoomWidth;?>, zoomWindowHeight : <?php echo $zoomHeight;?> , gallery: 'pc-gallery',cursor: 'pointer', galleryActiveClass: "active", imageCrossfade: true, responsive: true, easing : true}); 	
		paycart.jQuery("#zoomImages").bind("click", function(e) {	
			  var ez =   paycart.jQuery('#zoomImages').data('elevateZoom');
			  ez.closeAll(); //NEW: This function force hides the lens, tint and window	
			  paycart.jQuery.fancybox(ez.getGalleryList());
		
			  return false;
		}); 
</script>	
<?php 
