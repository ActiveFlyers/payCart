<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.cart
* @subpackage       Carttotal
* @contact          support+paycart@readybytes.in
* @author			rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="control-group">
	<label title="" class="hasTooltip control-label" for="paycart_form_title" id="paycart_form_title-lbl">
		<?php echo JText::_('PLG_PAYCART_GROUPRULE_CART_TOTAL');?>
	</label>
	<div class="controls">
		<select class="paycart-grouprule-cart-price" name="<?php echo $namePrefix;?>[operator]">
			<option value="=" <?php echo isset($config['operator']) && $config['operator'] == '=' ? 'selected="selected"' : '';?>><?php echo JText::_('PLG_PAYCART_GROUPRULE_CART_TOTAL_EQUAL');?></option>
			<option value="&lt" <?php echo isset($config['operator']) && $config['operator'] == '<' ? 'selected="selected"' : '';?>><?php echo JText::_('PLG_PAYCART_GROUPRULE_CART_TOTAL_LESS_THAN');?></option>
			<option value="&gt" <?php echo isset($config['operator']) && $config['operator'] == '>' ? 'selected="selected"' : '';?>><?php echo JText::_('PLG_PAYCART_GROUPRULE_CART_TOTAL_GREATER_THAN');?></option>
		</select>
		
		<input type="text" name="<?php echo $namePrefix;?>[amount]" value="<?php echo isset($config['amount'])?$config['amount']:0;?>"/> 

	</div>
</div>
