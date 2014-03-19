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
<div>
	<?php foreach($availiableGroupRules as $rule_type => $rule) : ?>
		<?php $rule = (object) $rule; ?>		
		<a class="btn btn-large" href="index.php?option=com_paycart&view=group&task=new&type=<?php echo $rule_type;?>">
			<h1>
				<?php echo JText::_($rule_type);?>
			</h1>
		</a>		
	<?php endforeach; ?>
</div>