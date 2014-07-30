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

<div class="row-fluid">
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
						<ul class="thumbnails">
			    			<li class="thumbnail">		    									
	    						<a href="#" onClick="return false;">
	    						<img src="<?php echo $logo['original']; ?>" alt="">
	    						</a>
	    						<div>		    										
	    							<span class="pull-right"><a href="#" onClick="return false;" class="muted">
	    								<i class="fa fa-trash-o"></i></a>
	    							</span>
	    						</div>
	    					</li>		    									    								
	    				</ul>
						<?php endif;?>	    			
						<div class="row-fluid">								
							<input type="file" name="paycart_form[company_logo]">
						</div>	
					</div>
				</div>
		</div>
		
	</div>			
</div>