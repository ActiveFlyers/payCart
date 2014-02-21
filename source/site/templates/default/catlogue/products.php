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
<div id="pc-products" class='pc-products span12 clearfix' data-columns>
	<?php foreach($catlogue->product as $p) : ?>
	<div class="pc-product-outer thumbnails">
		<div class='pc-product '>
				<div class="pc-product-content">
					<img src="<?php echo $p['cover_media'];?>">
					<h3><a href="#"><?php echo $p['title'];?></a></h3>
				</div>
		</div>
	</div>
	<?php endforeach;?>
</div>
</div>
<?php

