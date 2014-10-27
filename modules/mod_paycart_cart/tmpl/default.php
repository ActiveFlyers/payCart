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

$product_count =0 ;
?>

		<div id="pc-mod-cart-form-html" class="row-fluid clearfix">
		
			<a href='<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=display');?>'>
				<span>
					<i class="fa fa-shopping-cart fa-2x"></i>
					<b><?php echo JText::_('COM_PAYCART_CART'); ?></b>
				</span>
				
				<span class=" pc-mod-cart-badge badge badge-info pc-mod-cart-badge-product-counter ">
					<?php echo $product_count; ?>
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
						$('.pc-mod-cart-badge-product-counter').html(response_data['products_count']);
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
			$(document).on( "onPaycartCartUpdateproduct", modPaycartCart.update.do);
			
			// on Document ready 
			$(document).ready(function(){
				modPaycartCart.update.do();
			})
			 	
		  })(paycart.jQuery);
		</script>
			
		