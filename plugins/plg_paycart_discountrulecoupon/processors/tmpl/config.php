<?php
/**
 * @package     Paycart
 * @subpackage  Paycart.plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      mManishTrivedi 
 */

defined('_JEXEC') or die;

/**
 * PayCart! Promotion Processor config
 *
 * @package     Paycart
 * @subpackage  Paycart.plugin
 * @author 		manish
 *
 */

// $coupon_code var shoud be set
?>

<div class="row-fluid">

	<div class="span6">
		
		<div class='control-group'>
			
			<div class='control-label'>
				<label aria-invalid='false' id='paycart_form_coupon-lbl' for='paycart_form_coupon' class='control-label'>
				<?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_COUPON'); ?>
				<span class="star">*</span>
				</label>
			</div>
			
			<div class='controls'>
				<input name='paycart_form[coupon]' id='paycart_form_coupon' required="" value="<?php echo @$coupon_code;?>"  type='text' />
			</div>
											
		</div>
		
	</div>
	
</div>

<?php 