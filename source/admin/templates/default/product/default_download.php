<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		CARSELECTOR
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<!-- CONTENT START -->
<style>
<!--
	.rb-car-selector img.cs-logo{
		width:200px;
		height:200px;
		margin-top:5px;
	}
-->
</style>

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');
			echo $helper->render('index.php?option=com_paycart&view=product'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10 pc-import-data">
	<?php $uri = PaycartRoute::_("index.php?option=com_paycart&view=product");?>
	<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form pc-product-iniImport" enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="span5">
				<h2 class="text-info"><?php echo JText::sprintf('COM_PAYCART_ADMIN_EXPORT_DOWNLOAD_DATA');?></h2	>
				<div>
					<h5><span class="muted"><?php echo JText::_('COM_PAYCART_ADMIN_EXPORT_DOWNLOAD_DATA_MSG');?></span></h5>
					<p><?php echo JText::_('COM_PAYCART_ADMIN_EXPORT_DOWNLOAD_DATA_MSG_NOTE');?></p>
				</div>				
				<hr/>
			</div>
			
			<div class="span5">
				<?php if(count($paths)){?>
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<td class="center"><h4><?php echo JText::sprintf('COM_PAYCART_ADMIN_EXPORT_DOWNLOAD_FILE_DOWNLOAD_LINK');?></h4></td>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($paths as $name => $path){
								echo "<tr><td class='center'><a target='_blank' href='{$path}'>$name</a></td></tr>";
							}
						?>
					</tbody>
				</table>
				<?php }else{
					echo "<h4><span class=text-error>".JText::_("COM_PAYCART_ADMIN_EXPORT_NO_FILE_TO_DOWNLOAD")."</span></h4	>";
				}?>
			</div>
		</div>
		<input type="hidden" name="task" value="" />
	</form>
</div>