<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>
<div class="row-fluid">	
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form">
		<div class="span6">
			<fieldset class="form-horizontal">
				<h3><?php echo Rb_Text::_('Detial' ); ?></h3>
				<hr>		          
				
	            <div class="control-group">
					<?php echo $form->getLabel('title'); ?>
					<div class="controls"><?php echo $form->getInput('title'); ?></div>	
				</div>
				
				<div class="control-group">
					<?php echo $form->getLabel('desciption'); ?>
					<div class="controls"><?php echo $form->getInput('description'); ?></div>	
				</div>
				
				<div class="control-group">					
					<?php echo $form->getLabel('published'); ?>
					<div class="controls"><?php echo $form->getInput('published'); ?></div>	
				</div>
				
				<div class="control-group">
					<?php echo $form->getLabel('type'); ?>
					<div class="controls"><?php echo $form->getInput('type'); ?></div>	
				</div>	
			</fieldset>
		</div>
	</form>
</div>
<?php 
	