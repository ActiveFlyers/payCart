<?php

// Load Basic define constant
require_once realpath(dirname(__DIR__)).'/defines.php';
//Load Selenium configuration
require_once RBTEST_CORE_PATH.'/system/servers/config.php';
require_once 'RbWebdriverTestCase.php';

//require_once 'SeleniumClient/DesiredCapabilities.php';

class SeleniumClientAutoLoader {

	// Array of page class files
	private $pageClassFiles = array();

	public function __construct() {
		spl_autoload_register(array($this, 'seleniumClientLoader'));
	}

	private function seleniumClientLoader($className)
	{
		// path where it will test
		$availble_path = Array(
								RBTEST_CORE_PATH.'/system/'
							);
					
		$fileName = str_replace("\\", "/", $className) . '.php';
		$is_exist = false;
		foreach($availble_path as $path) {
			$file_path = $path.$fileName ;
			if(file_exists($file_path)) {
				include_once str_replace("\\", "/", $className) . '.php';
				$is_exist = true;
				break;
			}
		}
		if (!$is_exist) {
			die("\nSOMTHING IS GOING WRONG.............. :( need to fix \n CLASS NAME : $className \n FILE PATH : $file_path \n\n");
		}
	}
}

$autoloader = new SeleniumClientAutoLoader();