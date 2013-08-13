<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>

<script>
	<?php include_once dirname(dirname(__FILE__)).'/_media/js/template.js'; ?>
</script>

<?php 	foreach ($form->getFieldset('attribute_config') as $field): ?>
			<?php if('attribute_type' === $field->fieldname): ?>
			<?php 		echo $field->input;  continue; ?>
			<?php endif; ?>
			<div class="control-group">
				<div class="control-label">	<?php echo $field->label; ?> 	</div>
				<div class="controls">		<?php echo $field->input; ?>	</div>								
			</div>
<?php endforeach;?>
