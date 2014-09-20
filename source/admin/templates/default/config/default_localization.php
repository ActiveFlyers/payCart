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

Rb_HelperTemplate::loadMedia(array('angular'));
?>

<script type="text/javascript">
	paycart.ng.config = angular.module('pcngConfigApp', []);
</script>

<?php 
echo $this->loadTemplate('edit_ng');
?>

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
							<input type="file" name="paycart_form[company_logo]">
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
			  //$display_data = new stdClass();
			  $display_data = $origin_address; 
			  $display_data->prefix = 'paycart_form[localization_origin_address]';
		?>
		<?php $layout = new JLayoutFile('paycart_buyeraddress_edit', PAYCART_PATH_ADMIN_LAYOUTS);?>
		<?php echo $layout->render($display_data);?>
	</div>			
</div>