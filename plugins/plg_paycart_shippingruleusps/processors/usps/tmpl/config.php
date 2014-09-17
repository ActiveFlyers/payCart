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

//set default value if not set
$config->calculation_mode = isset($config->calculation_mode)?$config->calculation_mode:'ONEPACKAGE';
$config->service_code = isset($config->service_code)?$config->service_code:'FIRST CLASS';
$config->packaging_type = isset($config->packaging_type)?$config->packaging_type:'VARIABLE';
$config->packaging_size = isset($config->packaging_size)?$config->packaging_size:'REGULAR';
$config->machinable = isset($config->machinable)?$config->machinable:1;
?>
<br>

<hr/>
<div class="row-fluid">
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_USER_ID');?>
			</label>
			<div class="controls">
				<input type="text" name="paycart_form[processor_config][user_id]" id="pc-processor-usps-userid" value="<?php echo isset($config->user_id)?$config->user_id:''?>">
			</div>	
		</div>
	
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_CALCULATION_MODE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$calculationMode,'paycart_form[processor_config][calculation_mode]','','value','title',$config->calculation_mode,'pc-processor-usps-calculation-mode')?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$serviceCode,'paycart_form[processor_config][service_code]','','value','title',$config->service_code,'pc-processor-usps-service-code')?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$packagingType,'paycart_form[processor_config][packaging_type]','','value','title',$config->packaging_type,'pc-processor-usps-packaging-type')?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_SIZE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$packagingSize,'paycart_form[processor_config][packaging_size]','','value','title',$config->packaging_size, 'pc-processor-usps-packaging-size')?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_MACHINABLE');?>
			</label>
			<div class="controls">
				<fieldset class="radio btn-group">
					<input type="radio" id="pc-processor-usps-machinable-yes" 
						   name="paycart_form[processor_config][machinable]" value="1" 
								<?php echo $config->machinable == 1 ? 'checked="checked"' : '';?>>
					<label for="paycart_form_machinable" class="btn <?php echo $config->machinable == 1 ? 'active btn-success' : '';?>"><?php echo JText::_("JYES");?></label>
					
					<input type="radio" id="pc-processor-usps-machinable-no" 
						   name="paycart_form[processor_config][machinable]" value="0" 
								<?php echo $config->machinable == 0 ? 'checked="checked"' : '';?>>
					<label for="paycart_form_machinable" class="btn <?php echo $config->machinable == 0 ? 'active btn-danger' : '';?>"><?php echo JText::_("JNO");?></label>
				</fieldset>
			</div>	
		</div>
</div>
<?php 