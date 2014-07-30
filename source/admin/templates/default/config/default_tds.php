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
		<h2><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_DISCOUNT_HEADER');?></h2>
		<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_DISCOUNT_HEADER_MSG');?>
		</div>
	</div>
	<div class="span9">
		<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('discountrule_issuccessive') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>				
		</div>
	</div>
</div>
<?php 