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

<div id="rbWindowTitle">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $model_title ?></h3>
	</div>
</div>

<div class="modal-body" id="rbWindowBody">
	
	<!--  New_atrribute_creation body		-->
	<form id="paycart_state_form" class="rb-validate-form">
	
		<?php foreach ($form->getFieldset('state') as $field):?>
				
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
		<?php endforeach;?>
	 
		<input type="hidden" name="task" value="save" />
		<input type='hidden' name='id' value='<?php echo $record_id;?>' />
		
	</form>
	
</div>

<div id="rbWindowFooter">
	<div class="modal-footer">
		<button class="btn btn-primary " onClick="paycart.admin.state.add.go();"> 
			<?php echo JText::_('COM_PAYCART_BUTTON_SAVE'); ?> </button>
		<button class="btn" data-dismiss="modal" aria-hidden="true" ><?php echo JText::_('COM_PAYCART_BUTTON_CANCLE'); ?> </button>
	</div>
</div>

<script>
<?php include_once PAYCART_PATH_ADMIN_TEMPLATE.'/default/_media/js/template.js'; ?>
(function($)
		{
			$(document).ready(function($) 
					{
						paycart.form.validation.init('#paycart_state_form');
//						paycart_radio_btn_group_init();	

					});
		})(paycart.jQuery)
</script>






