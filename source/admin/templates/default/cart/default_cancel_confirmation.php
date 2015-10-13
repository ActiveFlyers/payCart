<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
echo Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);

?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><?php echo JText::_('COM_PAYCART_ADMIN_ORDER_CANCEL');?></h3>
</div>

<div class="modal-body">
	<div>
		<?php echo JText::_("COM_PAYCART_ADMIN_ORDER_CANCEL_CONFIRMATION")?>
	</div>
	<p>&nbsp;</p>
</div>

<div class="modal-footer text-center">
	<button class="btn" onclick="return false;" data-dismiss="modal"><?php echo JText::_('JNO')?></button>
	<button class="btn btn-primary" onclick="paycart.ajax.go('index.php?option=com_paycart&view=cart&task=initiateCancel&cart_id=<?php echo $cart_id; ?>',{ 'spinner_selector' : '#paycart-ajax-spinner'})"><?php echo JText::_('JYES')?></button>												
</div>
<?php 