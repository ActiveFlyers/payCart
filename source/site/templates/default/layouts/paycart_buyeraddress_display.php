<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Layouts
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

/**
 * List of Populated Variables
 * $displayData => have all required data 
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );


if(is_array($displayData)) {
	$displayData = (object) $displayData;
}

?>

<address>
  <strong><?php echo $displayData->to;  ?></strong><br>
  <?php echo $displayData->address;  ?><br>
  <?php echo $displayData->city;  ?>, <?php echo $displayData->state;  ?><br>
  <?php echo $displayData->country;  ?> <?php echo $displayData->zipcode;  ?><br>
  <abbr title="Phone"><i class="fa fa-phone"></i></abbr> <?php echo $displayData->phone1;  ?><br>
</address>

	
	<script>

		(function($){
	
			$(document).ready(function($){
				
			});				
		 	
		})(paycart.jQuery);
	</script>
	



