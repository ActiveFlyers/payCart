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
?>

<script type="text/javascript">
(function($){
	$(document).ready(function(){
	    $(".pc-popover").popover();
	});
})(paycart.jQuery);
</script>

<?php 
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
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_ZIP_ORIGIN');?>
				</label>
				<div class="controls">
					<input type="text" name="<?php echo $namePrefix?>[processor_config][zip_origin]" id="pc-processor-usps-zip-origin" value="<?php echo isset($config->zip_origin)?$config->zip_origin:''?>">
				</div>
				<p><small><?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_ZIP_ORIGIN_HELP_MESSAGE')?></small></p>	
			</div>
			
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
					<select name="<?php echo $namePrefix?>[processor_config][machinable]">
						<option value="1" <?php echo $config->machinable == 1 ? 'selected="selected"' : '';?>><?php echo JText::_('JYES')?></option>
						<option value="0" <?php echo $config->machinable == 0 ? 'selected="selected"' : '';?>><?php echo JText::_('JNO')?></option>
					</select>
					<a href="javascript:void(0);" data-toggle="popover" class="pc-popover" title="<?php echo JText::_("JHELP")?>"
							 	data-content="<?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_USPS_MACHINABLE_POPOVER");?>">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>	
			</div>
			
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_MAIL_TYPE');?>
				</label>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$mailType,$namePrefix.'[processor_config][first_class_mail_type]','','value','title',$config->first_class_mail_type, 'pc-processor-usps-first_class_mail_type')?>
				</div>	
				<div><small><?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_MAIL_TYPE_NOTE')?></small></div>
			</div>
			
		</div>
</div>
<?php 