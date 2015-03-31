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

<div class="well">
	<div class="row-fluid">
		<div class="span8">
			<div class="row-fluid pc-filter-row">
				<div class="pc-filter-minwidth-100 span3">
					<label><?php echo JText::_("COM_PAYCART_USERNAME").' / '.JText::_("COM_PAYCART_EMAIL")?></label>
					<?php echo paycartHtml::_('paycarthtml.text.filter', 'username', 'buyer', $filters, 'filter_paycart',array('class'=>'pc-filter-width'));?>
				</div>
				
				<div class="pc-filter-minwidth-150 span3 pc-filter-gap-top">	
					<?php echo paycartHtml::_('paycarthtml.country.filter','country_id','buyer',$filters,'filter_paycart')?>
				</div>
		
				<div class="pc-filter-minwidth-150 span3 pc-filter-gap-top">	
					<?php echo rb_Html::_('rb_html.jusertype.filter','usertype','buyer',$filters,'filter_paycart')?>
				</div>

				<div class="pc-filter-minwidth-50 span2 pc-filter-gap-top">
					<input type="submit" name="filter_submit" class="btn-block btn btn-primary" value="<?php echo JText::_('COM_PAYCART_ADMIN_GO');?>" />
					<input type="reset"  name="filter_reset"  class="btn-block btn" value="<?php echo JText::_('COM_PAYCART_ADMIN_RESET');?>" onclick="paycart.admin.grid.filters.reset(this.form);" />
				</div>
			</div>
		</div>
	</div>
</div>
<?php 