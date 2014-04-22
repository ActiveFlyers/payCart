<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal , Manish TRivedi
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

PaycartHtml::_('behavior.formvalidation');
?>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">

	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'buyer', array('active' => 'details')); ?>
		
<!--	 Account Details Tab		-->
			<?php echo JHtml::_('bootstrap.addTab', 'buyer', 'details', Rb_Text::_('COM_PAYCART_BUYER_ACCOUNT_DETAILS', true)); ?>
				
				<?php foreach ($form->getFieldset('basic-user') as $field):?>
				
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php endforeach;?>
				
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
<!--	 Address Details Tab		-->
			<?php echo JHtml::_('bootstrap.addTab', 'buyer', 'address', Rb_Text::_('COM_PAYCART_BUYER_ACCOUNT_DETAILS', true)); ?>
				
				<?php echo $this->loadTemplate('address'); ?>

			<?php echo JHtml::_('bootstrap.endTab'); ?>

		</fieldset>
	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />

</form>