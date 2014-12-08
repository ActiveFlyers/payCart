<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * edit screen of configuration
 * 
 * @since 1.0.0
 *  
 * @author Gaurav
 */

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE');?></h3>
</div>

<div class="modal-body">
	<div class="center">
		<span><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_SUPPORTED_LANGUAGE_UPDATED');?></span>		
	</div>	
</div>

<div class="modal-footer text-center">
	<button class="btn" onclick="return false;" data-dismiss="modal"><?php echo Rb_Text::_('COM_PAYCART_ADMIN_CLOSE')?></button>													
</div>

<?php 