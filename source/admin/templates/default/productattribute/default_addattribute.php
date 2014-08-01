<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	Frontend
* @contact 		support+paycart@readybytes.in
* @author 		rimjhim
*/
defined('_JEXEC') or die();

?>
<div class="control-group">
	<div class="control-label"><label><?php echo $productAttribute->getTitle();?></label></div>
	<div class="controls">
		<?php echo $productAttribute->getEditHtml($productAttributeValue);?>
	</div>	
</div>
<?php 
		