<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

//@PCTODO: mention all populated variables

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
<style>
	/* To load popup in proper position */
	#paycart div.modal {
	    margin-left: -200px;
	}
</style>

<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo JText::_($model_title); ?></h3>
	</div>
</div>

<div class="modal-body form-horizontal" id="rbWindowBody">
	<!--  New_atrribute_creation body		-->
	<form id="paycart_buyeraddress_form" class="pc-form-validate">	 
		<?php echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_edit', $display_data);?>
		
		<input type="hidden" name="task" value="save" />
		<input type='hidden' name='id' value='<?php echo $record_id;?>' />		
	</form>
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
		<button class="btn btn-primary " onClick="paycart.admin.buyeraddress.add.go();"> 
			<?php echo JText::_('COM_PAYCART_ADMIN_SAVE'); ?> </button>
		<button class="btn" data-dismiss="modal" aria-hidden="true" ><?php echo JText::_('COM_PAYCART_ADMIN_CANCEL'); ?> </button>
	</div>
</div>

<script>
(function($)
		{
			$(document).ready(function($) 
					{
						paycart.formvalidator.initialize('#paycart_buyeraddress_form');
						$('#paycart_buyeraddress_form select').chosen();		
					});
		})(paycart.jQuery)
</script>






