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

	$(document).ready(function(){
		if(<?php echo ($isExistInCart)?1:0;?>){
			paycart.product.changeButtonText();
		}
	});
	
	paycart.product = {};
	paycart.product.selector = {};
	
	paycart.product.selector.onChange= function(value){
		if($baseAttrId && value.id == '<?php echo 'pc-attr-'.$baseAttrId?>'){
			$('.pc-product-base-attribute').val('<?php echo $baseAttrId?>');
		}
		$('.pc-product-attributes').submit();
	},
	
	paycart.product.addtocart = function(productId){
		paycart.ajax.go('index.php?option=com_paycart&view=cart&task=addProduct&product_id='+productId);
		paycart.product.changeButtonText();
	},

	paycart.product.changeButtonText = function(){
		$('.pc-btn-addtocart').html("<?php echo JText::_('COM_PAYCART_CART_VIEW')." &nbsp;&nbsp; <i class='fa fa-chevron-right'></i>";?>");
		$('.pc-btn-addtocart').attr('onClick','rb.url.redirect("index.php?option=com_paycart&view=cart"); return false;');
		
		$('.pc-btn-buynow').replaceWith("<h3 class='text-center text-info'><?php echo JText::_('COM_PAYCART_PRODUCT_ADDED_TO_CART')?></h3>");
	}
})(paycart.jQuery);
</script>
