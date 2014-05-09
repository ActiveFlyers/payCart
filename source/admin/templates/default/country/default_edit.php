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
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">

	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'country', array('active' => 'detail')); ?>
		
<!--	 Account Details Tab		-->
			<?php echo JHtml::_('bootstrap.addTab', 'country', 'detail', JText::_('COM_PAYCART_TAB_COUNTRY_DETAILS')); ?>
				
				<?php foreach ($form->getFieldset('country') as $field):?>
				
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php endforeach;?>
				
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
<!--	 Address Details Tab		-->
			<?php echo JHtml::_('bootstrap.addTab', 'country', 'state', JText::_('COM_PAYCART_TAB_STATE_DETAILS')); ?>
				
				<?php echo $this->loadTemplate('states'); ?>

			<?php echo JHtml::_('bootstrap.endTab'); ?>

		</fieldset>
	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />

</form>





