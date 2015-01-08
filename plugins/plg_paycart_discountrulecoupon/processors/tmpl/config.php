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

<!-- script to initiate chosen -->
<script type="text/javascript">
 (function($){
	$(document).ready(function(){
		$('#paycart_discountrule_form_applyon').chosen({
		    "disable_search_threshold": 10,
		    "allow_single_deselect": true
		});
	});
 })(paycart.jQuery);
</script>

<div class="row-fluid">

	<div class="span6">
		
		<div class='control-group'>
			<div class='control-label'>
				<label aria-invalid='false' id='paycart_discountrule_form_coupon-lbl' for='paycart_discountrule_form_coupon' class='control-label'>
				<?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_COUPON'); ?>
				<span class="star">*</span>
				</label>
			</div>
			<div class='controls'>
				<input name='paycart_discountrule_form[coupon]' id='paycart_discountrule_form_coupon' required="" value="<?php echo @$coupon_code;?>"  type='text' />
				<div class="pc-error" for="paycart_discountrule_form_coupon"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
			</div>
											
		</div>
		
		<div class='control-group'>
			<div class='control-label'>
				<label aria-invalid='false' id='paycart_discountrule_form_applyon-lbl' for='paycart_discountrule_form_applyon' class='control-label'>
				<?php echo Jtext::_('COM_PAYCART_ADMIN_DISCOUNTRULE_APPLY');?> <?php echo JText::_('COM_PAYCART_ADMIN_DISCOUNTRULE_ON');?>
				<span class="star">*</span>
				</label>
			</div>
			<div class='controls'>
				<?php echo PaycartHtmlApplyon::getList('paycart_discountrule_form[apply_on]', @$applyOn, array(Paycart::RULE_APPLY_ON_PRODUCT, Paycart::RULE_APPLY_ON_SHIPPING),'paycart_discountrule_form_applyon');?>
			</div>
		</div>
	</div>
	
</div>

<?php 