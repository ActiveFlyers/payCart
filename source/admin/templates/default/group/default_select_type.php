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

<style>

.paycart .table th, .table td {
 text-align: center;
}

</style>
	<div class="row-fluid">

	<?php foreach($availableGroupRules as $rule_type => $rules) : ?>
			<table class="table table-bordered table-responsive span4">
	            <tr class="row-fluid">	
					  <th  class="span12" ><b><?php echo ucfirst($rule_type)."  ".JText::_("COM_PAYCART_ADMIN_GROUP_RULES") ;?></b></th>
				</tr> 
		
				<?php foreach ($rules as $key => $rule):?>
		  				 <tr class="row-fluid">
						  	  <td class="span12" >
							  	  <a href="index.php?option=com_paycart&view=group&task=new&type=<?php echo $rule_type;?>">
				 				  	<?php echo JText::_($rule->title);?>
				 				  </a>
			 				  </td>
						  </tr>
					<?php endforeach; ?>
			</table>  
   <?php endforeach; ?>
	</div>	 

<?php 