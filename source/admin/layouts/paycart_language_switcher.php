<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
$current_language = PaycartFactory::getPCCurrentLanguageCode();
$supported_language = PaycartFactory::getConfig()->get('localization_supported_language');
$languages = Rb_HelperJoomla::getLanguages();
?>
<div class="row-fluid">
	<div class="pull-right">
		<div class="btn-group">
			<button class="btn"><?php echo PaycartHtmlLanguageflag::getFlag($current_language, true);?></button>
			<button class="btn dropdown-toggle" data-toggle="dropdown">
		    	<span class="caret"></span>
		    </button>
		    
		    <ul class="dropdown-menu">
			    <?php foreach ($supported_language as $language) : ?>
					<li><a href="<?php echo $displayData->uri.'&lang_code='.$language;?>"><?php echo PaycartHtmlLanguageflag::getFlag($language, true);?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<?php 