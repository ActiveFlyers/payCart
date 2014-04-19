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
<div class='pc-categories-wrapper row-fluid clearfix'>
<div id="pc-categories" class='pc-categories span12 clearfix' data-columns >
	<?php foreach($catlogue->category as $c) : ?>
	<div class="pc-category-outer">
		<div class='pc-category blurground' style="background-image: url('<?php echo $c['cover_media'];?>');">
			<div class="pc-category-inner blurground vertical-center-wrapper" >
				<div class="pc-category-content ">
					<h2 class="vertical-center-content pc-ellipsis"><a href="#"><?php echo $c['title'];?></a></h2>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>
</div>
<?php

