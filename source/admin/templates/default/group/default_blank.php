<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>
<div class="pc-product-wrapper clearfix">
<div class="pc-group row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=group'); 
	?>
</div>
<!-- ADMIN MENU -->


<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="center muted">
			<div>
				<h1>&nbsp;</h1>
				<i class="fa fa-group fa-5x"></i>
			</div>
			
			<div>
				<h3><?php echo JText::_('COM_PAYCART_ADMIN_GROUP_GRID_BLANK_MSG');?></h3>
			</div>
		</div>
	</div>
	<div class="row-fluid">	
		<div class="center">
			<?php echo $this->loadTemplate('select_type');?>
		</div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
</div>
<!-- CONTENT END -->

</div>
</div>
<?php 