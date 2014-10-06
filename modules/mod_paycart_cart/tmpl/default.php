<?php
/**
 * @package     Paycart.Site
 * @subpackage  mod_paycart_cart
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * @author mMnaishTrivedi
 */

defined('_JEXEC') or die;

//// default product particular
//$product_particulars = Array();
//
//// get product particulars from current cart
//$cart = PaycartAPI::getCurrentCart();
//
//if ($cart instanceof PaycartCart) {
//	$product_particulars = $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
//}
//
//$product_count = count($product_particulars);

?>

		<div id="pc-mod-cart-form-html" class="row-fluid clearfix">
		
			<a href='<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=display');?>'>
				<span>
					<i class="fa fa-shopping-cart fa-2x"></i>
					<b><?php echo JText::_('COM_PAYCART_CART'); ?></b>
				</span>
				
				<span class="pc-mod-cart-text pc-mod-cart-text-empty hide">
					<?php echo JText::_('COM_PAYCART_EMPTY'); ?>
				</span>
				
				<span class=" pc-mod-cart-text pc-mod-cart-product-counter hide">
					<?php //echo $product_count; ?>
				</span>
				
				<span class=" pc-mod-cart-text pc-mod-cart-product-text hide">
					<?php echo JText::_('COM_PAYCART_PRODUCT'); ?>
				</span>
				
				<span class=" pc-mod-cart-text pc-mod-cart-product-text-s hide">
					<?php echo JText::_('COM_PAYCART_PRODUCTS'); ?>
				</span>
			</a>

		</div>
		
		
		<script>
		 (function($){
		
			var modPaycartCart = {};
			 
			modPaycartCart.update = 
				{
					onSuccess : function(response_data)
					{
						//console.log ( {" response contain error :  " : response_data } );

						// hide all stuff
						$('.pc-mod-cart-text').hide();

						// take action 
						switch(response_data['products_count']) 
						{
							case 0 :	// empty string
								$('.pc-mod-cart-text-empty').show();
								break;
								
							case 1 :	// 1 Product
								
								$('.pc-mod-cart-product-counter').html(response_data['products_count']);
								$('.pc-mod-cart-product-text, .pc-mod-cart-product-counter').show();
								break;

							default : // NUMBER Products
								$('.pc-mod-cart-product-counter').html(response_data['products_count']);
								$('.pc-mod-cart-product-text-s, .pc-mod-cart-product-counter').show();
								break ;
						}

					},

					onError : function(response_data)
					{
						console.log ( {" response contain error :  " : response_data } );
					},
					
					do : function(event)
					{
						var request 	= [];
						  
			  			request['success_callback']	=	modPaycartCart.update.onSuccess;

			  			request['url'] = 'index.php?option=com_paycart&view=cart&task=getProductCount&format=json';

						paycart.request(request);
						
					},
				};
			 
			
			// bind event 
			$(document).on( "onPaycartCartAfterUpdateproduct", modPaycartCart.update.do);
			
			// on Document ready 
			$(document).ready(function(){
				modPaycartCart.update.do();
			})
			 	
		  })(paycart.jQuery);
		</script>
			
		