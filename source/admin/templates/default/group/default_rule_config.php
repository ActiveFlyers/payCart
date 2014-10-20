<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

?>
<hr/>
<div class="row-fluid">
	<div class="span10">
		<?php echo $configHtml;?>
	</div>
	<div class="span2">
		<a href="#" onClick="paycart.jQuery(this).parent().parent().remove(); return false;">
			<i class="icon icon-delete"> </i>
		</a>
	</div>		
	<input type="hidden" name="<?php echo $namePrefix;?>[ruleClass]" value="<?php echo $ruleClass;?>" />		
</div>