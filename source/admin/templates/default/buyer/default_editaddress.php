<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		Manish Trivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>

<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm" class="rb-validate-form" enctype="multipart/form-data">

	<fieldset>
		<?php foreach ($form->getFieldset('buyeraddress') as $field):?>		
			<div class="control-group">
				<div class="control-label">	<?php echo $field->label; ?> 	</div>
				<div class="controls">		<?php echo $field->input; ?>	</div>								
			</div>
				
		<?php endforeach;?>
	</fieldset>
	
	<input type="hidden" name="task" value="save" />
	<input type='hidden' name='id' value='<?php echo $record_id;?>' />

</form>




