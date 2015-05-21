<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Buyer
* @subpackage       BuyerPayplans
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="control-group">
	<label title="" class="hasTooltip control-label" for="paycart_form_title" id="paycart_form_title-lbl" 
			data-original-title="&lt;strong&gt;<?php echo JText::_('Payplans Plan');?>&lt;/strong&gt;&lt;br /&gt;<?php echo JText::_('COM_PAYCART_ADMIN_DESCRIPTION');?>">
		<?php echo JText::_('Payplans Plan');?>
	</label>
	<div class="controls">
		<select class="paycart-grouprule-buyerpayplans-plan" name="<?php echo $namePrefix;?>[plan_assignment]" data-pc-selector="pc-option-manipulator" id="<?php echo $idPrefix;?>plan-assignment">
			<option value="aboveAll" <?php echo !isset($config['plan_assignment']) || $config['plan_assignment'] == 'any' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_SELECTED_ALL');?></option>
			<option value="selected" <?php echo isset($config['plan_assignment']) && $config['plan_assignment'] == 'selected' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_SELECTED');?></option>
			<option value="except" <?php echo isset($config['plan_assignment']) && $config['plan_assignment'] == 'except' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('COM_PAYCART_EXCEPT');?></option>
		</select>
		
		<span data-pc-option-manipulator="<?php echo $idPrefix.'plan-assignment';?>">
		
			<select class="paycart-grouprule-buyerpayplans-plan" name="<?php echo $namePrefix;?>[plan][]" multiple="true">
				<?php foreach($userPlans as $plan_id => $data):?>
					<option value="<?php echo $plan_id;?>" <?php echo isset($config['plan']) && in_array($plan_id, $config['plan']) ? 'selected="selected"' : '';?>><?php echo $data->title;?></option>
				<?php endforeach;?>
			</select>
			
		</span>
	</div>
</div>
