<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	backend
* @contact 		support+paycart@readybytes.in
* @author 		rimjhim
*/
defined('_JEXEC') or die();

echo $this->loadTemplate('js');
?>
<div class="pc-product-wrapper clearfix">
<div class="pc-product row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=country'); 
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
				<i class="fa fa-flag fa-5x"></i>
			</div>
			
			<div>
				<h3><?php echo JText::_('COM_PAYCART_ADMIN_COUNTRY_GRID_BLANK_MSG');?></h3>
			</div>
		</div>
	</div>
	<div class="row-fluid">	
		<div class="center">
			<a href="<?php echo JUri::base().'index.php?option=com_paycart&view=country&task=new';?>" class="btn btn-success btn-large">
				<i class="icon-plus-sign icon-white"></i>&nbsp;
				<?php echo Rb_Text::_('COM_PAYCART_ADMIN_COUNTRY_ADD');?>
			</a>			
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