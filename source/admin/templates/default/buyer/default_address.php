<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
<script>
		paycart.jQuery(document).ready(function($) {

			$('.add-new-address').click( function()
			{
				paycart.admin.buyer.address.add();
			});

		});
</script>
	
<?php
	if (empty($addresses)):
	?>
	
	<div class="row-fluid">	
		<div class="center">
			<a href="#" class="btn btn-success add-new-address">
				<i class="icon-plus-sign icon-white"></i>&nbsp; <?php echo Rb_Text::_('COM_PAYCART_ADD_NEW_ADDRESS');?>
			</a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-question-sign "></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_SUPPORT_LINK');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-book"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_DOCUMENTATION_LINK');?></a>
		</div>
	</div>
<?php endif; ?>


