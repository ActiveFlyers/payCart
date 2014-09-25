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
<!-- ADMIN MENU -->
<div class="span2">
<?php
$helper = PaycartFactory::getHelper('adminmenu');	
echo $helper->render('index.php?option=com_paycart&view=config');
?>
</div>
<!-- ADMIN MENU -->


<div class="span10">
<div class="row-fluid"> 
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="pc-form-validate" enctype="multipart/form-data">
	
	<ul id="paycartAdminConfigTabs" class="nav nav-tabs">
		<li class="active">
			<a data-toggle="tab" href="#paycartAdminConfigTabsSettings"><?php echo JText::_('COM_PAYCART_ADMIN_SETTINGS');?></a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#paycartAdminConfigTabsLocalization"><?php echo JText::_('COM_PAYCART_ADMIN_LOCALIZATION');?></a>
		</li>
	</ul>


	<div class="tab-content">
		<div class="tab-pane active" id="paycartAdminConfigTabsSettings">
			
			<?php echo $this->loadTemplate('catalogue');?>
			<hr/>
			
			<?php echo $this->loadTemplate('invoice');?>
		</div>
		
		<div class="tab-pane" id="paycartAdminConfigTabsLocalization">
			<?php echo $this->loadTemplate('localization');?>
		</div>
	</div>
	<input type="hidden" name="task" value="save" />
</form>
</div>
</div>
<?php 