<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<?php
$catlogue = include JPATH_ROOT.'/test/_data/ux/catlogue1.php';

?>

<div class='pc-products-wrapper row-fluid clearfix'> 
	<div id="pc-products" class ='pc-products span12 clearfix' data-columns>     
	<?php foreach($catlogue->product as $p) : ?>     
		<div class="pc-product-outer ">
			<div class='pc-product thumbnail'>                 
				<div class="pc-product-content">                    
					<img src="<?php echo $p['cover_media'];?>">
					<h3><a href="#"><?php echo $p['title'];?></a></h3>                    
					<h4><span class="currency">$</span> <span class="amount">210.00</span></h4>
				
					<div class="pc-separator">&nbsp;</div>
					
					<h4 class="teaser row-fluid muted">
						<span class="pull-left"><del>300</del></span>
						<span class="text-right pull-right">30% OFF</span>
					</h4>					
				</div>
			</div>
		</div>
	<?php endforeach;?>
	</div>
</div>
<?php

