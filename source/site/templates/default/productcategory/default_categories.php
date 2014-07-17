<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<div class='pc-categories-wrapper row-fluid clearfix'>
	<div id="pc-categories" class='pc-categories span12 clearfix' data-columns >

		<?php foreach($categories as $c) : ?>
		<?php $instance = PaycartProductcategory::getInstance($c->productcategory_id, $c);?>
	
		<div class="pc-category-outer">
		<?php $media = $instance->getCoverMedia();?>
			<div class='pc-category blurground' style="background-image: url('<?php echo $media['optimized'];?>');">
				<div class="pc-category-inner blurground vertical-center-wrapper" >
					<div class="pc-category-content ">
						<h2 class="vertical-center-content pc-ellipsis"><a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&productcategory_id='.$c->productcategory_id)?>"><?php echo $instance->getTitle();?></a></h2>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach;?>

	</div>
</div>
<?php

