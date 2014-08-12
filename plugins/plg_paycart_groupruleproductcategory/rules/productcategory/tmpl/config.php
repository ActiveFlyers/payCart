<?php

/**
* @copyright        Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PacartGrouprule.Product
* @subpackage       productcategory
* @contact          support+paycart@readybytes.in
* @author			rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="control-group">
	<label title="" class="hasTooltip control-label" for="paycart_form_title" id="paycart_form_title-lbl">
		<?php echo JText::_('COM_PAYCART_ADMIN_PRODUCT_CATEGORY');?>
	</label>
	<div class="controls">
		<select class="paycart-grouprule-product-category" name="<?php echo $namePrefix;?>[category_assignment]">
			<option value="any" <?php echo isset($config['category_assignment']) && $config['category_assignment'] == 'any' ? 'selected="selected"' : '';?>><?php echo JText::_('COM_PAYCART_ADMIN_ANY');?></option>
			<option value="selected" <?php echo isset($config['category_assignment']) && $config['category_assignment'] == 'selected' ? 'selected="selected"' : '';?>><?php echo JText::_('COM_PAYCART_ADMIN_SELECTED');?></option>
			<option value="except" <?php echo isset($config['category_assignment']) && $config['category_assignment'] == 'except' ? 'selected="selected"' : '';?>><?php echo JText::_('COM_PAYCART_ADMIN_EXCEPT');?></option>
		</select>
		
		<?php echo PaycartHtmlCategory::getList($namePrefix."[category][]", isset($config['category'])?$config['category']:array(), false, array('class'=>'paycart-grouprule-product-category', 'multiple' => 'true'));?>

	</div>
</div>
