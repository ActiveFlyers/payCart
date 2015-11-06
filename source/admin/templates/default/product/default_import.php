<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
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
	<?php $uri = PaycartRoute::_("index.php?option=com_paycart&view=product&task=initImport");?>
	<form target="pc-import-iframe" action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form pc-product-iniImport" enctype="multipart/form-data">
		<fieldset class="form-horizontal">
			<br/>
			<div class="row-fluid">				
				<h1><?php echo JText::sprintf('COM_PAYCART_ADMIN_IMPORT_DETAILS_HEADER' , 'Products');?></h1>
				<div>
					<h4><span class="muted"><?php echo JText::_('COM_PAYCART_ADMIN_IMPORT_DETAILS_HEADER_MSG');?></span></h4>
				</div>				
				<hr/>
			</div>
			
			<div id="pc-import-upload-csv-file" class="panel-group" id="accordion">
			    <div class="pc-panel pc-panel-default">
			      <div class="pc-panel-heading">
			        <h4 class="pc-panel-title">
			          <a data-toggle="collapse" data-parent="#accordion" href="#pc-import-upload-file"><h3><?php echo JText::_("COM_PAYCART_ADMIN_IMPORT_STEP_ONE");?></h3></a>
			        </h4>
			      </div>
			      <div id="pc-import-upload-file" class="panel-collapse collapse in">
			        <div class="pc-panel-body">
			        	<div class="row-fluid">
			        		<div class="row-fluid">
								<?php $entity = 'product';?>
								<span class="text-muted"><?php echo JText::_("COM_PAYCART_ADMIN_IMPORT_NEW_RECORDS_WARNING");?></span><br/>
							</div>	
			        		<div class="span3">
			        			<ul>
			        				<li><label><?php echo JText::_("COM_PAYCART_ADMIN_IMPORT_UPLOAD_CSV");?></label></li>
			        				<li><label><?php echo JText::sprintf("COM_PAYCART_ADMIN_IMPORT_HELP_MESSAGE_ONE" , "product_id");?></label></li>
			        				<?php echo JText::sprintf("COM_PAYCART_ADMIN_IMPORT_HELP_MESSAGE_TWO" , "product" , "product");?>
			        			</ul>
			        		</div>
		        			<div class="span9">
			        			<div class="control-group">							
									<input type="file" class="btn btn-default pc-upload-csv" name="fileToUpload" id="pc-fileToUpload">
	    							<a href="#" class="btn btn-success" onClick="paycart.admin.product.initImport()">
										<?php echo Rb_Text::_('COM_PAYCART_ADMIN_UPLOAD_CSV');?>
									</a>
									<iframe id='pc-import-iframe' name='pc-import-iframe' src="" class="pc-import-iframe">
									</iframe>				
								</div>
			        		</div>
			        	</div>
			        </div>
			      </div>
			    </div>
			    
			    <div id="pc-import-map-and-import" class="pc-panel pc-panel-default">
			      <div class="pc-panel-heading">
			        <h4 class="pc-panel-title">
			          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><h3><?php echo JText::_("COM_PAYCART_ADMIN_IMPORT_STEP_TWO");?></h3></a>
			        </h4>
			      </div>
			      <div id="collapse2" class="panel-collapse collapse">
			        <div class="pc-panel-body">
			        	<div class="span4">
			        		<div class="row-fluid">
				        		<ul>			        		
				        			<li><label><?php echo JText::_("COM_PAYCART_ADMIN_IMPORT_MAP_FIELDS");?></label></li>
				        		</ul>
			        		</div>
		        		</div>
	        			<div class="span8">
		        			<div class="control-group">							
								<a href="#" class="btn btn-success btn-large" onClick="paycart.admin.product.mapImportedFields()">
									<?php echo Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_IMPORT_TITLE');?>
								</a>				
							</div>
		        		</div>
			        </div>
			      </div>
			    </div>
			    
			    <div id="pc-imported-data-summary" class="pc-panel pc-panel-default">
			      <div class="pc-panel-heading">
			        <h4 class="pc-panel-title">
			          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><h3><?php echo JText::_("COM_PAYCART_ADMIN_IMPORT_SUMMARY");?></h3></a>
			        </h4>
			      </div>
			      <div id="collapse3" class="panel-collapse collapse">
			        <div class="pc-panel-body" id="pc-imported-data-summary-body">
			        	<?php if(empty($summary))
			        				echo JText::_("COM_PAYCART_ADMIN_NO_SUMMARY");
			        		  else
			        		  		echo $summary;
			        	?>			        	
			        </div>
			      </div>
			    </div>
			</div>
		</fieldset>
		<input type="hidden" name="task" value="initImport" />
	</form>
</div>
<?php 