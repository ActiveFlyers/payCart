<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
* @author		rimjhim
*/

defined('_JEXEC') or die( 'Restricted access' );

$currency = PaycartFactory::getHelper('format')->currency(PaycartFactory::getConfig()->get('localization_currency'));

?>

<script type="text/javascript">
(function($){
	var nextCounter = <?php echo (!empty($config->range))?max(array_keys((array)$config->range))+1:1; ?>;
	paycart.shipping = {};
	paycart.shipping.flatrate = {};
	
	paycart.shipping.flatrate.addRange = function(){
		var currency = '<?php echo $currency?>';
		var counter  = $('*[class^="pc-shipping-flaterate-range-"]').length + 1;
		var html 	 = '<div class="pc-shipping-flaterate-range-'+nextCounter+'">'+
					   '<input class="input-small" type="text" name="paycart_form[processor_config][range]['+nextCounter+'][min]" value=""/>&nbsp;'+
				       '<input class="input-small" type="text" name="paycart_form[processor_config][range]['+nextCounter+'][max]" value=""/>&nbsp;'+
				       '<span class="input-prepend">'+
					   '<span class="add-on">'+currency+'</span><input type="text" class="input-small" name="paycart_form[processor_config][range]['+nextCounter+'][price]" value=""/>'+
					   '</span>'+
					   ' <a href="javascript:void(0)"><i class="fa fa-trash-o" onClick="paycart.shipping.flatrate.deleteRange('+nextCounter+')"></i></a>'+
					   '<br><br></div>';
		$('.pc-shipping-flatrate-range').append(html);
		nextCounter++;
	},

	paycart.shipping.flatrate.deleteRange = function(counter){
		$('.pc-shipping-flaterate-range-'+counter).remove();
	}
	
})(paycart.jQuery);
</script>
<br>

<div class="row_fluid">
	<div class="control-group">
		<label title="" class="hasTooltip control-label">
			<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_OUT_OF_RANGE');?>
		</label>
		<div class="controls">
			<select name="paycart_form[processor_config][out_of_range]">
				<option value="<?php echo PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE;?>" <?php echo isset($config->out_of_range) && $config->out_of_range == PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE ? 'selected="selected"' : '';?>>
					<?php echo JText::_("Highest range price");?>
				</option>
				<option value="<?php echo PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY;?>" <?php echo isset($config->out_of_range) && $config->out_of_range == PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY ? 'selected="selected"' : '';?>>
					<?php echo JText::_("Do not apply");?>
				</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label title="" class="hasTooltip control-label">
			<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_BILLING');?>
		</label>
		<div class="controls">
			<fieldset class="radio btn-group" id="paycart_form_billing">
				<input type="radio" id="paycart_form_billing_price" name="paycart_form[processor_config][billing]" value="<?php echo PaycartShippingruleProcessorFlatRate::PRICE;?>" 
							<?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::PRICE ? 'checked="checked"' : '';?>>
				<label for="paycart_form_billing_price" class="btn <?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::PRICE ? 'active btn-success' : '';?>"><?php echo JText::_("According to price");?></label>
				<input type="radio" id="paycart_form_billing_weight" name="paycart_form[processor_config][billing]" value="<?php echo PaycartShippingruleProcessorFlatRate::WEIGHT;?>" 
							<?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::WEIGHT ? 'checked="checked"' : '';?>><?php echo JText::_("According to weight");?>
				<label for="paycart_form_billing_weight" class="btn <?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::WEIGHT ? 'active btn-success' : '';?>"><?php echo JText::_("According to weight");?></label>
			</fieldset>
		</div>
	</div>
		
	<div class="control-group">
		<label title="" class="hasTooltip control-label">
			<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_RANGE');?>
		</label>
		
		<div class="controls pc-shipping-flatrate-range">
			<button class="btn" type="button" onClick="paycart.shipping.flatrate.addRange()" ><?php echo JText::_("Add Range");?></button><br><br>
			<?php if(empty($config->range)):?>
					<div class="pc-shipping-flaterate-range-0">
						<input class="input-small" type="text" name="paycart_form[processor_config][range][0][min]" value=""/>
						<input class="input-small" type="text" name="paycart_form[processor_config][range][0][max]" value=""/>
						<span class="input-prepend">
							<span class="add-on"><?php echo $currency;?></span>
							<input type="text" class="input-small" name="paycart_form[processor_config][range][0][price]" value=""/>
						</span>
						<a href="javascript:void(0)"><i class="fa fa-trash-o" onClick="paycart.shipping.flatrate.deleteRange(0)"></i></a>
					<br><br></div>
				<?php else :?>
				<?php foreach ($config->range as $key => $value):?>
					<div class="pc-shipping-flaterate-range-<?php echo $key?>">
						<input class="input-small" type="text" name="paycart_form[processor_config][range][<?php echo $key?>][min]" value="<?php echo $value->min;?>"/>
						<input class="input-small" type="text" name="paycart_form[processor_config][range][<?php echo $key?>][max]" value="<?php echo $value->max;?>"/>
						<span class="input-prepend">
							<span class="add-on"><?php echo $currency;?></span>
							<input type="text" class="input-small" name="paycart_form[processor_config][range][<?php echo $key?>][price]" value="<?php echo $value->price;?>"/>
						</span>
						<a href="javascript:void(0)"><i class="fa fa-trash-o" onClick="paycart.shipping.flatrate.deleteRange(<?php echo $key;?>)"></i></a>
					<br><br></div>
				<?php endforeach;?>				
			<?php endif;?>
		</div>
		
	</div>
</div>
<?php 
