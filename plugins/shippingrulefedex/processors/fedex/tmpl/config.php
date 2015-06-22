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
<br>

<hr/>
<div class="row-fluid">
	<div class="span6">
			<div class="control-group">			
				<div class="control-label">
					<label for="pc-processor-fedex-account-number" id="pc-processor-fedex-account-number-lbl" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_ACCOUNT_NUMBER');?>
					</label>
				</div>
				<div class="controls">
					<input type="text" name="<?php echo $namePrefix?>[processor_config][account_number]" id="pc-processor-fedex-account-number" value="<?php echo isset($config->account_number)?$config->account_number:''?>" required="required">
					<div class="pc-error" for="pc-processor-fedex-account-number"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-fedex-meter-number" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_METER_NUMBER');?>
					</label>
				</div>
				<div class="controls">
					<input type="text" name="<?php echo $namePrefix?>[processor_config][meter_number]" id="pc-processor-fedex-meter-number" value="<?php echo isset($config->meter_number)?$config->meter_number:''?>" required="required">
					<div class="pc-error" for="pc-processor-fedex-meter-number"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-fedex-password" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PASSWORD');?>
					</label>
				</div>
				<div class="controls">
					<input type="text" name="<?php echo $namePrefix?>[processor_config][password]" id="pc-processor-fedex-password" value="<?php echo isset($config->password)?$config->password:''?>" required="required">
					<div class="pc-error" for="pc-processor-fedex-password"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-fedex-key" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_AUTHENTICATION_KEY');?>
					</label>
				</div>
				<div class="controls">
					<input type="text" name="<?php echo $namePrefix?>[processor_config][key]" id="pc-processor-fedex-key" value="<?php echo isset($config->key)?$config->key:''?>" required="required">
					<div class="pc-error" for="pc-processor-fedex-key"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-usps-calculation-mode" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_CALCULATION_MODE');?>
					</label>
				</div>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$calculationMode,$namePrefix.'[processor_config][calculation_mode]','required="required"','value','title',$config->calculation_mode,'pc-processor-usps-calculation-mode')?>
					<div class="pc-error pc-margin-top-0" for="pc-processor-usps-calculation-mode"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>					
				</div>	
			</div>
			
						
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-fedex-pickup-type" id="pc-processor-fedex-pickup-type-lbl" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PICKUP_TYPE');?>
					</label>
				</div>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$pickpTypes,$namePrefix.'[processor_config][pickup_type]','required="required"','value','title',$config->pickup_type,'pc-processor-fedex-pickup-type')?>
					<div class="pc-error pc-margin-top-0" for="pc-processor-fedex-pickup-type"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>
			
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-fedex-packaging-type" id="pc-processor-fedex-packaging-type-lbl" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_PACKAGING_TYPE');?>
					</label>
				</div>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$packagingType,$namePrefix.'[processor_config][packaging_type]','required="required"','value','title',$config->packaging_type,'pc-processor-fedex-packaging-type')?>
					<div class="pc-error pc-margin-top-0" for="pc-processor-fedex-packaging-type"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>			
			
			<div class="control-group">				
				<div class="control-label">
					<label for="pc-processor-fedex-service_code" id="pc-processor-fedex-service_code-lbl" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_SERVICE_CODE');?>
					</label>
				</div>
				<div class="controls">
					<?php echo PaycartHtml::_('select.genericlist',$services,$namePrefix.'[processor_config][service_code]','required="required"','value','title',$config->service_code,'pc-processor-fedex-service-code')?>
					<div class="pc-error pc-margin-top-0" for="pc-processor-fedex-service-code"><?php echo JText::_('COM_PAYCART_ADMIN_VALIDATION_ERROR_REQUIRED');?></div>
				</div>	
			</div>	
			
			<div class="control-group">
				<div class="control-label">
					<label for="pc-processor-fedex-test-mode" id="pc-processor-fedex-test-mode-lbl" class="required">
						<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_TEST_MODE');?>
					</label>
				</div>
				
				<div class="controls">
					<fieldset id="paycart_shippingrule_form_published" class="radio btn-group" aria-invalid="false">
						<input type="radio" id="paycart_shippingrule_form_testmode_0" name="paycart_shippingrule_form[processor_config][testmode]" value="1" checked="checked">
						<label for="paycart_shippingrule_form_testmode_0" class="btn active btn-success"><?php echo JText::_('JYES')?></label>
						<input type="radio" id="paycart_shippingrule_form_testmode_1" name="paycart_shippingrule_form[processor_config][testmode]" value="0">
						<label for="paycart_shippingrule_form_testmode_1" class="btn"><?php echo JText::_('JNO')?></label>
					</fieldset>					
				</div>
			
			</div>
		</div>
		
		<div class="span6">
			<fieldset>
				<legend>
					<?php echo JText::_("PLG_PAYCART_SHIPPINGRULE_FEDEX_SENDER_ADDRESS")?>
				</legend>
				<div><small><?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_FEDEX_ORIGIN_ADDRESS_NOTE')?></small></div><br>
				
				<div class="control-group">
				  <label class="control-label" for="pc-processor-fedex-country">
				  		<?php echo JText::_('COM_PAYCART_COUNTRY');?>
				  </label>
				  <div class="controls">
				  		<?php echo PaycartHtmlCountry::getList($namePrefix.'[processor_config][country_id]', isset($config->country_id)?$config->country_id:'','pc-processor-fedex-country_id', array('class'=>'validate-hidden'),'isocode2');?>			   
				  </div>
				</div>
			
				<div class="control-group">
				  <label class="control-label" for="pc-processor-fedex-zipcode">
				  		<?php echo JText::_('COM_PAYCART_ZIPCODE');?>
				  </label>
				  <div class="controls">
					    <input 	name="<?php echo $namePrefix?>[processor_config][zipcode]" 
								id="pc-processor-fedex-zipcode" 
								value="<?php echo isset($config->zipcode)?$config->zipcode :''?>"
								type="text">
				  </div>
				</div>
			
				<div class="control-group">
				  <label class="control-label" for="pc-processor-fedex-city">
				  		<?php echo JText::_('COM_PAYCART_CITY');?>
				  </label>
				  <div class="controls">
				    <input 	name="<?php echo $namePrefix?>[processor_config][city]" 
							id="pc-processor-fedex-city" 
							value="<?php echo isset($config->city)?$config->city:'' ?>"
				    	    type="text">
				  </div>
				</div>	
				
				<div class="control-group">
				  <label class="control-label" for="pc-processor-fedex-address">
				  		<?php echo JText::_('COM_PAYCART_ADDRESS');?>
				  </label>
				  <div class="controls">
				    <textarea name="<?php echo $namePrefix?>[processor_config][address]" 
							id="pc-processor-fedex-address"><?php echo isset($config->address)?$config->address:'' ?></textarea>
				  </div>
				</div>	
			</fieldset>
		</div>
</div>
<?php 