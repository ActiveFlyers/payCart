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

<div class='pc-products-wrapper row-fluid clearfix'> 
	<div id="pc-products" class ='pc-products span12 clearfix' data-columns>     
		
		<?php foreach($products as $p) : ?>
			<?php $instance = PaycartProduct::getInstance($p->product_id,$p)?>     
			<div class="pc-product-outer ">
				<div class='pc-product thumbnail' onclick="location.href='<?php echo PaycartRoute::_('index.php?option=com_paycart&view=product&product_id='.$p->product_id)?>';">                 
					<div class="pc-product-content">   
						<?php $media = $instance->getCoverMedia();?>                 
						<img src="<?php echo $media['optimized'];?>">
						<div class="pc-product-title pc-hide-overflow"><?php echo $instance->getTitle();?></div>                      
						<h4><span class="currency">$</span> <span class="amount"><?php echo $instance->getPrice();?></span></h4>
					</div>
				</div>
			</div>
		<?php endforeach;?>

	</div>
</div>
<?php

