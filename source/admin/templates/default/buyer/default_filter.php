<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain 
*/

defined('_JEXEC') OR die();
?>

<div>
	<?php echo Rb_Html::_('rb_html.text.filter', 'username', 'buyer', $filters, 'filter_paycart');?>
	<?php echo paycartHtml::_('paycarthtml.country.filter','country_id','buyer',$filters,'filter_paycart')?>
	
	<div><input type="submit" name="filter_submit" class="btn btn-primary" value="<?php echo JText::_('Go');?>" /></div>
	<div><input type="reset"  name="filter_reset"  class="btn" value="<?php echo JText::_('Reset');?>" onclick="paycart.admin.grid.filters.reset(this.form);" /></div>	
</div>
<?php 