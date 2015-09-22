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
<style>

.paycart .outer{
	display: inline-block;
    border: 1px solid #e5e5e5;
    height: 60px;
    padding: 2px;
    width: 60px;
    text-align:center;
    position: relative;
    margin-bottom: 10px;
}
.zoomWrapper {padding-left:1%;}
.fancybox-overlay {
    background: rgba(0, 0, 0, 0.8) ;
    display: none;
    left: 0;
    overflow: hidden;
    top: 0;
    z-index: 8010;
}

.paycart .pc-fancybox{
  margin: auto;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  max-height:100%;
}
.paycart #pc-zoomImages{
	display:table-cell; 
	vertical-align:middle;
	 text-align:center;
	}
</style>


<?php $current = current($images);?>
<div class="clearfix">
	<div id="pc-gallery" class="pull-left">
			<ul class="unstyled">
			<?php 
			
			foreach ($images as $mediaId => $detail):?>
					<?php list($width, $height, $type, $attr) =   getimagesize($detail['optimized']);?>
				<li>
				 <a  href="#" data-height="<?php echo $height;?>"  data-image="<?php echo $detail['optimized'];?>" data-zoom-image="<?php echo $detail['original'];?>" class="outer"> 
					<img class="pc-fancybox" src="<?php echo $detail['thumbnail'];?>" data-fancybox-group ="pc-gallery" data-fancybox-href="<?php echo $detail['original'];?>" />
				</a>
				</li>
			<?php endforeach;?>
			</ul>
		</div>
		<div class="pull-left">
		<img id="pc-zoomImages"  src="<?php echo $current['optimized'];?>" data-zoom-image="<?php echo $current['original'];?>"/>
		</div>
</div>	
	
<script>
(function($){

	$(document).ready(function(){
		
		var zoom_type="<?php echo $zoomType;?>";
		var zoom_tint_color='#CDCDCD';
		var zoom_tint_opacity=0.4;
		var zoomWindowWidth = 600;
		var zoomWindowHeight = 600;
		var borderSize = 1;
		var zoomTint = false;

		if(zoom_type == 'window'){
			zoomTint = true;
			var zoomWindowWidth = <?php echo $zoomWidth;?>;
			var zoomWindowHeight = <?php echo $zoomHeight;?>;
			
			if(zoomWindowHeight == ''){
				 var max_height = 0;
					
			$('#'+'pc-gallery' + ' a').each(function() {
				cur_height = $(this).data("height");
				if (cur_height > max_height) {
				   max_height = cur_height;
				}
		    });

			zoomWindowHeight = max_height;
			}
		}

		$("#pc-zoomImages").elevateZoom({ zoomType : zoom_type, cursor:"crosshair", tint:zoomTint,borderSize : borderSize,tintColour:zoom_tint_color,tintOpacity:zoom_tint_opacity,zoomWindowHeight:zoomWindowHeight, gallery: 'pc-gallery',cursor: 'pointer', galleryActiveClass: "active", imageCrossfade: true, responsive: true, easing : true}); 	
	
		$("#pc-zoomImages").bind("click", function(e) {	

			  var ez =   $('#pc-zoomImages').data('elevateZoom');
			  ez.closeAll(); //NEW: This function force hides the lens, tint and window	
			  $.fancybox(ez.getGalleryList(),{
				  type        : 'iframe', 
				  fitToView   : false,
				  autoSize    : false,
				  openEffect  : 'elastic',
	              closeEffect : 'elastic',
				  afterLoad : function() {
					  $('.fancybox-iframe').contents().find('head').append('<style type="text/css">img{max-width:100%!important;max-height:100%!important;margin: auto;position: absolute;top: 0; left: 0; bottom: 0; right: 0;}</style>');
				  }
			    });
				
			  return false;
		}); 
	})
})(paycart.jQuery);
</script>	
<?php 
