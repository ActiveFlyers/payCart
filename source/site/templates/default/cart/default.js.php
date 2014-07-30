<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/

/**
 * @PCTODO: List of Populated Variables
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>

<script>


	(function($) {

		paycart.cart = {};

	    paycart.cart.product = {};

	    paycart.cart.product = {

	    	    updateQuantity : function(productId, quantity)
	    	    {
    	    		var link  = 'index.php?option=com_paycart&task=updateQuantity&view=cart';
    	    		var data  = {'product_id': productId, 'quantity': quantity};
    	    		paycart.ajax.go(link,data);
    	    	},
    	    	
    	        remove : function(productId)
    	        {
    	      		var link  = 'index.php?option=com_paycart&task=removeProduct&view=cart';
    	      	    var data  = {'product_id': productId};
    	      	    paycart.ajax.go(link,data);
    	      	}
	       };
	 	
				
	})(paycart.jQuery);


</script>