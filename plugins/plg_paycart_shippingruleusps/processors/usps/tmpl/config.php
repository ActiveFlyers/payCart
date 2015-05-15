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
$config->first_class_mail_type = isset($config->first_class_mail_type)?$config->first_class_mail_type:'PARCEL'
?>
<br>

<hr/>
<div class="row-fluid">
	<div class="span6">
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_USER_ID');?>
				</label>
				<div class="controls">
					<input type="text" name="<?php echo $namePrefix?>[processor_config][user_id]" id="pc-processor-usps-userid" value="<?php echo isset($config->user_id)?$config->user_id:''?>">
				</div>	
			</div>
		
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_CALCULATION_MODE');?>
				</label>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$calculationMode,$namePrefix.'[processor_config][calculation_mode]','','value','title',$config->calculation_mode,'pc-processor-usps-calculation-mode')?>
				</div>	
			</div>
			
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE');?>
				</label>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$serviceCode,$namePrefix.'[processor_config][service_code]','','value','title',$config->service_code,'pc-processor-usps-service-code')?>
				</div>	
			</div>
			
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE');?>
				</label>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$packagingType,$namePrefix.'[processor_config][packaging_type]','','value','title',$config->packaging_type,'pc-processor-usps-packaging-type')?>
				</div>	
			</div>
			
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_SIZE');?>
				</label>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$packagingSize,$namePrefix.'[processor_config][packaging_size]','','value','title',$config->packaging_size, 'pc-processor-usps-packaging-size')?>
				</div>	
			</div>
			
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_MACHINABLE');?>
				</label>
				<div class="controls">
					<fieldset class="radio btn-group">
						<input type="radio" id="pc-processor-usps-machinable-yes" 
							   name="<?php echo $namePrefix?>[processor_config][machinable]" value="1" 
									<?php echo $config->machinable == 1 ? 'checked="checked"' : '';?>>
						<label for="<?php echo $namePrefix?>_machinable" class="btn <?php echo $config->machinable == 1 ? 'active btn-success' : '';?>"><?php echo JText::_("JYES");?></label>
						
						<input type="radio" id="pc-processor-usps-machinable-no" 
							   name="<?php echo $namePrefix?>[processor_config][machinable]" value="0" 
									<?php echo $config->machinable == 0 ? 'checked="checked"' : '';?>>
						<label for="<?php echo $namePrefix?>_machinable" class="btn <?php echo $config->machinable == 0 ? 'active btn-danger' : '';?>"><?php echo JText::_("JNO");?></label>
					</fieldset>
				</div>	
			</div>
			
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_MAIL_TYPE');?>
				</label>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$mailType,$namePrefix.'[processor_config][first_class_mail_type]','','value','title',$config->first_class_mail_type, 'pc-processor-usps-first_class_mail_type')?>
				</div>	
			</div>
			
		</div>
		
		<div class="span6 well">
				<?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_USPS_HELP_MESSAGE")?> <a target="_blank" href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=config')?>"><?php echo JText::_('COM_PAYCART_ADMIN_CONFIGURATION')?></a>.
		</div>
</div>
<?php 