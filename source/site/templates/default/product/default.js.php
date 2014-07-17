<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}
?>

<script type="text/javascript">
(function($){
paycart.product = {};
paycart.product.selector = {};

paycart.product.selector.onChange= function(value){
	if(value.id == '<?php echo 'pc-attr-'.$baseAttrId?>'){
		$('.pc-product-base-attribute').val('<?php echo $baseAttrId?>');
	}
	$('.pc-product-attributes').submit();
}
})(paycart.jQuery);
</script>
