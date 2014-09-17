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
$weightUnit = PaycartFactory::getConfig()->get('catalogue_weight_unit');

?>

<script type="text/javascript">
(function($){
	var nextCounter = <?php echo (!empty($config->range))?max(array_keys((array)$config->range))+1:1; ?>;
	paycart.shipping = {};
	paycart.shipping.flatrate = {};
	
	paycart.shipping.flatrate.addRange = function(){
		var currency = '<?php echo $currency?>';
		var counter  = $('*[class^="pc-shipping-flaterate-range-"]').length + 1;

		var lastMaxValue = $('.pc-shipping-flatrate-range tr:nth-last-child(2) td:nth-child(2) input').val();
			
		var html 	 = '<tr class="pc-shipping-flaterate-range-'+nextCounter+'">'+
					   '<td><input class="input-small" type="text" name="paycart_form[processor_config][range]['+nextCounter+'][min]" value="'+lastMaxValue+'"/>&nbsp;</td>'+
				       '<td><input class="input-small" type="text" name="paycart_form[processor_config][range]['+nextCounter+'][max]" value=""/>&nbsp;</td>'+
				       '<td><span class="input-prepend">'+
					   '<span class="add-on">'+currency+'</span><input type="text" class="input-small" name="paycart_form[processor_config][range]['+nextCounter+'][price]" value=""/>'+
					   '</span></td>'+
					   '<td><a href="javascript:void(0)"><i class="fa fa-trash-o" onClick="paycart.shipping.flatrate.deleteRange('+nextCounter+')"></i></a></td>'+
					   '</tr>';
		$('.pc-shipping-flatrate-range tr:last').before(html);
		nextCounter++;
	},

	paycart.shipping.flatrate.deleteRange = function(counter){
		$('.pc-shipping-flaterate-range-'+counter).remove();
	}

	paycart.shipping.flatrate.changeBilling = function(value)
	{
		if(value == 'PRICE'){
			$('.pc-shipping-flaterate-range-price').show();
			$('.pc-shipping-flaterate-range-weight').hide();
		}
		else{ 
			$('.pc-shipping-flaterate-range-weight').show();
			$('.pc-shipping-flaterate-range-price').hide();
		}
	}
	
	var val = $( "input:radio[name='paycart_form[processor_config][billing]']:checked" ).val();
	paycart.shipping.flatrate.changeBilling(val);
})(paycart.jQuery);
</script>
<br>

<hr/>

<div class="row_fluid">
	
	<div class="control-group">
		<label title="" class="hasTooltip control-label">
			<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_BILLING_BASED_ON');?>
		</label>
		<div class="controls">
			<fieldset class="radio btn-group" id="paycart_form_billing">
				<input onClick="paycart.shipping.flatrate.changeBilling(this.value)" type="radio" id="paycart_form_billing_price" 
					   name="paycart_form[processor_config][billing]" value="<?php echo PaycartShippingruleProcessorFlatRate::PRICE;?>" 
							<?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::PRICE ? 'checked="checked"' : '';?>>
				<label for="paycart_form_billing_price" class="btn <?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::PRICE ? 'active btn-success' : '';?>"><?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_FLATRATE_PRICE");?></label>
				
				<input onClick="paycart.shipping.flatrate.changeBilling(this.value)" type="radio" id="paycart_form_billing_weight" 
				       name="paycart_form[processor_config][billing]" value="<?php echo PaycartShippingruleProcessorFlatRate::WEIGHT;?>" 
							<?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::WEIGHT ? 'checked="checked"' : '';?>>
				<label for="paycart_form_billing_weight" class="btn <?php echo isset($config->billing) && $config->billing == PaycartShippingruleProcessorFlatRate::WEIGHT ? 'active btn-success' : '';?>"><?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_FLATRATE_WEIGHT");?></label>
			</fieldset>
		</div>
	</div>
		
	<div class="control-group">
		<label title="" class="hasTooltip control-label">
			&nbsp;
		</label>
		
		<div class="controls pc-shipping-flatrate-range">
		
			<table class="table table-responsive span6">
				<thead>
					<tr>
						<th>
							<span class="pc-shipping-flaterate-range-price"><?php echo ' >= '.JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_MIN').'( '. $currency.' )';?></span>
							<span class="pc-shipping-flaterate-range-weight"><?php echo '>= '. JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_MIN').'( '. $weightUnit.' )';?></span>
						</th>
						<th>
							<span class="pc-shipping-flaterate-range-price"><?php echo '< '.JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_MAX').'( '. $currency.' )';?></span>
							<span class="pc-shipping-flaterate-range-weight"><?php echo '< '.JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_MAX').'( '. $weightUnit.' )';?></span>
						</th>
						<th>
							<span><?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_SHIPPING_COST');?></span>
						</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<?php if(empty($config->range)):?>
					<tr class="pc-shipping-flaterate-range-0">
						<td><input class="input-small" type="text" name="paycart_form[processor_config][range][0][min]" value="0"/></td>
						<td><input class="input-small" type="text" name="paycart_form[processor_config][range][0][max]" value=""/></td>
						<td>
							<span class="input-prepend">
								<span class="add-on"><?php echo $currency;?></span>
								<input type="text" class="input-small" name="paycart_form[processor_config][range][0][price]" value=""/>
							</span>
						</td>
						<td><a href="javascript:void(0)"><i class="fa fa-trash-o" onClick="paycart.shipping.flatrate.deleteRange(0)"></i></a></td>
					</tr>
				<?php else :?>
				
					<?php foreach ($config->range as $key => $value):?>
						<tr class="pc-shipping-flaterate-range-<?php echo $key?>">
							<td><input class="input-small" type="text" name="paycart_form[processor_config][range][<?php echo $key?>][min]" value="<?php echo $value->min;?>"/></td>
							<td><input class="input-small" type="text" name="paycart_form[processor_config][range][<?php echo $key?>][max]" value="<?php echo $value->max;?>"/></td>
							<td>
								<span class="input-prepend">
									<span class="add-on"><?php echo $currency;?></span>
									<input type="text" class="input-small" name="paycart_form[processor_config][range][<?php echo $key?>][price]" value="<?php echo $value->price;?>"/>
								</span>
							</td>
							<td><a href="javascript:void(0)"><i class="fa fa-trash-o" onClick="paycart.shipping.flatrate.deleteRange(<?php echo $key;?>)"></i></a></td>
						</tr>
					<?php endforeach;?>				
				<?php endif;?>
					<tr>
						<td colspan="4">
							<button class="pull-right btn" type="button" onClick="paycart.shipping.flatrate.addRange()" ><i class="fa fa-plus"> <?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_FLATRATE_ADD_NEW_RANGE");?></i></button>
						</td>
					</tr>
			</table>
			
		</div>
		
	</div>
	
	<hr/>
	
	<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FLATRATE_OUT_OF_RANGE');?>
			</label>
			<div class="controls">
				<select name="paycart_form[processor_config][out_of_range]">
					<option value="<?php echo PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE;?>" <?php echo isset($config->out_of_range) && $config->out_of_range == PaycartShippingruleProcessorFlatRate::HIGHEST_RANGE_PRICE ? 'selected="selected"' : '';?>>
						<?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_FLATRATE_HIGHEST_RANGE_PRICE");?>
					</option>
					<option value="<?php echo PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY;?>" <?php echo isset($config->out_of_range) && $config->out_of_range == PaycartShippingruleProcessorFlatRate::DO_NOT_APPLY ? 'selected="selected"' : '';?>>
						<?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_FLATRATE_DO_NOT_APPLY");?>
					</option>
				</select>
			</div>
	</div>	

</div>
<?php 
