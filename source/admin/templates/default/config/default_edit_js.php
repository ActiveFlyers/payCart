<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>

<script type="text/javascript">
paycart.admin.config = {};

(function($){

	paycart.admin.config.changeDefaultLanguage = {};
	paycart.admin.config.changeDefaultLanguage.init = function(){
		var link  = 'index.php?option=com_paycart&view=config&task=changeDefaultLanguage&action=init';
		paycart.ajax.go(link, null);
	};
	
	paycart.admin.config.changeDefaultLanguage.confirmed = function(){
		var language = $('#paycart_config_form_localization_default_language').val();
		if(language.length <= 0){
			return false;
		}

		var link  = 'index.php?option=com_paycart&view=config&task=changeDefaultLanguage&action=confirmed&language='+language;
		paycart.ajax.go(link, null);
	};

	paycart.admin.config.changeDefaultLanguage.do = function(language){		
		if(language.length <= 0){
			return false;
		}		
		var link  = 'index.php?option=com_paycart&view=config&task=changeDefaultLanguage&action=do&language='+language;
		paycart.ajax.go(link, null);
	};

	paycart.admin.config.changeDefaultLanguage.done = function(language){
		$('#pc-confic-localization-default-language').html(language);
		return true;
	}


	paycart.admin.config.updateSupportedLanguage = {};
	paycart.admin.config.updateSupportedLanguage.init = function(){
		var link  = 'index.php?option=com_paycart&view=config&task=updateSupportedLanguage&action=init';
		paycart.ajax.go(link, null);
	};
	
	paycart.admin.config.updateSupportedLanguage.confirmed = function(){
		var languages = $('#paycart_config_form_localization_supported_language').val();
		if(languages.length <= 0){
			languages = [];
		}
				
		languages = JSON.stringify(languages);
		
		var link  = 'index.php?option=com_paycart&view=config&task=updateSupportedLanguage&action=confirmed';
		paycart.ajax.go(link, {'languages' : languages});
	};

	paycart.admin.config.updateSupportedLanguage.do = function(languages){
		languages = JSON.stringify(languages);
		
		var link  = 'index.php?option=com_paycart&view=config&task=updateSupportedLanguage&action=do';
		paycart.ajax.go(link, {'languages' : languages});
	};

	paycart.admin.config.updateSupportedLanguage.done = function(language){
		$('#pc-confic-localization-supported-language').html(language);
		return true;
	}
	
})(paycart.jQuery);
</script>
<?php 
