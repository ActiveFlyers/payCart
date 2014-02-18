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
<form action="<?php echo $uri; ?>" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span3">&nbsp;</div>
		<div class="span6 pc-blank-heading">
			<?php echo $heading; ?>
			<p class="muted"><?php echo $msg; ?></p>
		</div>
		<div class="span3">&nbsp;</div>
	</div>

	<div class="row-fluid">	
		<div class="center">
			<a href="<?php echo JUri::base().'index.php?option=com_paycart&view=group&task=new';?>" class="btn btn-success"><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo Rb_Text::_('New Group');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-question-sign "></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_SUPPORT_LINK');?></a>
			<a href="http://www.joomlaxi.com/" target="_blank" class="btn disabled"><i class="icon-book"></i>&nbsp;<?php echo Rb_Text::_('COM_PAYCART_DOCUMENTATION_LINK');?></a>
		</div>
	</div>
	
	<input type="hidden" name="task" value="" />
</form>
<?php 