<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * edit screen of configuration
 * 
 * @since 1.0.0
 *  
 * @author Rimjhim
 */

echo $this->loadTemplate('edit_js');
echo $this->loadTemplate('edit_ng');
?>

<div class="row-fluid">
	<div class="span3">
		<h2><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_LANGUAGE_HEADER');?></h2>
		<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_LOCALIZATION_LANGUAGE_HEADER_MSG');?>
		</div>
	</div>
	<div class="span9">
		<div class="row-fluid">
			<div class="span6">
				<?php $field = $form->getField('localization_default_language') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls">
						<div id="pc-confic-localization-default-language"> <?php echo PaycartHtmlLanguageflag::getFlag($field->value, true); ?></div>
						<br/>
						<div><a href="#pc-config-localization-defaultlang-modal" data-toggle="modal" onClick="return paycart.admin.config.changeDefaultLanguage.init();"><i class="fa fa-edit"></i> <?php echo JText::_('COM_PAYCART_ADMIN_CHANGE');?></a></div>
						<div id="pc-config-localization-defaultlang-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:800px; margin-left:-400px;" data-backdrop="static" data-keyboard="false">
							&nbsp;
						</div>
					</div>
				</div>
			</div>
			<div class="span6">
				<?php $field = $form->getField('localization_supported_language') ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls">
						<div id="pc-confic-localization-supported-language"> 
							<?php foreach($field->value as $language) : ?>
								<div><?php echo PaycartHtmlLanguageflag::getFlag($language, true);?></div>
							<?php endforeach; ?>
						</div>
						<br/>						
						<div><a href="#pc-config-localization-supportedlang-modal" data-toggle="modal" onClick="return paycart.admin.config.updateSupportedLanguage.init();"><i class="fa fa-edit"></i> <?php echo JText::_('COM_PAYCART_ADMIN_CHANGE');?></a></div>
						<div id="pc-config-localization-supportedlang-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:800px; margin-left:-400px;" data-backdrop="static" data-keyboard="false">
							&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>		

<hr/>

<div class="row-fluid">
	<div class="span3">
		<h2><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_COUNTRY_HEADER');?></h2>
		<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_COUNTRY_HEADER_MSG');?>
		</div>
	</div>
	<div class="span9">
		<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('localization_date_format') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>			
				<div class="span6">
					<?php $field = $form->getField('localization_currency') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>					
		</div>
		
		<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('localization_currency_position') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>			
				<div class="span6">
					<?php $field = $form->getField('localization_currency_format') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>					
		</div>
		
		<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('localization_decimal_separator') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>			
				<div class="span6">
					<?php $field = $form->getField('localization_fraction_digit_count') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>					
		</div>
	</div>
</div>

<hr/>

<div class="row-fluid" data-ng-app="pcngConfigApp">
	<div class="span3">
		<h2><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_COMPANY_HEADER');?></h2>
		<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_COMPANY_HEADER_MSG');?>
		</div>
	</div>
	<div class="span9">
		<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('company_name') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>
				
				<div class="span6">
					<?php $field = $form->getField('company_address') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				</div>
		</div>
		
		<div class="row-fluid">
				<div class="span6">
					<?php $field = $form->getField('company_logo') ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?> </div>
						<?php if(!empty($logo)):?>
							<script>
								var pc_company_logo = <?php echo json_encode($logo);?>;
							</script>	
							<div data-ng-controller="pcngConfigLogoCtrl">		
								<ul data-ng-show="company_logo" class="thumbnails">
					    			<li class="thumbnail">		    									
			    						<a href="#" onClick="return false;">
			    						<img data-ng-src="{{ company_logo.thumbnail }}" alt="">
			    						</a>
		 	    						<div>		    										
			    							<span class="pull-right"><a href="#" onClick="return false;" class="muted" data-ng-click="remove(company_logo.media_id)">
			    								<i class="fa fa-trash-o"></i></a>
			    							</span>
			    						</div>
			    					</li>		    									    								
			    				</ul>
			    			</div>
						<?php endif;?>	    			
						<div class="row-fluid">								
							<input type="file" name="paycart_config_form[company_logo]">
						</div>	
					</div>
				</div>
		</div>
		
	</div>			
</div>

<hr/>

<div class="row-fluid">
	<div class="span3">
		<h2><?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_WARE_HOUSE_HEADER');?></h2>
		<div>
		<?php echo JText::_('COM_PAYCART_ADMIN_CONFIG_WARE_HOUSE_HEADER_MSG');?>
		</div>
	</div>
	<div class="span9">
		<?php 
			  $display_data = $origin_address; 
			  $display_data->prefix = 'paycart_config_form[localization_origin_address]';
		?>
		<?php echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_edit', $display_data);?>
	</div>			
</div>