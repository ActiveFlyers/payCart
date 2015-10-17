<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		Neelam Soni
*/

defined('_JEXEC') or die( 'Restricted access' );
echo Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);
?>
<div class="text-center text-error hide" data-pc-selector="import-error">
	<span><?php echo JText::_("COM_PAYCART_ADMIN_PRODUCT_IMPORT_FAILED")?></span>
</div>

<div class="paycart">
	<form class="pc-import-map-fields form-horizontal" method="post">
		
		<div class="row-fluid">
			<span class="text-info"><h4><?php echo JText::_("COM_PAYCART_IMPORT_VERIFY_LANGUAGE");?></h4></span>
		</div>
		
		<div class="row-fluid">
			<p><?php echo JText::sprintf("COM_PAYCART_IMPORT_CURRENT_LANGUAGE" , $current_language);?></p>
			<?php $field = $form->getField('localization_default_language') ?>
			<div class="control-group">
				<div class="control-label text-error"><?php echo JText::_("COM_PAYCART_IMPORT_DATA_IN_LANGUAGE")?> </div>
				<div class="controls">
					<?php echo $field->input; ?>
				</div>
			</div>
			<hr/>
		</div>
		
		<div class="row-fluid">
			<span class="text-info"><h4><?php echo JText::_("COM_PAYCART_IMPORT_MAP_CSV_FIELDS");?></h4></span>
		</div>
		<div class="row-fluid">
			<?php 
				 foreach ($csv_headers as $csv_header)
				 {?>
					<div class="control-group">
						<div class="control-label"><?php echo $csv_header;?> </div>
						<div class="controls">
							<select id="<?php echo $csv_header;?>" name="<?php echo $csv_header;?>">
								<?php 
									foreach ($options as $key => $option)
									{
										if($key == $csv_header)
										{
											$option = '<option selected="selected" value="'.$key.'">'.$key.'</option>';
										}
										echo $option;
									}
								?>
							</select>
						</div>
					</div>
		   <?php }
		   ?>
		</div>
	</form>
</div>
<?php 