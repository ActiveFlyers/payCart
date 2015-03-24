<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	backend
* @contact 		support+paycart@readybytes.in
* @author 		rimjhim jain
*/
defined('_JEXEC') or die();
?>
<div class="pc-buyer-wrapper clearfix">
<div class="pc-buyer row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=buyer'); 
	?>
</div>
<!-- ADMIN MENU -->


<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">

	<?php echo $this->loadTemplate('filter'); ?>
	
	<div class="row-fluid">
		<div class="center muted">
			<div>
				<h1>&nbsp;</h1>
				<i class="fa fa-tags fa-5x"></i>
			</div>
			
			<div>
				<h3><?php echo JText::_('COM_PAYCART_ADMIN_BUYER_GRID_BLANK_MSG');?></h3>
			</div>
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