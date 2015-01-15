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
 * @author Rimjhim
 */

?>

<div class="row-fluid">
	<div class="span3">
		<h2><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_CATALOGUE_HEADER');?></h2>
		<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_CATALOGUE_HEADER_MSG');?>
		</div>
	</div>
	<div class="span9">
		<fieldset class="form">
			<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('catalogue_image_thumb_height') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls">
							<?php echo $field->input; ?>
							<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
						</div>
					</div>
				</div>
				
				<div class="span6">
					<?php $field = $form->getField('catalogue_image_thumb_width') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls">
							<?php echo $field->input; ?>
							<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
						</div>
					</div>
				</div>						
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('catalogue_image_optimized_height') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls">
							<?php echo $field->input; ?>
							<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
						</div>
					</div>
				</div>
				
				<div class="span6">
					<?php $field = $form->getField('catalogue_image_optimized_width') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls">
							<?php echo $field->input; ?>
							<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
						</div>
					</div>
				</div>						
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('catalogue_image_squared_size') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls">
							<?php echo $field->input; ?>
							<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
						</div>
					</div>
				</div>
				
				
			</div>

			<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('catalogue_image_upload_size') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls">
							<?php echo $field->input; ?>
							<div class="pc-error" for="<?php echo $field->id;?>"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
						</div>
					</div>
				</div>
				
				<div class="span6">
					<?php $field = $form->getField('catalogue_weight_unit') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>					
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('catalogue_dimension_unit') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>	
				
				<div class="span6">&nbsp;</div>					
			</div>
			
		</fieldset>
	</div>
</div>
<?php 