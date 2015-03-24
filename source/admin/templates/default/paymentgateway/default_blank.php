<?php

/**
* @copyright 	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license 		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage 	Back-end
* @author		support+paycart@readybytes.in
* 
*/

defined('_JEXEC') or die( 'Restricted access' );
?>

	<div class="pc-group row-fluid">
	
		<!-- ADMIN MENU -->
		<div class="span2">
			<?php
			$helper = PaycartFactory::getHelper('adminmenu');	
			echo $helper->render('index.php?option=com_paycart&view=paymentgateway');
			?>
		</div>
		
		<!-- CONTENT START -->
		
		<div class="span10">
		
			<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
				
				<?php echo $this->loadTemplate('filter'); ?>
			
				<div class="row-fluid">
					<div class="center muted">
						<div>
							<h1>&nbsp;</h1>
							<i class="fa fa-credit-card fa-5x"></i>
						</div>
						
						<div>
							<h3><?php echo JText::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_GRID_BLANK_MSG');?></h3>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="center">
						<a href="<?php echo JUri::base().'index.php?option=com_paycart&view=paymentgateway&task=new';?>" class="btn btn-success btn-large">
						<i class="icon-plus-sign icon-white"></i>&nbsp;
						<?php echo Rb_Text::_('COM_PAYCART_ADMIN_PAYMENTGATEWAY_ADD');?>
						</a>
					</div>
				</div>
				
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
			</form>							
		</div>
		<!-- CONTENT END -->
	
	
	</div>
<?php 