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

//set default value
$config->associated_address = isset($config->associated_address)?$config->associated_address : 'billing';
?>
<hr/>
<div class="row-fluid">
	<div class="span6">
			<div class="control-group">
				<label title="" class="hasTooltip control-label">
					<?php echo JText::_('PLG_PAYCART_TAXRULE_EUVAT_ASSOCIATED_ADDRESS');?>
				</label>
				<div class="controls">
					<fieldset class="radio btn-group" id="<?php echo $namePrefix?>_associated_address">
						<input type="radio" name="<?php echo $namePrefix?>[processor_config][associated_address]" 
									value="billing" id="<?php echo $namePrefix?>_associated_address_billing"
									<?php echo $config->associated_address == 'billing' ? 'checked="checked"' : '';?>>
						<label for="<?php echo $namePrefix?>_associated_address_billing" class="btn <?php echo $config->associated_address == 'billing' ? 'active btn-success' : '';?>"><?php echo JText::_("COM_PAYCART_ADDRESS_BILLING");?></label>
						
						<input type="radio" name="<?php echo $namePrefix?>[processor_config][associated_address]" 
									value="shipping" id="<?php echo $namePrefix?>_associated_address_shipping"
									<?php echo $config->associated_address == 'shipping' ? 'checked="checked"' : '';?>>
						<label for="<?php echo $namePrefix?>_associated_address_shipping" class="btn <?php echo $config->associated_address == 'shipping' ? 'active btn-success' : '';?>"><?php echo JText::_("COM_PAYCART_ADDRESS_SHIPPING");?></label>
					</fieldset>
					
				</div>	
			</div>
	</div>
</div>