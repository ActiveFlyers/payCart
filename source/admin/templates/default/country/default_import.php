<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain 
*/

defined('_JEXEC') or die( 'Restricted access' );

echo Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);
?>

<div class="text-center text-error hide" data-pc-selector="import-error">
	<span><?php echo JText::_("COM_PAYCART_ADMIN_COUNTRY_IMPORT_FAILED")?></span>
</div>

<div class="text-center text-error hide" data-pc-selector="import-description">
	<span><?php echo JText::_('COM_PAYCART_ADMIN_COUNTRY_IMPORT_DESCRIPTION')?></span>
</div>

<div>
	<span class="pull-right">
		<a href="javascript:void(0)" data-pc-selector="all-countries"><?php echo JText::_('COM_PAYCART_ADMIN_COUNTRY_IMPORT_SELECT_ALL')?></a> / 
		<a href="javascript:void(0)" data-pc-selector="no-country"><?php echo JText::_('COM_PAYCART_ADMIN_COUNTRY_IMPORT_NONE')?></a>
	</span>
</div>
<br><br>
<div style="min-height:180px;">
	<select name="pc-country-select" id="pc-country-select" multiple="true" data-pc-selector="country" >
		<?php foreach($countries as $key => $data) :?>
			<?php if(in_array($data['isocode3'], $existingCountries)):?>
				<?php continue;?>
			<?php endif;?>
			<option value="<?php echo $key?>"><?php echo $data['title'];?></option>
		<?php endforeach;?>
	</select>
</div>

<script>
	(function($){		
		$("#pc-country-select").chosen({"disable_search_threshold":5,'width':'500px', "placeholder_text_multiple":"<?php echo JText::_("COM_PAYCART_ADMIN_COUNTRY_IMPORT_SELECT_PLACE_HOLDER")?>"});
	})(paycart.jQuery);
</script>
<?php 