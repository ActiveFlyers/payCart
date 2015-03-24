<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );?>
?>

<div>
	<?php echo Rb_Html::_('rb_html.text.filter', 'title', 'productcategory', $filters, 'filter_paycart');?>
	<?php echo Rb_Html::_('rb_html.boolean.filter', 'published', 'productcategory', $filters, 'filter_paycart','COM_PAYCART_ADMIN');?>
	
	<?php $options = array()?>
	<?php $options[0] = array('value'=>'','text'=>JText::_('JOPTION_SELECT_MAX_LEVELS'))?>
	<?php for ($i = 1; $i <= 10; $i++){
			$options[$i] = array('value'=>$i,'text'=>$i);
		  }
	?>
	<?php echo JHtml::_('select.genericlist', $options, 'filter_paycart_productcategory_level[]', array('onchange' => 'document.adminForm.submit()'), 'value', 'text', $filters['level']); ?>
	
	<div><input type="submit" name="filter_submit" class="btn btn-primary" value="<?php echo JText::_('Go');?>" /></div>
	<div><input type="reset"  name="filter_reset"  class="btn" value="<?php echo JText::_('Reset');?>" onclick="paycart.admin.grid.filters.reset(this.form);" /></div>
	
</div>
<?php 