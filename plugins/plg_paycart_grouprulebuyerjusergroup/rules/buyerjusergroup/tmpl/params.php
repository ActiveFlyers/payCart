<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Buyer
* @subpackage       BuyerJusergroup
* @contact          support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="control-group">
	<label title="" class="hasTooltip control-label" for="paycart_form_title" id="paycart_form_title-lbl" 
			data-original-title="&lt;strong&gt;<?php echo Rb_Text::_('Usergroups');?>&lt;/strong&gt;&lt;br /&gt;<?php echo Rb_Text::_('Description');?>">
		<?php echo Rb_Text::_('Usergroups');?>
	</label>
	<div class="controls">
		<select class="paycart-grouprule-buyerjusergroup-groups" name="<?php echo $namePrefix;?>[jusergroup_assignment]">
			<option value="any" <?php echo isset($params['jusergroup_assignment']) && $params['jusergroup_assignment'] == 'any' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('Any');?></option>
			<option value="selected" <?php echo isset($params['jusergroup_assignment']) && $params['jusergroup_assignment'] == 'selected' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('Selected');?></option>
			<option value="except" <?php echo isset($params['jusergroup_assignment']) && $params['jusergroup_assignment'] == 'except' ? 'selected="selected"' : '';?>><?php echo Rb_Text::_('Except');?></option>
		</select>
		
		<select class="paycart-grouprule-buyerjusergroup-groups" name="<?php echo $namePrefix;?>[jusergroups][]" multiple="true">
			<?php foreach($usergroups as $group_id => $group):?>
				<option value="<?php echo $group_id;?>" <?php echo isset($params['jusergroups']) && in_array($group_id, $params['jusergroups']) ? 'selected="selected"' : '';?>><?php echo $group->name;?></option>
			<?php endforeach;?>
		</select>
	</div>
</div>
