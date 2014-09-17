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

$calculationMode = array( 
						  0 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_CACLULATION_MODE_ONEPACKAGE'), 'value' => 'ONEPACKAGE'),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_CACLULATION_MODE_PERITEM'),'value' => 'PERITEM')
					);
$config->calculation_mode = isset($config->calculation_mode)?$config->calculation_mode:'ONEPACKAGE';
					
$serviceCode = array( 
						  0 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_FIRST_CLASS'), 'value' => 'FIRST CLASS'),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_FIRST_CLASS_COMMERCIAL'),'value' => 'FIRST CLASS COMMERCIAL'),
						  2 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_PRIORITY'), 'value' => 'PRIORITY'),
						  3 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_PRIORITY_COMMERCIAL'), 'value' => 'PRIORITY COMMERCIAL'),
						  4 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_EXPRESS'), 'value' => 'EXPRESS'),
						  5 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_EXPRESS_COMMERCIAL'), 'value' => 'EXPRESS COMMERCIAL'),
						  6 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_PARCEL'), 'value' => 'PARCEL'),
						  7 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_MEDIA'), 'value' => 'MEDIA'),
						  8 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE_LIBRARY'), 'value' => 'LIBRARY')
					);
$config->service_code = isset($config->service_code)?$config->service_code:'FIRST CLASS';

$packagingType = array(
						  0 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_VARIABLE'), 'value' => 'VARIABLE'),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_FLAT_RATE_ENVELOPE'),'value' => 'FLAT RATE ENVELOPE'),
						  2 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_PADDED_FLAT_RATE_ENVELOPE'), 'value' => 'PADDED FLAT RATE ENVELOPE'),
						  3 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_LEGAL_FLAT_RATE_ENVELOPE'), 'value' => 'LEGAL FLAT RATE ENVELOPE'),
						  4 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_SM_FLAT_RATE_ENVELOPE'), 'value' => 'SM FLAT RATE ENVELOPE'),
						  5 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_WINDOW_FLAT_RATE_ENVELOPE'), 'value' => 'WINDOW FLAT RATE ENVELOPE'),
						  6 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_GIFT_CARD_FLAT_RATE_ENVELOPE'), 'value' => 'GIFT CARD FLAT RATE ENVELOPE'),
						  7 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_FLAT_RATE_BOX'), 'value' => 'FLAT RATE BOX'),
						  9 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_SM_FLAT_RATE_BOX'), 'value' => 'SM FLAT RATE BOX'),
						  10 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_MD_FLAT_RATE_BOX'), 'value' => 'MD FLAT RATE BOX'),
						  11 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_LG_FLAT_RATE_BOX'), 'value' => 'LG FLAT RATE BOX'),
						  12 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_REGIONALRATEBOXA'), 'value' => 'REGIONALRATEBOXA'),
						  13 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_REGIONALRATEBOXB'), 'value' => 'REGIONALRATEBOXB'),
						  14 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_REGIONALRATEBOXC'), 'value' => 'REGIONALRATEBOXC'),
						  15 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_RECTANGULAR'), 'value' => 'RECTANGULAR'),
						  16 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE_NONRECTANGULAR'), 'value' => 'NONRECTANGULAR')
					);
$config->packaging_type = isset($config->packaging_type)?$config->packaging_type:'VARIABLE';
					
					
$packagingSize = array(
 						  0 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_SIZE_REGULAR'), 'value' => 'REGULAR'),
						  1 => array('title' => JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_SIZE_LARGE'),'value' => 'LARGE')
				);
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
				<input type="text" name="paycart_form[processor_config][user_id]" value="<?php echo isset($config->user_id)?$config->user_id:''?>">
			</div>	
		</div>
	
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_CALCULATION_MODE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$calculationMode,'paycart_form[processor_config][calculation_mode]','','value','title',$config->calculation_mode)?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_SERVICE_CODE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$serviceCode,'paycart_form[processor_config][service_code]','','value','title',$config->service_code)?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_TYPE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$packagingType,'paycart_form[processor_config][packaging_type]','','value','title',$config->packaging_type)?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_PACKAGING_SIZE');?>
			</label>
			<div class="controls">
				<?php echo PaycartHtml::_('select.genericlist',$packagingSize,'paycart_form[processor_config][packaging_size]','','value','title',$config->packaging_size)?>
			</div>	
		</div>
		
		<div class="control-group">
			<label title="" class="hasTooltip control-label">
				<?php echo JText::_('PLG_PAYCART_SHIPPINGRULE_USPS_MACHINABLE');?>
			</label>
			<div class="controls">
				<fieldset class="radio btn-group" id="paycart_form_machinable">
					<input type="radio" id="paycart_form_machinable" 
						   name="paycart_form[processor_config][machinable]" value="1" 
								<?php echo $config->machinable == 1 ? 'checked="checked"' : '';?>>
					<label for="paycart_form_machinable" class="btn <?php echo $config->machinable == 1 ? 'active btn-success' : '';?>"><?php echo JText::_("JYES");?></label>
					
					<input type="radio" id="paycart_form_machinable" 
						   name="paycart_form[processor_config][machinable]" value="0" 
								<?php echo $config->machinable == 0 ? 'checked="checked"' : '';?>>
					<label for="paycart_form_machinable" class="btn <?php echo $config->machinable == 0 ? 'active btn-success' : '';?>"><?php echo JText::_("JNO");?></label>
				</fieldset>
			</div>	
		</div>
</div>
<?php 